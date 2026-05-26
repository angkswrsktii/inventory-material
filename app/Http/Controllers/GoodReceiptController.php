<?php

namespace App\Http\Controllers;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\Material;
use App\Models\Mutasi;
use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodReceipt::with(['purchaseOrder', 'warehouse', 'receiver']);

        if ($request->search) {
            $query->where('gr_number', 'like', "%{$request->search}%")
                ->orWhere('delivery_note_number', 'like', "%{$request->search}%");
        }

        $goodReceipts = $query->latest()->paginate(10);
        return view('good-receipts.index', compact('goodReceipts'));
    }

    public function create(Request $request)
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['issued', 'partial'])
            ->with(['items.material'])
            ->get();

        $purchaseOrders = $purchaseOrders->map(function ($po) {
            $po->setRelation('items', $po->items->map(function ($item) {
                $receivedQty = GoodReceiptItem::where('t_purchase_order_item_id', $item->id)->sum('quantity');
                $item->remaining_quantity = $item->quantity - $receivedQty;
                return $item;
            })->filter(function ($item) {
                return $item->remaining_quantity > 0;
            })->values());
            return $po;
        })->filter(function ($po) {
            return $po->items->count() > 0;
        })->values();

        $warehouses = Warehouse::where('is_active', true)->get();

        // Kalau karyawan, hanya tampilkan dirinya sendiri
        if ($this->isKaryawanUser()) {
            $users = User::where('id', Auth::id())->where('is_active', true)->get();
        } else {
            $users = User::where('is_active', true)->orderBy('name')->get();
        }

        $latest     = GoodReceipt::latest('id')->first();
        $nextId     = $latest ? $latest->id + 1 : 1;
        $autoNumber = 'GR-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('good-receipts.create', compact('purchaseOrders', 'warehouses', 'users', 'autoNumber'));
    }

    public function store(Request $request)
    {
        // Karyawan dipaksa pakai id dirinya sendiri
        if ($this->isKaryawanUser()) {
            $request->merge(['m_pic_id' => Auth::id()]);
        }

        $request->validate([
            't_purchase_order_id'          => 'required|exists:t_purchase_orders,id',
            'm_warehouse_id'               => 'required|exists:m_warehouses,id',
            'm_pic_id'                     => 'required|exists:m_users,id',
            'receipt_date'                 => 'required|date',
            'delivery_note_number'         => 'nullable|string|max:100',
            'notes'                        => 'nullable|string',
            'items'                        => 'required|array|min:1',
            'items.*.m_material_id'        => 'nullable|exists:m_materials,id',
            'items.*.quantity'             => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $latest   = GoodReceipt::latest('id')->first();
            $nextId   = $latest ? $latest->id + 1 : 1;
            $grNumber = 'GR-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $receipt = GoodReceipt::create([
                'gr_number'            => $grNumber,
                't_purchase_order_id'  => $request->t_purchase_order_id,
                'm_warehouse_id'       => $request->m_warehouse_id,
                'm_pic_id'             => $request->m_pic_id,
                'm_project_id'         => $request->m_prjk_id ?? null,
                'receipt_date'         => $request->receipt_date,
                'delivery_note_number' => $request->delivery_note_number,
                'notes'                => $request->notes,
                'received_by'          => Auth::id(),
            ]);

            foreach ($request->items as $itemData) {
                $unit = 'Pcs';
                if (!empty($itemData['m_material_id'])) {
                    $material = Material::find($itemData['m_material_id']);
                    if ($material) $unit = $material->unit;
                }

                GoodReceiptItem::create([
                    't_good_receipt_id'        => $receipt->id,
                    't_purchase_order_item_id' => $itemData['t_purchase_order_item_id'] ?? null,
                    'm_material_id'            => $itemData['m_material_id'] ?? null,
                    'quantity'                 => $itemData['quantity'],
                    'unit'                     => $unit,
                ]);

                if (!empty($itemData['m_material_id'])) {
                    $stock = Stock::firstOrCreate(
                        ['m_warehouse_id' => $request->m_warehouse_id, 'm_material_id' => $itemData['m_material_id']],
                        ['current_stock'  => 0]
                    );
                    $stock->current_stock += $itemData['quantity'];
                    $stock->save();

                    Mutasi::create([
                        'm_warehouse_id' => $request->m_warehouse_id,
                        'm_material_id'  => $itemData['m_material_id'],
                        'reference_type' => GoodReceipt::class,
                        'reference_id'   => $receipt->id,
                        'type'           => 'in',
                        'quantity'       => $itemData['quantity'],
                        'balance'        => $stock->current_stock,
                        'notes'          => 'Penerimaan GR: ' . $grNumber,
                        'created_by'     => Auth::id(),
                    ]);
                }
            }

            // Update status PO
            $po = PurchaseOrder::with('items')->find($request->t_purchase_order_id);
            if ($po) {
                $isCompleted = true;
                foreach ($po->items as $poItem) {
                    $receivedQty = GoodReceiptItem::where('t_purchase_order_item_id', $poItem->id)->sum('quantity');
                    if (round($receivedQty, 2) < round($poItem->quantity, 2)) {
                        $isCompleted = false;
                        break;
                    }
                }
                $po->update(['status' => $isCompleted ? 'completed' : 'partial']);
            }

            DB::commit();
            return redirect()->route('good-receipts.show', $receipt)->with('success', 'Penerimaan Material berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function isKaryawanUser()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'isKaryawan')) {
            return $user->isKaryawan();
        }

        if (isset($user->role)) {
            return strtolower($user->role) === 'karyawan';
        }

        if (isset($user->is_karyawan)) {
            return (bool) $user->is_karyawan;
        }

        return false;
    }

    public function show(GoodReceipt $goodReceipt)
    {
        $goodReceipt->load(['purchaseOrder', 'warehouse', 'receiver', 'items.material', 'project']);
        return view('good-receipts.show', compact('goodReceipt'));
    }
}
