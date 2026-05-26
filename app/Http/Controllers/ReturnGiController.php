<?php

namespace App\Http\Controllers;

use App\Models\ReturnGi;
use App\Models\ReturnGiItem;
use App\Models\GoodIssue;
use App\Models\ProductionQc;
use App\Models\Stock;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnGiController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnGi::with(['goodIssue', 'productionQc', 'returner'])->orderByDesc('id');

        if ($request->search) {
            $query->where('return_number', 'like', "%{$request->search}%")
                ->orWhereHas('goodIssue', fn($g) => $g->where('gi_number', 'like', "%{$request->search}%"))
                ->orWhereHas('productionQc', fn($q) => $q->where('wo_number', 'like', "%{$request->search}%"));
        }

        $returns = $query->paginate(15)->withQueryString();

        return view('return-gi.index', compact('returns'));
    }

    public function create(Request $request)
    {
        // Ambil data QC yang sudah Approved, ada Material yang diretur (NG Retur > 0), 
        // dan belum pernah dibuatkan dokumen Return sebelumnya.
        $returnedQcIds = ReturnGi::whereNotNull('t_production_qc_id')->pluck('t_production_qc_id');

        $availableQcs = ProductionQc::with(['goodIssue.pic', 'part'])
            ->where('status', 'approved')
            ->where('quantity_failed_retur', '>', 0)
            ->whereNotIn('id', $returnedQcIds)
            ->orderByDesc('id')
            ->get();

        $qc = null;
        if ($request->t_production_qc_id) {
            $qc = ProductionQc::with(['goodIssue.items.material', 'part'])->findOrFail($request->t_production_qc_id);
        }

        $latest = ReturnGi::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $returnNumber = 'RET-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('return-gi.create', compact('availableQcs', 'qc', 'returnNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            't_production_qc_id' => 'required|exists:t_production_qcs,id',
            'return_date'     => 'required|date',
            'notes'           => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.m_material_id' => 'required|exists:m_materials,id',
            'items.*.quantity'      => 'required|numeric|min:0',
        ]);

        // Pastikan total input qty > 0 agar tidak menyimpan dokumen kosong
        $totalInput = collect($request->items)->sum('quantity');
        if ($totalInput <= 0) {
            return back()->withInput()->withErrors(['items' => 'Total Qty Retur tidak boleh 0. Isi setidaknya 1 material.']);
        }

        $qc = ProductionQc::findOrFail($request->t_production_qc_id);
        $gi = $qc->goodIssue;

        $latest = ReturnGi::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $returnNumber = 'RET-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $return = ReturnGi::create([
                'return_number'      => $returnNumber,
                't_good_issue_id'    => $gi->id,
                't_production_qc_id' => $qc->id,
                'return_date'        => $request->return_date,
                'notes'              => $request->notes,
                'returned_by'        => Auth::id(),
            ]);

            $warehouseId = $gi->m_warehouse_id;

            foreach ($request->items as $itemData) {
                if ($itemData['quantity'] <= 0) continue; // Skip jika user menginput 0

                $material = \App\Models\Material::find($itemData['m_material_id']);

                ReturnGiItem::create([
                    't_return_id'   => $return->id,
                    'm_material_id' => $itemData['m_material_id'],
                    'quantity'      => $itemData['quantity'],
                    'unit'          => $material->unit ?? 'Pcs',
                ]);

                // Update Stock Material (Bukan Part)
                $stock = Stock::firstOrCreate(
                    [
                        'm_warehouse_id' => $warehouseId,
                        'm_material_id'  => $itemData['m_material_id']
                    ],
                    ['current_stock' => 0]
                );

                $stock->current_stock += $itemData['quantity'];
                $stock->save();

                // Mutasi Material (Masuk Gudang)
                Mutasi::create([
                    'm_warehouse_id' => $warehouseId,
                    'm_material_id'  => $itemData['m_material_id'],
                    'reference_type' => ReturnGi::class,
                    'reference_id'   => $return->id,
                    'type'           => 'in',
                    'quantity'       => $itemData['quantity'],
                    'balance'        => $stock->current_stock,
                    'notes'          => 'Retur NG dari WO: ' . $qc->wo_number,
                    'created_by'     => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route('return-gi.index')->with('success', 'Retur Material berhasil disimpan dan stok bertambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(ReturnGi $returnGi)
    {
        $returnGi->load(['goodIssue', 'productionQc', 'items.material', 'returner']);
        return view('return-gi.show', compact('returnGi'));
    }

    public function print(ReturnGi $returnGi)
    {
        $returnGi->load(['goodIssue', 'productionQc', 'items.material', 'returner']);
        return view('return-gi.print', compact('returnGi'));
    }
}
