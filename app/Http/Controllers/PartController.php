<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = Part::with('customer');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('part_name', 'like', "%{$request->search}%")
                  ->orWhere('part_no', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($sq) use ($request) {
                      $sq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $parts = $query->latest()->paginate(10)->withQueryString();

        return view('parts.index', compact('parts'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        return view('parts.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'm_customer_id' => 'nullable|exists:m_customers,id',
            'part_no' => 'required|string|max:50|unique:m_parts,part_no',
            'part_name' => 'required|string|max:255',
            'panjang_part' => 'nullable|numeric|min:0',
            'bq' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Part::create($validated);

        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil ditambahkan.');
    }

    public function show(Part $part)
    {
        $part->load('customer', 'stocks.warehouse');
        return view('parts.show', compact('part'));
    }

    public function edit(Part $part)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        return view('parts.edit', compact('part', 'customers'));
    }

    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'm_customer_id' => 'nullable|exists:m_customers,id',
            'part_no' => ['required', 'string', 'max:50', Rule::unique('m_parts', 'part_no')->ignore($part->id)],
            'part_name' => 'required|string|max:255',
            'panjang_part' => 'nullable|numeric|min:0',
            'bq' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $part->update($validated);

        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil diperbarui.');
    }

    public function destroy(Part $part)
    {
        // Pengecekan apakah masih ada stock
        $hasStock = $part->stocks()->where('current_stock', '>', 0)->exists();
        if ($hasStock) {
            return back()->with('error', 'Part tidak dapat dihapus karena masih memiliki stok di gudang.');
        }

        $part->delete();

        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil dihapus.');
    }
}
