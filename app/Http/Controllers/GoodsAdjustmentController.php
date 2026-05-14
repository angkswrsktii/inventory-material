<?php

namespace App\Http\Controllers;

use App\Models\GoodIssueItem;
use App\Models\Mutasi;
use App\Models\Stock;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\Project;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('id')->get();
        $activeProjectId = $request->project_id ?? ($projects->first()->id ?? null);

        $stocks = Stock::with(['material.supplier', 'warehouse'])
            ->whereNotNull('m_material_id')
            ->whereHas('material', function($q) use ($activeProjectId) {
                $q->where('project_id', $activeProjectId);
            })
            ->get();

        foreach ($stocks as $stock) {
            $material = $stock->material;

            // Lacak Part & Customer (Hanya untuk keperluan display UI)
            $part = null;
            $giItem = GoodIssueItem::with('goodIssue.part.customer')
                ->where('m_material_id', $material->id)
                ->whereHas('goodIssue', function($q) {
                    $q->whereNotNull('m_part_id');
                })
                ->latest('id')
                ->first();

            if ($giItem && $giItem->goodIssue && $giItem->goodIssue->part) {
                $part = $giItem->goodIssue->part;
            }

            // MENGAMBIL NILAI LANGSUNG DARI TABEL MATERIAL
            $bq = (float) ($material->bq ?? 0);
            $cutPerDay = (float) ($material->cut_per_day ?? 0.1); 
            if ($cutPerDay <= 0) $cutPerDay = 0.1; // Cegah division by zero

            $stockQty = (float) $stock->current_stock;

            // Kalkulasi Awal
            $alokasi  = $stockQty * $bq;
            $lifetime = $stockQty / $cutPerDay;
            $minStock = 10 * $cutPerDay;
            $maxStock = 2 * $minStock;

            // Status Awal
            if ($stockQty > $maxStock) {
                $status = 'Over';
            } elseif ($stockQty < $minStock) {
                $status = 'Danger';
            } elseif (abs($stockQty - $minStock) < 0.01) {
                $status = 'Warning';
            } else {
                $status = 'Aman';
            }

            $stock->calc = (object)[
                'material_id' => $material->id, // ID Material sangat penting untuk AJAX
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
                'satuan'      => $material->unit ?? 'Pcs'
            ];
        }

        return view('goods-adjustment.index', compact('projects', 'activeProjectId', 'stocks'));
    }

    // FUNGSI BARU UNTUK MENYIMPAN HASIL EDIT DARI GRID (AJAX)
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

        return response()->json([
            'success' => true, 
            'message' => 'Material updated successfully'
        ]);
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $materials = Material::where('is_active', true)->orderBy('name')->get();

        return view('goods-adjustment.create', compact('warehouses', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'm_warehouse_id' => 'required|exists:m_warehouses,id',
            'm_material_id'  => 'required|exists:m_materials,id',
            'type'           => 'required|in:in,out', // in = Receipt, out = Issue
            'quantity'       => 'required|numeric|min:0.01',
            'notes'          => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Tabel Stock
            $stock = Stock::firstOrCreate(
                [
                    'm_warehouse_id' => $request->m_warehouse_id,
                    'm_material_id'  => $request->m_material_id
                ],
                ['current_stock' => 0]
            );

            if ($request->type === 'in') {
                $stock->current_stock += $request->quantity;
            } else {
                if ($stock->current_stock < $request->quantity) {
                    return back()->with('error', 'Stok tidak mencukupi untuk dikeluarkan.')->withInput();
                }
                $stock->current_stock -= $request->quantity;
            }
            $stock->save();

            // 2. Insert ke Tabel Mutasi
            Mutasi::create([
                'm_warehouse_id' => $request->m_warehouse_id,
                'm_material_id'  => $request->m_material_id,
                'reference_type' => Material::class, // Menggunakan Class Material sbg Ref penanda
                'reference_id'   => $request->m_material_id,
                'type'           => $request->type, // IN atau OUT
                'quantity'       => $request->quantity,
                'balance'        => $stock->current_stock,
                'notes'          => 'ADJUSTMENT: ' . $request->notes,
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