<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::with('creator')->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === '1');
        }

        $suppliers = $query->paginate(10)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:50',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string',
            'npwp'              => 'nullable|string|max:30',
            'bank_name'         => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        Supplier::create([
            ...$request->only([
                'name', 'contact_person', 'phone', 'email', 'address',
                'npwp', 'bank_name', 'bank_account', 'bank_account_name', 'notes',
            ]),
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => fn($q) => $q->latest()->take(10)]);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:50',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string',
            'npwp'              => 'nullable|string|max:30',
            'bank_name'         => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        $supplier->update($request->only([
            'name', 'contact_person', 'phone', 'email', 'address',
            'npwp', 'bank_name', 'bank_account', 'bank_account_name', 'notes',
        ]));

        return redirect()->route('suppliers.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Supplier tidak bisa dihapus karena sudah digunakan di Purchase Order.');
        }

        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }

    public function toggleActive(Supplier $supplier)
    {
        $supplier->update(['is_active' => !$supplier->is_active]);
        $status = $supplier->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Supplier berhasil {$status}.");
    }
}