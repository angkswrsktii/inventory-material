<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('supplier', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            if ($request->status === 'low') {
                $query->whereColumn('current_stock', '<=', 'minimum_stock')
                      ->where('current_stock', '>', 0);
            } elseif ($request->status === 'empty') {
                $query->where('current_stock', '<=', 0);
            } elseif ($request->status === 'normal') {
                $query->whereColumn('current_stock', '>', 'minimum_stock');
            }
        }

        $materials = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Material::count(),
            'low' => Material::whereColumn('current_stock', '<=', 'minimum_stock')->where('current_stock', '>', 0)->count(),
            'empty' => Material::where('current_stock', '<=', 0)->count(),
            'normal' => Material::whereColumn('current_stock', '>', 'minimum_stock')->count(),
        ];

        return view('materials.index', compact('materials', 'stats'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:materials,code',
            'name' => 'required|string|max:255',
            'specification' => 'nullable|string|max:500',
            'unit' => 'required|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Material::create($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function show(Material $material)
    {
        $stockCards = $material->stockCards()
            ->with('withdrawalCard')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('materials.show', compact('material', 'stockCards'));
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('materials', 'code')->ignore($material->id)],
            'name' => 'required|string|max:255',
            'specification' => 'nullable|string|max:500',
            'unit' => 'required|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        if ($material->current_stock > 0) {
            return back()->with('error', 'Material tidak dapat dihapus karena masih memiliki stok.');
        }

        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }
}
