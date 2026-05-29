<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('supplier');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhereHas('supplier', function($sq) use ($request) {
                      $sq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $materials = $query->latest()->paginate(10)->withQueryString();

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $projects  = Project::orderBy('name')->get();
        return view('materials.create', compact('suppliers', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'm_supplier_id'    => 'nullable|exists:m_suppliers,id',
            'project_id'       => 'nullable|exists:m_project,id',
            'code'             => 'required|string|max:50|unique:m_materials,code',
            'name'             => 'required|string|max:255',
            'specification'    => 'nullable|string|max:500',
            'unit'             => 'required|string|max:50',
            'panjang_material' => 'nullable|numeric|min:0',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Material::create($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function show(Material $material)
    {
        $material->load('supplier', 'stocks.warehouse');
        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $projects  = Project::orderBy('name')->get(); // FIX: sebelumnya tidak di-pass ke view
        return view('materials.edit', compact('material', 'suppliers', 'projects'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'm_supplier_id'    => 'nullable|exists:m_suppliers,id',
            'project_id'       => 'nullable|exists:m_project,id', // FIX: sebelumnya tidak ada
            'code'             => ['required', 'string', 'max:50', Rule::unique('m_materials', 'code')->ignore($material->id)],
            'name'             => 'required|string|max:255',
            'specification'    => 'nullable|string|max:500',
            'unit'             => 'required|string|max:50',
            'panjang_material' => 'nullable|numeric|min:0',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $hasStock = $material->stocks()->where('current_stock', '>', 0)->exists();
        if ($hasStock) {
            return back()->with('error', 'Material tidak dapat dihapus karena masih memiliki stok di gudang.');
        }

        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }
}