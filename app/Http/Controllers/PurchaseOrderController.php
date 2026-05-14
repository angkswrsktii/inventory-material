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
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['purchaseRequest', 'items', 'creator', 'supplier'])
            ->orderBy('order_date', 'desc')
            ->orderByDesc('id');

        if ($request->search) {
            $query->where('po_number', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->paginate(10)->withQueryString();

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(Request $request)
    {
        // Pastikan with('items.material') agar nama material terbawa ke view
        $purchaseRequests = PurchaseRequest::with('items.material') 
            ->whereIn('status', ['approved'])
            ->orderByDesc('id')
            ->get();

        $selectedPR = null;
        if ($request->pr_id) {
            $selectedPR = PurchaseRequest::with('items.material')
                ->where('status', 'approved')
                ->find($request->pr_id);
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        
        $latest = PurchaseOrder::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $autoNumber = 'PO-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchase-orders.create', compact('purchaseRequests', 'selectedPR', 'autoNumber', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            't_purchase_request_id'     => 'required|exists:t_purchase_requests,id',
            'order_date'                => 'required|date',
            'm_supplier_id'             => 'required|exists:m_suppliers,id',
            'notes'                     => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.m_material_id'     => 'required|exists:m_materials,id',
            'items.*.quantity'          => 'required|numeric|min:0.01',
            'items.*.price_per_qty'     => 'required|numeric|min:0',
            'items.*.price'             => 'required|numeric|min:0', 
        ]);

        $pr = PurchaseRequest::findOrFail($request->t_purchase_request_id);
        if ($pr->status !== 'approved') {
            return back()->with('error', 'Purchase Request harus berstatus Disetujui untuk membuat PO.');
        }

        DB::transaction(function () use ($request) {
            $latest = PurchaseOrder::latest('id')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $documentNo = 'PO-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $po = PurchaseOrder::create([
                'po_number'              => $request->po_number ?? $documentNo,
                't_purchase_request_id'  => $request->t_purchase_request_id,
                'order_date'             => $request->order_date,
                'm_supplier_id'          => $request->m_supplier_id,
                'notes'                  => $request->notes,
                'status'                 => 'draft',
                'created_by'             => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                // Di sini kita bisa ambil unit dari database agar presisi
                $material = \App\Models\Material::find($item['m_material_id']);
                
                PurchaseOrderItem::create([
                    't_purchase_order_id'       => $po->id,
                    'm_material_id'             => $item['m_material_id'],
                    'unit'                      => $material ? $material->unit : 'Pcs',
                    'quantity'                  => $item['quantity'],
                    'price_per_qty'             => $item['price_per_qty'],
                    'price'                     => $item['price'], 
                ]);
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order Draft berhasil dibuat.');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Hanya PO berstatus Draft yang dapat diedit.');
        }

        $purchaseOrder->load(['items.material', 'purchaseRequest']);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Hanya PO berstatus Draft yang dapat diedit.');
        }

        $request->validate([
            'order_date'                => 'required|date',
            'm_supplier_id'             => 'required|exists:m_suppliers,id',
            'notes'                     => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.id'                => 'required|exists:t_purchase_order_items,id',
            'items.*.price_per_qty'     => 'required|numeric|min:0',
            'items.*.price'             => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update([
                'order_date'             => $request->order_date,
                'm_supplier_id'          => $request->m_supplier_id,
                'notes'                  => $request->notes,
            ]);

            foreach ($request->items as $itemData) {
                $item = PurchaseOrderItem::find($itemData['id']);
                if ($item && $item->t_purchase_order_id == $purchaseOrder->id) {
                    $item->update([
                        'price_per_qty' => $itemData['price_per_qty'],
                        'price'         => $itemData['price'],
                    ]);
                }
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseRequest.items', 'items.material', 'creator', 'supplier']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function send(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'PO ini tidak dapat dikirim.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->update(['status' => 'issued']);

            // PR ditandai completed saat PO sudah di-issue
            if ($purchaseOrder->purchaseRequest) {
                $purchaseOrder->purchaseRequest->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Purchase Order berhasil ditandai sebagai terkirim (Issued) ke supplier.');
    }

    public function cancel(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'issued'])) {
            return back()->with('error', 'PO ini tidak dapat dibatalkan.');
        }

        $request->validate(['cancel_reason' => 'required|string|min:5']);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update([
                'status' => 'cancelled',
                'notes'  => ($purchaseOrder->notes ? $purchaseOrder->notes . "\n" : '') . 'Dibatalkan: ' . $request->cancel_reason,
            ]);

            // Kembalikan PR ke approved agar bisa dibuat PO lagi jika PO ini batal
            if ($purchaseOrder->purchaseRequest) {
                $purchaseOrder->purchaseRequest->update(['status' => 'approved']);
            }
        });

        return back()->with('success', 'Purchase Order berhasil dibatalkan.');
    }

    public function print(PurchaseOrder $purchaseOrder)
    {
        // Memuat relasi yang dibutuhkan untuk halaman print agar tidak N+1 query
        $purchaseOrder->load(['purchaseRequest', 'items.material', 'creator', 'supplier']);

        // Mengembalikan view print. Asumsi file disimpan di resources/views/purchase-orders/print.blade.php
        return view('purchase-orders.print', compact('purchaseOrder'));
    }
}