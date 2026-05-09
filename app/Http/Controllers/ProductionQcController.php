<?php

namespace App\Http\Controllers;

use App\Models\ProductionQc;
use App\Models\WithdrawalCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionQcController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionQc::with(['withdrawalCard', 'creator'])->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('document_no', 'like', "%{$request->search}%")
                  ->orWhereHas('withdrawalCard', fn($w) =>
                        $w->where('document_no', 'like', "%{$request->search}%")
                          ->orWhere('part_name', 'like', "%{$request->search}%")
                  );
            });
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->gedung) $query->where('gedung', $request->gedung);
        if ($request->date_from) $query->whereDate('qc_date', '>=', $request->date_from);
        if ($request->date_to)   $query->whereDate('qc_date', '<=', $request->date_to);

        $qcs   = $query->paginate(15)->withQueryString();
        $stats = [
            'draft'    => ProductionQc::where('status', 'draft')->count(),
            'approved' => ProductionQc::where('status', 'approved')->count(),
            'rejected' => ProductionQc::where('status', 'rejected')->count(),
            'total_ng' => ProductionQc::where('status', 'approved')->sum('qty_ng'),
        ];

        return view('production-qc.index', compact('qcs', 'stats'));
    }

    public function create(Request $request)
    {
        // Harus dari Kartu Pengambilan
        $withdrawalCard = null;
        if ($request->withdrawal_id) {
            $withdrawalCard = WithdrawalCard::with('items.material')
                ->findOrFail($request->withdrawal_id);

            // Cek apakah sudah ada QC untuk WD ini
            if (ProductionQc::where('withdrawal_card_id', $withdrawalCard->id)
                            ->whereIn('status', ['draft','approved'])->exists()) {
                return redirect()->route('withdrawal-cards.show', $withdrawalCard)
                    ->with('error', 'Kartu Pengambilan ini sudah memiliki QC.');
            }
        }

        $gedungList = ['Gedung 1', 'Gedung 2', 'Gedung 3'];
        $documentNo = ProductionQc::generateDocumentNo();

        return view('production-qc.create', compact('withdrawalCard', 'gedungList', 'documentNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'withdrawal_card_id' => 'required|exists:withdrawal_cards,id',
            'qc_date'            => 'required|date',
            'gedung'             => 'nullable|string|max:50',
            'qty_produksi'       => 'required|numeric|min:0',
            'qty_sfg'            => 'required|numeric|min:0',
            'qty_ng'             => 'required|numeric|min:0',
            'ng_notes'           => 'nullable|string',
            'notes'              => 'nullable|string',
        ]);

        if (($request->qty_sfg + $request->qty_ng) > $request->qty_produksi) {
            return back()->withInput()
                ->withErrors(['qty_sfg' => 'Total SFG + NG tidak boleh melebihi Qty Produksi.']);
        }

        ProductionQc::create([
            'document_no'        => ProductionQc::generateDocumentNo(),
            'qc_date'            => $request->qc_date,
            'withdrawal_card_id' => $request->withdrawal_card_id,
            'gedung'             => $request->gedung,
            'qty_produksi'       => $request->qty_produksi,
            'qty_sfg'            => $request->qty_sfg,
            'qty_ng'             => $request->qty_ng,
            'ng_notes'           => $request->ng_notes,
            'notes'              => $request->notes,
            'status'             => 'draft',
            'created_by'         => Auth::id(),
        ]);

        return redirect()->route('production-qc.index')
            ->with('success', 'Data Quality Control berhasil disimpan dan menunggu persetujuan.');
    }

    public function show(ProductionQc $productionQc)
    {
        $productionQc->load(['withdrawalCard.items.material', 'creator', 'approver']);
        return view('production-qc.show', compact('productionQc'));
    }

    public function approve(ProductionQc $productionQc)
    {
        if ($productionQc->status !== 'draft') {
            return back()->with('error', 'Quality Control ini sudah diproses.');
        }

        $productionQc->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Quality Control disetujui.');
    }

    public function reject(Request $request, ProductionQc $productionQc)
    {
        if ($productionQc->status !== 'draft') {
            return back()->with('error', 'Quality Control ini sudah diproses.');
        }

        $productionQc->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes'       => ($productionQc->notes ? $productionQc->notes . "\n" : '')
                           . 'Ditolak: ' . ($request->reject_reason ?? '-'),
        ]);

        return back()->with('success', 'Quality Control berhasil ditolak.');
    }

    public function destroy(ProductionQc $productionQc)
    {
        if ($productionQc->status === 'approved') {
            return back()->with('error', 'Quality Control yang sudah disetujui tidak dapat dihapus.');
        }
        $productionQc->delete();
        return back()->with('success', 'Data Quality Control berhasil dihapus.');
    }
}