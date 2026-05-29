<?php

namespace App\Http\Controllers;

use App\Models\GoodIssue;
use App\Models\GoodIssueItem;
use App\Models\MaterialBatch;
use App\Models\Part;
use App\Models\Stock;
use App\Models\Mutasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodIssue::with(['warehouse', 'part', 'issuer', 'receiver']);

        if ($request->search) {
            $query->where('gi_number', 'like', "%{$request->search}%")
                ->orWhere('purpose', 'like', "%{$request->search}%");
        }

        $goodIssues = $query->latest()->paginate(10);
        return view('good-issues.index', compact('goodIssues'));
    }

    public function create()
    {
        $stocks  = Stock::with(['material', 'warehouse'])->where('current_stock', '>', 0)->get();
        $parts   = Part::where('is_active', true)->get();
        $project = \App\Models\Project::get();

        // Semua batch yang masih punya sisa stok, urut FIFO (terlama duluan)
        $batches = MaterialBatch::where('remaining_quantity', '>', 0)
            ->with(['material', 'warehouse'])
            ->orderBy('receipt_date', 'asc')
            ->orderBy('load_material_number', 'asc')
            ->get()
            ->map(fn($b) => [
                'id'                   => $b->id,
                'load_material_number' => $b->load_material_number,
                'm_material_id'        => $b->m_material_id,
                'm_warehouse_id'       => $b->m_warehouse_id,
                'remaining_quantity'   => (float) $b->remaining_quantity,
                'receipt_date'         => $b->receipt_date->toDateString(),
            ]);

        if (Auth::user()->role === 'karyawan') {
            $users = User::where('id', Auth::id())->where('is_active', true)->get();
        } else {
            $users = User::where('is_active', true)->orderBy('name')->get();
        }

        $latest     = GoodIssue::latest('id')->first();
        $nextId     = $latest ? $latest->id + 1 : 1;
        $autoNumber = 'GI-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('good-issues.create', compact('stocks', 'parts', 'users', 'autoNumber', 'project', 'batches'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'karyawan') {
            $request->merge(['m_pic_id' => Auth::id()]);
        }

        $request->validate([
            'gi_number'              => 'nullable|string',
            'm_part_id'              => 'required|exists:m_parts,id',
            'm_pic_id'               => 'required|exists:m_users,id',
            'm_prjk_id'              => 'required|exists:m_project,id',
            'issue_date'             => 'required|date',
            'purpose'                => 'required|string',
            'items'                  => 'required|array|min:1',
            'items.*.m_stock_id'     => 'required|exists:m_stocks,id',
            'items.*.load_material_number' => 'nullable|string|max:50',
            'items.*.quantity'       => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Validasi stok cukup (total dan per-batch)
            foreach ($request->items as $itemData) {
                $stock = Stock::find($itemData['m_stock_id']);
                if (!$stock || $stock->current_stock < $itemData['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk item ini.");
                }

                $loadNumber = $itemData['load_material_number'] ?? null;
                if (!empty($loadNumber)) {
                    $batch = MaterialBatch::where('load_material_number', $loadNumber)
                        ->where('m_material_id', $stock->m_material_id)
                        ->where('m_warehouse_id', $stock->m_warehouse_id)
                        ->first();

                    if (!$batch) {
                        throw new \Exception("Batch '{$loadNumber}' tidak ditemukan.");
                    }
                    if ($batch->remaining_quantity < $itemData['quantity']) {
                        throw new \Exception(
                            "Stok batch '{$loadNumber}' tidak mencukupi. " .
                            "Sisa: {$batch->remaining_quantity}, diminta: {$itemData['quantity']}."
                        );
                    }
                }
            }

            $latest   = GoodIssue::latest('id')->first();
            $nextId   = $latest ? $latest->id + 1 : 1;
            $giNumber = 'GI-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $firstStock = Stock::find($request->items[array_key_first($request->items)]['m_stock_id']);

            $issue = GoodIssue::create([
                'gi_number'      => $request->gi_number ?? $giNumber,
                'm_warehouse_id' => $firstStock->m_warehouse_id,
                'm_part_id'      => $request->m_part_id,
                'm_pic_id'       => $request->m_pic_id,
                'm_project_id'   => $request->m_prjk_id,
                'issue_date'     => $request->issue_date,
                'purpose'        => $request->purpose,
                'issued_by'      => Auth::id(),
            ]);

            foreach ($request->items as $itemData) {
                $stock      = Stock::find($itemData['m_stock_id']);
                $loadNumber = $itemData['load_material_number'] ?? null;

                GoodIssueItem::create([
                    't_good_issue_id'      => $issue->id,
                    'm_material_id'        => $stock->m_material_id,
                    'load_material_number' => $loadNumber,
                    'quantity'             => $itemData['quantity'],
                    'unit'                 => $stock->material->unit ?? 'Pcs',
                ]);

                // Kurangi sisa batch
                if (!empty($loadNumber)) {
                    $batch = MaterialBatch::where('load_material_number', $loadNumber)
                        ->where('m_material_id', $stock->m_material_id)
                        ->where('m_warehouse_id', $stock->m_warehouse_id)
                        ->first();

                    if ($batch) {
                        $batch->remaining_quantity -= $itemData['quantity'];
                        $batch->save();
                    }
                }

                $stock->current_stock -= $itemData['quantity'];
                $stock->save();

                Mutasi::create([
                    'm_warehouse_id' => $stock->m_warehouse_id,
                    'm_material_id'  => $stock->m_material_id,
                    'reference_type' => GoodIssue::class,
                    'reference_id'   => $issue->id,
                    'type'           => 'out',
                    'quantity'       => $itemData['quantity'],
                    'balance'        => $stock->current_stock,
                    'notes'          => 'Pengeluaran GI: ' . $giNumber . ' untuk Produksi' . ($loadNumber ? ' | Batch: ' . $loadNumber : ''),
                    'created_by'     => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route('good-issues.show', $issue)->with('success', 'Pengeluaran Material berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(GoodIssue $goodIssue)
    {
        $goodIssue->load(['warehouse', 'part', 'pic', 'issuer', 'items.material', 'project']);
        return view('good-issues.show', compact('goodIssue'));
    }

    public function print(GoodIssue $goodIssue)
    {
        $goodIssue->load(['warehouse', 'part', 'pic', 'issuer', 'items.material', 'project']);
        return view('good-issues.print', compact('goodIssue'));
    }
}
