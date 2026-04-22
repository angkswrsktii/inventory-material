<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = StockCard::with('material')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->material_id) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $stockCards = $query->paginate(15)->withQueryString();
        $materials = Material::active()->orderBy('name')->get();

        return view('stock-cards.index', compact('stockCards', 'materials'));
    }

    public function create()
    {
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        return view('stock-cards.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'transaction_date' => 'required|date',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reference_no' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $material = Material::lockForUpdate()->findOrFail($validated['material_id']);

            if ($validated['type'] === 'out') {
                if ($material->current_stock < $validated['quantity']) {
                    throw new \Exception("Stok tidak mencukupi. Stok saat ini: {$material->current_stock} {$material->unit}");
                }
                $material->current_stock -= $validated['quantity'];
                $quantityIn = 0;
                $quantityOut = $validated['quantity'];
            } else {
                $material->current_stock += $validated['quantity'];
                $quantityIn = $validated['quantity'];
                $quantityOut = 0;
            }

            $material->save();

            StockCard::create([
                'material_id' => $validated['material_id'],
                'transaction_date' => $validated['transaction_date'],
                'type' => $validated['type'],
                'quantity_in' => $quantityIn,
                'quantity_out' => $quantityOut,
                'balance' => $material->current_stock,
                'reference_no' => $validated['reference_no'] ?? null,
                'source' => $validated['source'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('stock-cards.index')
            ->with('success', 'Transaksi stok berhasil dicatat.');
    }

    public function show(Material $material)
    {
        $stockCards = $material->stockCards()
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('stock-cards.show', compact('material', 'stockCards'));
    }
}
