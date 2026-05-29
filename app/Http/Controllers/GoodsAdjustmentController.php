<?php

namespace App\Http\Controllers;

use App\Models\GoodIssueItem;
use App\Models\Material;
use App\Models\MaterialBatch;
use App\Models\Mutasi;
use App\Models\Part;
use App\Models\Project;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $projects        = Project::orderBy('id')->get();
        $activeProjectId = $request->project_id ?? ($projects->first()->id ?? null);

        $stocks = Stock::with(['material.supplier', 'warehouse'])
            ->whereNotNull('m_material_id')
            ->whereHas('material', function ($q) use ($activeProjectId) {
                $q->where('project_id', $activeProjectId)
                  ->where('is_active', true);
            })
            ->get();

        foreach ($stocks as $stock) {
            $material = $stock->material;

            $part   = null;
            $giItem = GoodIssueItem::with('goodIssue.part.customer')
                ->where('m_material_id', $material->id)
                ->whereHas('goodIssue', fn ($q) => $q->whereNotNull('m_part_id'))
                ->latest('id')
                ->first();

            if ($giItem && $giItem->goodIssue && $giItem->goodIssue->part) {
                $part = $giItem->goodIssue->part;
            }

            $bq         = (float) ($material->bq ?? 0);
            $cutPerDay  = (float) ($material->cut_per_day ?? 0.1);
            if ($cutPerDay <= 0) $cutPerDay = 0.1;

            $stockQty = (float) $stock->current_stock;
            $alokasi  = $stockQty * $bq;
            $lifetime = $stockQty / $cutPerDay;
            $minStock = 10 * $cutPerDay;
            $maxStock = 2 * $minStock;

            if ($stockQty > $maxStock)                  $status = 'Over';
            elseif ($stockQty < $minStock)              $status = 'Danger';
            elseif (abs($stockQty - $minStock) < 0.01)  $status = 'Warning';
            else                                         $status = 'Aman';

            $stock->calc = (object) [
                'material_id' => $material->id,
                'spesifikasi' => $material->name,
                'dimensi'     => $material->specification ?? '-',
                'supplier'    => $material->supplier->name ?? '-',
                'warehouse'   => $stock->warehouse->name ?? '-',
                'part_name'   => $part ? $part->part_name : '-',
                'customer'    => ($part && $part->customer) ? $part->customer->name : '-',
                'bq'          => $bq,
                'cut_per_day' => $cutPerDay,
                'alokasi'     => $alokasi,
                'lifetime'    => $lifetime,
                'min_stock'   => $minStock,
                'max_stock'   => $maxStock,
                'status'      => $status,
                'satuan'      => $material->unit ?? 'Pcs',
            ];
        }

        return view('goods-adjustment.index', compact('projects', 'activeProjectId', 'stocks'));
    }

    public function updateMaterialData(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:m_materials,id',
            'bq'          => 'required|numeric|min:0',
            'cut_per_day' => 'required|numeric|min:0.01',
        ]);

        $material = Material::findOrFail($request->material_id);
        $material->update([
            'bq'          => $request->bq,
            'cut_per_day' => $request->cut_per_day,
        ]);

        return response()->json(['success' => true, 'message' => 'Material updated successfully']);
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $materials  = Material::with('supplier')->where('is_active', true)->orderBy('name')->get();

        // Semua batch tersedia untuk dropdown "out", urut FIFO
        $batches = MaterialBatch::where('remaining_quantity', '>', 0)
            ->orderBy('receipt_date', 'asc')
            ->orderBy('load_material_number', 'asc')
            ->get()
            ->map(fn ($b) => [
                'load_material_number' => $b->load_material_number,
                'm_material_id'        => $b->m_material_id,
                'm_warehouse_id'       => $b->m_warehouse_id,
                'remaining_quantity'   => (float) $b->remaining_quantity,
            ]);

        return view('goods-adjustment.create', compact('warehouses', 'materials', 'batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'm_warehouse_id'       => 'required|exists:m_warehouses,id',
            'm_material_id'        => 'required|exists:m_materials,id',
            'type'                 => 'required|in:in,out',
            'quantity'             => 'required|numeric|min:0.01',
            'notes'                => 'required|string',
            'load_material_number' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $stock = Stock::firstOrCreate(
                [
                    'm_warehouse_id' => $request->m_warehouse_id,
                    'm_material_id'  => $request->m_material_id,
                ],
                ['current_stock' => 0]
            );

            $loadNumber = $request->load_material_number ?? null;

            if ($request->type === 'in') {
                $stock->current_stock += $request->quantity;
                $stock->save();

                // Catat atau tambah batch jika load_material_number diisi
                if (!empty($loadNumber)) {
                    $batch = MaterialBatch::where('load_material_number', $loadNumber)
                        ->where('m_material_id', $request->m_material_id)
                        ->where('m_warehouse_id', $request->m_warehouse_id)
                        ->first();

                    if ($batch) {
                        $batch->initial_quantity   += $request->quantity;
                        $batch->remaining_quantity += $request->quantity;
                        $batch->save();
                    } else {
                        MaterialBatch::create([
                            'load_material_number'   => $loadNumber,
                            'm_material_id'          => $request->m_material_id,
                            'm_warehouse_id'         => $request->m_warehouse_id,
                            't_good_receipt_item_id' => null,
                            'initial_quantity'       => $request->quantity,
                            'remaining_quantity'     => $request->quantity,
                            'receipt_date'           => now()->toDateString(),
                        ]);
                    }
                }
            } else {
                if ($stock->current_stock < $request->quantity) {
                    return back()->with('error', 'Stok tidak mencukupi untuk dikeluarkan.')->withInput();
                }

                // Validasi & kurangi batch jika dipilih
                if (!empty($loadNumber)) {
                    $batch = MaterialBatch::where('load_material_number', $loadNumber)
                        ->where('m_material_id', $request->m_material_id)
                        ->where('m_warehouse_id', $request->m_warehouse_id)
                        ->first();

                    if (!$batch) {
                        return back()->with('error', "Batch '{$loadNumber}' tidak ditemukan.")->withInput();
                    }
                    if ($batch->remaining_quantity < $request->quantity) {
                        return back()->with('error',
                            "Stok batch '{$loadNumber}' tidak mencukupi. Sisa: {$batch->remaining_quantity}."
                        )->withInput();
                    }

                    $batch->remaining_quantity -= $request->quantity;
                    $batch->save();
                }

                $stock->current_stock -= $request->quantity;
                $stock->save();
            }

            Mutasi::create([
                'm_warehouse_id' => $request->m_warehouse_id,
                'm_material_id'  => $request->m_material_id,
                'reference_type' => Material::class,
                'reference_id'   => $request->m_material_id,
                'type'           => $request->type,
                'quantity'       => $request->quantity,
                'balance'        => $stock->current_stock,
                'notes'          => 'ADJUSTMENT: ' . $request->notes . ($loadNumber ? ' | Batch: ' . $loadNumber : ''),
                'created_by'     => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('goods-adjustment.index')->with('success', 'Goods Adjustment berhasil disimpan dan mutasi tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

}
