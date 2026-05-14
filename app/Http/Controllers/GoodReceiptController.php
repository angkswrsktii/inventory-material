<?php

namespace App\Http\Controllers;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\Material;
use App\Models\Mutasi;
use App\Models\Pic;
use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
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
        // Ambil PO yang berstatus issued atau partial
        $purchaseOrders = PurchaseOrder::whereIn('status', ['issued', 'partial'])
            ->with(['items.material']) // load material dan part
            ->get();

        // Hitung sisa qty tiap item, dan saring PO yang sudah full received
        $purchaseOrders = $purchaseOrders->map(function ($po) {
            // Modifikasi koleksi items di dalam masing-masing PO
            $po->setRelation('items', $po->items->map(function ($item) {
                // Hitung total kuantitas dari item ini yang sudah diterima di GR sebelumnya
                $receivedQty = GoodReceiptItem::where('t_purchase_order_item_id', $item->id)->sum('quantity');
                
                // Tambahkan properti bantuan untuk dikirim ke view
                $item->remaining_quantity = $item->quantity - $receivedQty;
                
                return $item;
            })->filter(function ($item) {
                // Filter: Hanya simpan item yang sisanya masih lebih dari 0
                return $item->remaining_quantity > 0;
            })->values());

            return $po;
        })->filter(function ($po) {
            // Filter: Jangan tampilkan PO di dropdown jika semua itemnya sudah 0 (meskipun statusnya nyangkut di partial)
            return $po->items->count() > 0;
        })->values();

        $warehouses = Warehouse::where('is_active', true)->get();
        $pics = Pic::where('is_active', true)->get();
        $latest = GoodReceipt::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $autoNumber = 'GR-'.date('Ym').'-'.str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Abaikan variabel $project jika tidak didefinisikan sebelumnya, atau tambahkan jika ada
        return view('good-receipts.create', compact('purchaseOrders', 'warehouses', 'pics', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            't_purchase_order_id' => 'required|exists:t_purchase_orders,id',
            'm_warehouse_id' => 'required|exists:m_warehouses,id',
            'm_pic_id' => 'required|exists:m_pics,id',
            'receipt_date' => 'required|date',
            'delivery_note_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.m_material_id' => 'nullable|exists:m_materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Generate GR Number
            $latest = GoodReceipt::latest('id')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $grNumber = 'GR-'.date('Ym').'-'.str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $receipt = GoodReceipt::create([
                'gr_number' => $grNumber,
                't_purchase_order_id' => $request->t_purchase_order_id,
                'm_warehouse_id' => $request->m_warehouse_id,
                'm_pic_id' => $request->m_pic_id,
                'm_project_id' => $request->m_prjk_id ?? null, // Sesuaikan ini dengan form Anda
                'receipt_date' => $request->receipt_date,
                'delivery_note_number' => $request->delivery_note_number,
                'notes' => $request->notes,
                'received_by' => auth()->id(),
            ]);

            foreach ($request->items as $itemData) {
                $material = null;
                $unit = 'Pcs';
                if (! empty($itemData['m_material_id'])) {
                    $material = Material::find($itemData['m_material_id']);
                    if ($material) {
                        $unit = $material->unit;
                    }
                }

                // Save Item
                $grItem = GoodReceiptItem::create([
                    't_good_receipt_id' => $receipt->id,
                    't_purchase_order_item_id' => $itemData['t_purchase_order_item_id'] ?? null,
                    'm_material_id' => $itemData['m_material_id'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit' => $unit,
                ]);

                // Update/Create Stock
                if (! empty($itemData['m_material_id'])) {
                    $stock = Stock::firstOrCreate(
                        [
                            'm_warehouse_id' => $request->m_warehouse_id,
                            'm_material_id' => $itemData['m_material_id'],
                        ],
                        ['current_stock' => 0]
                    );

                    $stock->current_stock += $itemData['quantity'];
                    $stock->save();

                    // Save Mutasi
                    Mutasi::create([
                        'm_warehouse_id' => $request->m_warehouse_id,
                        'm_material_id' => $itemData['m_material_id'],
                        'reference_type' => GoodReceipt::class,
                        'reference_id' => $receipt->id,
                        'type' => 'in',
                        'quantity' => $itemData['quantity'],
                        'balance' => $stock->current_stock,
                        'notes' => 'Penerimaan GR: '.$grNumber,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // ==========================================
            // PENGECEKAN STATUS PO (PARTIAL / COMPLETED)
            // ==========================================
            $po = PurchaseOrder::with('items')->find($request->t_purchase_order_id);
            if ($po) {
                $isCompleted = true; // Asumsikan selesai dulu

                // Loop setiap item pada PO tersebut
                foreach ($po->items as $poItem) {
                    // Cari total item ini yang sudah masuk ke tabel GR
                    $receivedQty = GoodReceiptItem::where('t_purchase_order_item_id', $poItem->id)->sum('quantity');
                    
                    // Jika total yang diterima masih kurang dari kuantitas aslinya, berarti belum completed
                    if (round($receivedQty, 2) < round($poItem->quantity, 2)) {
                        $isCompleted = false;
                        break; // Keluar dari loop, tidak perlu ngecek item lain
                    }
                }

                // Update status PO
                $po->update([
                    'status' => $isCompleted ? 'completed' : 'partial'
                ]);
            }

            DB::commit();

            return redirect()->route('good-receipts.show', $receipt)->with('success', 'Penerimaan barang berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }
    

    public function show(GoodReceipt $goodReceipt)
    {
        $goodReceipt->load(['purchaseOrder', 'warehouse', 'receiver', 'items.material', 'project']);

        return view('good-receipts.show', compact('goodReceipt'));
    }
}
