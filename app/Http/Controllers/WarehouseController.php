<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%")
                    ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === '1');
        }

        $warehouses = $query->paginate(10)->withQueryString();

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:20|unique:m_warehouses,code',
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        Warehouse::create([
            ...$request->only(['code', 'name', 'location']),
            'is_active' => true,
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load('stocks');
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'code'     => 'required|string|max:20|unique:m_warehouses,code,' . $warehouse->id,
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        $warehouse->update($request->only(['code', 'name', 'location']));

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->stocks()->count() > 0) {
            return back()->with('error', 'Gudang tidak bisa dihapus karena sudah memiliki data stok.');
        }

        $warehouse->delete();

        return back()->with('success', 'Gudang berhasil dihapus.');
    }

    public function toggleActive(Warehouse $warehouse)
    {
        $warehouse->update(['is_active' => ! $warehouse->is_active]);
        $status = $warehouse->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Gudang berhasil {$status}.");
    }
}
