<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['purchaseRequest', 'items', 'creator'])
            ->orderBy('order_date', 'desc')
            ->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('document_no', 'like', "%{$request->search}%")
                  ->orWhere('supplier_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(10)->withQueryString();

        $stats = [
            'draft'    => PurchaseOrder::where('status', 'draft')->count(),
            'sent'     => PurchaseOrder::where('status', 'sent')->count(),
            'partial'  => PurchaseOrder::where('status', 'partial')->count(),
            'received' => PurchaseOrder::where('status', 'received')->count(),
        ];

        return view('purchase-orders.index', compact('orders', 'stats'));
    }

    // ── CREATE ────────────────────────────────────────────
    // PO hanya bisa dibuat dari PR yang sudah approved
    public function create(Request $request)
    {
        // Load PR yang sudah disetujui (approved atau ordered)
        $approvedPRs = PurchaseRequest::with('items')
            ->whereIn('status', ['approved', 'ordered'])
            ->orderByDesc('id')
            ->get();

        // Kalau ada pr_id dari query string, preload PR tersebut
        $selectedPR = null;
        if ($request->pr_id) {
            $selectedPR = PurchaseRequest::with('items.material')
                ->whereIn('status', ['approved', 'ordered'])
                ->find($request->pr_id);
        }

        $suppliers = Supplier::active()->orderBy('name')->get();
        $documentNo = PurchaseOrder::generateDocumentNo();
        return view('purchase-orders.create', compact('approvedPRs', 'selectedPR', 'documentNo', 'suppliers'));
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'purchase_request_id'       => 'required|exists:purchase_requests,id',
            'order_date'                => 'required|date',
            'expected_date'             => 'nullable|date|after_or_equal:order_date',
            'supplier_id'               => 'required|exists:suppliers,id',
            'supplier_contact'          => 'nullable|string|max:255',
            'delivery_address'          => 'nullable|string',
            'payment_terms'             => 'nullable|string|max:255',
            'notes'                     => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.material_name'     => 'required|string|max:255',
            'items.*.unit'              => 'required|string|max:50',
            'items.*.quantity_ordered'  => 'required|numeric|min:0.01',
            'items.*.unit_price'        => 'nullable|numeric|min:0',
        ]);

        $pr = PurchaseRequest::findOrFail($request->purchase_request_id);

        if (!in_array($pr->status, ['approved', 'ordered'])) {
            return back()->with('error', 'Purchase Request harus berstatus Disetujui untuk membuat PO.');
        }

        DB::transaction(function () use ($request, $pr) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += ($item['unit_price'] ?? 0) * $item['quantity_ordered'];
            }

            $po = PurchaseOrder::create([
                'document_no'          => PurchaseOrder::generateDocumentNo(),
                'purchase_request_id'  => $pr->id,
                'order_date'           => $request->order_date,
                'expected_date'        => $request->expected_date,
                'supplier_id'          => $request->supplier_id,
                'supplier_name'        => Supplier::find($request->supplier_id)?->name,
                'supplier_contact'     => $request->supplier_contact,
                'delivery_address'     => $request->delivery_address,
                'payment_terms'        => $request->payment_terms,
                'notes'                => $request->notes,
                'status'               => 'draft',
                'total_amount'         => $total,
                'created_by'           => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id'         => $po->id,
                    'purchase_request_item_id'  => $item['purchase_request_item_id'] ?? null,
                    'material_id'               => $item['material_id'] ?? null,
                    'material_name'             => $item['material_name'],
                    'material_code'             => $item['material_code'] ?? null,
                    'unit'                      => $item['unit'],
                    'specification'             => $item['specification'] ?? null,
                    'quantity_ordered'          => $item['quantity_ordered'],
                    'quantity_received'         => 0,
                    'unit_price'                => $item['unit_price'] ?? null,
                    'total_price'               => ($item['unit_price'] ?? 0) * $item['quantity_ordered'],
                    'item_notes'                => $item['item_notes'] ?? null,
                ]);
            }

            // Update status PR menjadi ordered
            $pr->update(['status' => 'ordered']);
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibuat. Status PR otomatis berubah menjadi "Sudah PO".');
    }

    // ── SHOW ──────────────────────────────────────────────
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseRequest.items', 'items.material', 'creator', 'approver']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    // ── SEND (kirim ke supplier) ───────────────────────────
    public function send(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canSend()) {
            return back()->with('error', 'PO ini tidak dapat dikirim.');
        }

        $purchaseOrder->update([
            'status'      => 'sent',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Purchase Order berhasil ditandai sebagai terkirim ke supplier.');
    }

    // ── CANCEL ────────────────────────────────────────────
    public function cancel(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canCancel()) {
            return back()->with('error', 'PO ini tidak dapat dibatalkan.');
        }

        $request->validate(['cancel_reason' => 'required|string|min:5']);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update([
                'status' => 'cancelled',
                'notes'  => ($purchaseOrder->notes ? $purchaseOrder->notes . "\n" : '') . 'Dibatalkan: ' . $request->cancel_reason,
            ]);

            // Kembalikan status PR ke approved agar bisa dibuat PO baru
            $purchaseOrder->purchaseRequest->update(['status' => 'approved']);
        });

        return back()->with('success', 'Purchase Order berhasil dibatalkan. Status PR dikembalikan ke Disetujui.');
    }

    // ── RECEIVE (catat penerimaan barang) ─────────────────
    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['sent', 'partial'])) {
            return back()->with('error', 'PO ini tidak dapat dicatat penerimaannya.');
        }

        $request->validate([
            'quantities'   => 'required|array',
            'quantities.*' => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $qtyBaru = $request->quantities[$item->id] ?? 0;
                if ($qtyBaru > 0) {
                    $item->update([
                        'quantity_received' => min(
                            $item->quantity_ordered,
                            ($item->quantity_received ?? 0) + $qtyBaru
                        ),
                    ]);
                }
            }

            $purchaseOrder->load('items');

            if ($purchaseOrder->isFullyReceived()) {
                $purchaseOrder->update(['status' => 'received']);
            } else {
                $anyReceived = $purchaseOrder->items->some(fn($i) => ($i->quantity_received ?? 0) > 0);
                if ($anyReceived) {
                    $purchaseOrder->update(['status' => 'partial']);
                }
            }
        });

        // Kumpulkan data untuk redirect ke input stok
        $purchaseOrder->load(['items.material', 'supplier']);
        $firstItem = $purchaseOrder->items->first();

        return redirect()->route('stock-cards.create', [
            'from_po'       => $purchaseOrder->document_no,
            'supplier_name' => $purchaseOrder->supplier?->name ?? $purchaseOrder->supplier_name,
            'material_id'   => $firstItem?->material_id,
            'quantity'      => $request->quantities[$firstItem?->id] ?? null,
            'po_items'      => json_encode(
                $purchaseOrder->items->map(fn($i) => [
                    'material_id' => $i->material_id,
                    'qty'         => $request->quantities[$i->id] ?? 0,
                ])->filter(fn($i) => $i['qty'] > 0)->values()
            ),
        ])->with('success', 'Penerimaan barang berhasil dicatat. Silakan lengkapi kartu stok.');

    }

    // ── PRINT ─────────────────────────────────────────────
    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseRequest', 'items.material', 'creator', 'approver']);
        return view('purchase-orders.print', compact('purchaseOrder'));
    }
}