<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $query = PurchaseRequest::with(['items', 'requester', 'approver'])
            ->orderBy('request_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->search) {
            $query->where('pr_number', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($currentUser->isKaryawan()) {
            $query->where('requester_id', $currentUser->id);
        }

        $purchaseRequests = $query->paginate(10)->withQueryString();

        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    public function create()
    {
        $materials  = Material::where('is_active', true)->orderBy('name')->get();
        $latest = PurchaseRequest::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $autoNumber = 'PR-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchase-requests.create', compact('materials', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_date'               => 'required|date',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.m_material_id'      => 'required|exists:m_materials,id',
            'items.*.quantity'           => 'required|numeric|min:0.01',
            'items.*.purpose'            => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $latest = PurchaseRequest::latest('id')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $documentNo = 'PR-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $pr = PurchaseRequest::create([
                'pr_number'         => $request->pr_number ?? $documentNo,
                'request_date'      => $request->request_date,
                'notes'             => $request->notes,
                'status'            => 'draft', // Diubah menjadi draft
                'requester_id'      => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $material = Material::find($item['m_material_id']);
                PurchaseRequestItem::create([
                    't_purchase_request_id' => $pr->id,
                    'm_material_id'         => $item['m_material_id'],
                    'unit'                  => $material ? $material->unit : 'Pcs',
                    'quantity'              => $item['quantity'],
                    'notes'                 => $item['purpose'],
                ]);
            }
        });

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase Request berhasil disimpan sebagai Draft.');
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        // Hanya Draft yang bisa diedit
        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Hanya PR berstatus Draft yang dapat diedit. Kembalikan ke draft terlebih dahulu.');
        }

        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $purchaseRequest->load('items');

        return view('purchase-requests.edit', compact('purchaseRequest', 'materials'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Hanya Draft yang bisa diupdate
        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Hanya PR berstatus Draft yang dapat diedit.');
        }

        $request->validate([
            'request_date'               => 'required|date',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.m_material_id'      => 'required|exists:m_materials,id',
            'items.*.quantity'           => 'required|numeric|min:0.01',
            'items.*.purpose'            => 'required|string',
        ]);

        DB::transaction(function () use ($request, $purchaseRequest) {
            $purchaseRequest->update([
                'request_date' => $request->request_date,
                'notes'        => $request->notes,
            ]);

            $purchaseRequest->items()->delete();

            foreach ($request->items as $item) {
                $material = Material::find($item['m_material_id']);
                PurchaseRequestItem::create([
                    't_purchase_request_id' => $purchaseRequest->id,
                    'm_material_id'         => $item['m_material_id'],
                    'unit'                  => $material ? $material->unit : 'Pcs',
                    'quantity'              => $item['quantity'],
                    'notes'                 => $item['purpose'],
                ]);
            }
        });

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['items.material', 'requester', 'approver']);
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    public function submit(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Hanya draft yang dapat diajukan.');
        }

        $purchaseRequest->update(['status' => 'pending']);
        return back()->with('success', 'Purchase Request berhasil diajukan untuk di-review.');
    }

    // Fungsi baru untuk mengembalikan Pending ke Draft
    public function revertToDraft(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Hanya PR berstatus Pending yang bisa dikembalikan ke Draft.');
        }

        $purchaseRequest->update(['status' => 'draft']);
        return back()->with('success', 'PR berhasil dikembalikan ke Draft dan siap untuk diedit.');
    }

    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Purchase Request tidak dapat di-approve.');
        }

        if (!$currentUser->canApprovePR()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui PR.');
        }

        $purchaseRequest->update([
            'status'      => 'approved',
            'approved_by' => $currentUser->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Purchase Request berhasil disetujui.');
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Purchase Request tidak dapat ditolak.');
        }

        if (!$currentUser->canApprovePR()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak PR.');
        }

        $purchaseRequest->update([
            'status'           => 'rejected',
            'approved_by'      => $currentUser->id,
            'approved_at'      => now(),
        ]);

        return back()->with('success', 'Purchase Request telah ditolak.');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if (!in_array($purchaseRequest->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Hanya PR berstatus Draft atau Ditolak yang dapat dihapus.');
        }

        $purchaseRequest->delete();

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil dihapus.');
    }

    public function print(PurchaseRequest $purchaseRequest)
    {
        // Melakukan Eager Loading untuk relasi yang dibutuhkan pada halaman print.
        // Ini menghindari masalah N+1 Query dan sama seperti yang Anda gunakan di method show().
        $purchaseRequest->load(['items.material', 'requester', 'approver']);

        // Mengembalikan file view blade print Anda.
        // Asumsinya file html yang Anda bagikan disimpan di resources/views/purchase-requests/print.blade.php
        return view('purchase-requests.print', compact('purchaseRequest'));
    }
}