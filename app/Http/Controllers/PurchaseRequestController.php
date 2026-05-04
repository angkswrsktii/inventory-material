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
    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $query = PurchaseRequest::with(['items', 'creator', 'reviewer'])
            ->orderBy('request_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('document_no', 'like', "%{$request->search}%")
                    ->orWhere('requested_by_name', 'like', "%{$request->search}%")
                    ->orWhere('department', 'like', "%{$request->search}%")
                    ->orWhere('purpose', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        // Karyawan hanya lihat PR miliknya sendiri
        if ($currentUser->isKaryawan()) {
            $query->where('created_by', $currentUser->id);
        }

        $requests = $query->paginate(10)->withQueryString();

        $stats = [
            'draft'     => PurchaseRequest::where('status', 'draft')->count(),
            'submitted' => PurchaseRequest::where('status', 'submitted')->count(),
            'approved'  => PurchaseRequest::where('status', 'approved')->count(),
            'ordered'   => PurchaseRequest::where('status', 'ordered')->count(),
        ];

        return view('purchase-requests.index', compact('requests', 'stats'));
    }

    // ── CREATE ────────────────────────────────────────────
    public function create()
    {
        $materials  = Material::where('is_active', true)->orderBy('name')->get();
        $documentNo = PurchaseRequest::generateDocumentNo();

        return view('purchase-requests.create', compact('materials', 'documentNo'));
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'request_date'               => 'required|date',
            'requested_by_name'          => 'required|string|max:100',
            'department'                 => 'nullable|string|max:100',
            'purpose'                    => 'nullable|string|max:500',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.material_name'      => 'required|string|max:255',
            'items.*.unit'               => 'required|string|max:50',
            'items.*.quantity_requested' => 'required|numeric|min:0.01',
            'items.*.estimated_price'    => 'nullable|numeric|min:0',
        ]);

        $action = $request->input('action', 'draft');

        DB::transaction(function () use ($request, $action) {
            $pr = PurchaseRequest::create([
                'document_no'       => PurchaseRequest::generateDocumentNo(),
                'request_date'      => $request->request_date,
                'requested_by_name' => $request->requested_by_name,
                'department'        => $request->department,
                'purpose'           => $request->purpose,
                'notes'             => $request->notes,
                'status'            => $action === 'submit' ? 'submitted' : 'draft',
                'created_by'        => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'material_id'         => $item['material_id'] ?: null,
                    'material_name'       => $item['material_name'],
                    'material_code'       => $item['material_code'] ?? null,
                    'unit'                => $item['unit'],
                    'specification'       => $item['specification'] ?? null,
                    'quantity_requested'  => $item['quantity_requested'],
                    'estimated_price'     => $item['estimated_price'] ?? null,
                    'item_notes'          => $item['item_notes'] ?? null,
                ]);
            }
        });

        $msg = $action === 'submit'
            ? 'Purchase Request berhasil diajukan dan menunggu review.'
            : 'Purchase Request berhasil disimpan sebagai draft.';

        return redirect()->route('purchase-requests.index')->with('success', $msg);
    }

    // ── SHOW ──────────────────────────────────────────────
    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['items.material', 'creator', 'reviewer']);
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    // ── EDIT ──────────────────────────────────────────────
    public function edit(PurchaseRequest $purchaseRequest)
    {
        if (!$purchaseRequest->canEdit()) {
            return back()->with('error', 'Purchase Request ini tidak dapat diedit.');
        }

        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $purchaseRequest->load('items');

        return view('purchase-requests.edit', compact('purchaseRequest', 'materials'));
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        if (!$purchaseRequest->canEdit()) {
            return back()->with('error', 'Purchase Request ini tidak dapat diedit.');
        }

        $request->validate([
            'request_date'               => 'required|date',
            'requested_by_name'          => 'required|string|max:100',
            'department'                 => 'nullable|string|max:100',
            'purpose'                    => 'nullable|string|max:500',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.material_name'      => 'required|string|max:255',
            'items.*.unit'               => 'required|string|max:50',
            'items.*.quantity_requested' => 'required|numeric|min:0.01',
            'items.*.estimated_price'    => 'nullable|numeric|min:0',
        ]);

        $action = $request->input('action', 'draft');

        DB::transaction(function () use ($request, $purchaseRequest, $action) {
            $purchaseRequest->update([
                'request_date'      => $request->request_date,
                'requested_by_name' => $request->requested_by_name,
                'department'        => $request->department,
                'purpose'           => $request->purpose,
                'notes'             => $request->notes,
                'status'            => $action === 'submit' ? 'submitted' : 'draft',
            ]);

            $purchaseRequest->items()->delete();

            foreach ($request->items as $item) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $purchaseRequest->id,
                    'material_id'         => $item['material_id'] ?: null,
                    'material_name'       => $item['material_name'],
                    'material_code'       => $item['material_code'] ?? null,
                    'unit'                => $item['unit'],
                    'specification'       => $item['specification'] ?? null,
                    'quantity_requested'  => $item['quantity_requested'],
                    'estimated_price'     => $item['estimated_price'] ?? null,
                    'item_notes'          => $item['item_notes'] ?? null,
                ]);
            }
        });

        $msg = $action === 'submit'
            ? 'Purchase Request berhasil diajukan dan menunggu review.'
            : 'Purchase Request berhasil diperbarui.';

        return redirect()->route('purchase-requests.show', $purchaseRequest)->with('success', $msg);
    }

    // ── SUBMIT ────────────────────────────────────────────
    public function submit(PurchaseRequest $purchaseRequest)
    {
        if (!$purchaseRequest->canSubmit()) {
            return back()->with('error', 'Purchase Request ini tidak dapat diajukan.');
        }

        $purchaseRequest->update(['status' => 'submitted']);

        return back()->with('success', 'Purchase Request berhasil diajukan untuk di-review.');
    }

    // ── APPROVE ───────────────────────────────────────────
    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$purchaseRequest->canReview()) {
            return back()->with('error', 'Purchase Request ini tidak dapat di-approve. Status saat ini: ' . $purchaseRequest->status_label);
        }

        if (!$currentUser->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui PR. Hanya Pimpinan atau Kepala Gudang yang dapat menyetujui.');
        }

        $request->validate([
            'approved_quantities'    => 'nullable|array',
            'approved_quantities.*'  => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseRequest, $currentUser) {
            if ($request->approved_quantities) {
                foreach ($request->approved_quantities as $itemId => $qty) {
                    $purchaseRequest->items()->where('id', $itemId)->update([
                        'quantity_approved' => $qty,
                    ]);
                }
            } else {
                $purchaseRequest->items()->update([
                    'quantity_approved' => DB::raw('quantity_requested'),
                ]);
            }

            $purchaseRequest->update([
                'status'      => 'approved',
                'reviewed_by' => $currentUser->id,
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Purchase Request berhasil disetujui.');
    }

    // ── REJECT ────────────────────────────────────────────
    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$purchaseRequest->canReview()) {
            return back()->with('error', 'Purchase Request ini tidak dapat ditolak. Status saat ini: ' . $purchaseRequest->status_label);
        }

        if (!$currentUser->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak PR. Hanya Pimpinan atau Kepala Gudang yang dapat menolak.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $purchaseRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => $currentUser->id,
            'reviewed_at'      => now(),
        ]);

        return back()->with('success', 'Purchase Request telah ditolak.');
    }

    // ── MARK ORDERED ──────────────────────────────────────
    public function markOrdered(PurchaseRequest $purchaseRequest)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$purchaseRequest->canMarkOrdered()) {
            return back()->with('error', 'Purchase Request ini belum disetujui.');
        }

        if (!$currentUser->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        $purchaseRequest->update(['status' => 'ordered']);

        return back()->with('success', 'Purchase Request ditandai sebagai sudah dipesan (PO).');
    }

    // ── DESTROY ───────────────────────────────────────────
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if (!in_array($purchaseRequest->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Hanya PR berstatus Draft atau Ditolak yang dapat dihapus.');
        }

        $purchaseRequest->delete();

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil dihapus.');
    }

    // ── PRINT ─────────────────────────────────────────────
    public function print(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['items.material', 'creator', 'reviewer']);
        return view('purchase-requests.print', compact('purchaseRequest'));
    }
}