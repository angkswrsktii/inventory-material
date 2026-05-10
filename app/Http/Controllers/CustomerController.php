<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('creator')->orderByDesc('id');

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

        $customers = $query->paginate(10)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'npwp'           => 'nullable|string|max:30',
            'notes'          => 'nullable|string',
        ]);

        Customer::create([
            ...$request->only(['name', 'contact_person', 'phone', 'email', 'address', 'npwp', 'notes']),
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'npwp'           => 'nullable|string|max:30',
            'notes'          => 'nullable|string',
        ]);

        $customer->update($request->only(['name', 'contact_person', 'phone', 'email', 'address', 'npwp', 'notes']));

        return redirect()->route('customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer berhasil dihapus.');
    }

    public function toggleActive(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        $status = $customer->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Customer berhasil {$status}.");
    }
}
