<?php

namespace App\Http\Controllers;

use App\Models\GoodIssue;
use App\Models\ProductionQc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionQcController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionQc::with(['goodIssue', 'part', 'checker'])->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('wo_number', 'like', "%{$request->search}%")
                  ->orWhereHas('goodIssue', fn ($g) => $g->where('gi_number', 'like', "%{$request->search}%"))
                  ->orWhereHas('part', fn ($p) => $p->where('part_name', 'like', "%{$request->search}%")->orWhere('part_no', 'like', "%{$request->search}%"));
            });
        }
        if ($request->date_from) {
            $query->whereDate('qc_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('qc_date', '<=', $request->date_to);
        }

        $qcs = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => ProductionQc::count(),
            'total_ok' => ProductionQc::sum('quantity_passed'),
            'total_ng' => ProductionQc::sum(DB::raw('quantity_failed + quantity_failed_retur')),
        ];

        return view('production-qc.index', compact('qcs', 'stats'));
    }

    public function create(Request $request)
    {
        $availableGIs = GoodIssue::whereNotNull('m_part_id')
            ->whereDoesntHave('qc')
            ->get();

        $goodIssue = null;
        if ($request->t_good_issue_id) {
            $goodIssue = GoodIssue::with(['part', 'items.material'])->findOrFail($request->t_good_issue_id);
        }

        $latest   = ProductionQc::latest('id')->first();
        $nextId   = $latest ? $latest->id + 1 : 1;
        $woNumber = 'WO-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('production-qc.create', compact('goodIssue', 'availableGIs', 'woNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            't_good_issue_id' => 'required|exists:t_good_issues,id',
            'qc_date'         => 'required|date',
            'quantity_passed' => 'required|numeric|min:0',
            'quantity_failed' => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        if (($request->quantity_passed + $request->quantity_failed) == 0) {
            return back()->withInput()->withErrors(['quantity_passed' => 'Total QC (OK + NG) tidak boleh 0.']);
        }

        $gi       = GoodIssue::findOrFail($request->t_good_issue_id);
        $latest   = ProductionQc::latest('id')->first();
        $nextId   = $latest ? $latest->id + 1 : 1;
        $woNumber = 'WO-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            ProductionQc::create([
                'wo_number'             => $woNumber,
                't_good_issue_id'       => $gi->id,
                'm_part_id'             => $gi->m_part_id,
                'checked_by'            => Auth::id(),
                'qc_date'               => $request->qc_date,
                'quantity_passed'       => $request->quantity_passed,
                'quantity_failed'       => $request->quantity_failed,
                'quantity_failed_retur' => 0,
                'notes'                 => $request->notes,
                'status'                => 'approved',
            ]);

            DB::commit();
            return redirect()->route('production-qc.index')->with('success', 'Work Order berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(ProductionQc $productionQc)
    {
        $productionQc->load(['goodIssue.items.material', 'part']);
        return view('production-qc.edit', compact('productionQc'));
    }

    public function update(Request $request, ProductionQc $productionQc)
    {
        $request->validate([
            'qc_date'         => 'required|date',
            'quantity_passed' => 'required|numeric|min:0',
            'quantity_failed' => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        $productionQc->update([
            'qc_date'         => $request->qc_date,
            'quantity_passed' => $request->quantity_passed,
            'quantity_failed' => $request->quantity_failed,
            'notes'           => $request->notes,
        ]);

        return redirect()->route('production-qc.index')->with('success', 'Work Order berhasil diperbarui.');
    }

    public function show(ProductionQc $productionQc)
    {
        $productionQc->load(['goodIssue.items.material', 'part', 'checker']);
        return view('production-qc.show', compact('productionQc'));
    }

    public function print(ProductionQc $productionQc)
    {
        $productionQc->load(['goodIssue.items.material', 'part', 'checker']);
        return view('production-qc.print', compact('productionQc'));
    }

    public function destroy(ProductionQc $productionQc)
    {
        $productionQc->delete();
        return back()->with('success', 'Data Work Order berhasil dihapus.');
    }
}
