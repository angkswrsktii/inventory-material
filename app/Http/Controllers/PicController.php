<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function index(Request $request)
    {
        $query = Pic::query()->orderByDesc('id');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('position', 'like', "%{$request->search}%");
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === '1');
        }

        $pics = $query->paginate(10)->withQueryString();
        return view('pics.index', compact('pics'));
    }

    public function create()
    {
        return view('pics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        Pic::create([
            'name'      => $request->name,
            'position'  => $request->position,
            'is_active' => true,
        ]);

        return redirect()->route('pics.index')
            ->with('success', 'PIC berhasil ditambahkan.');
    }

    public function show(Pic $pic)
    {
        return view('pics.show', compact('pic'));
    }

    public function edit(Pic $pic)
    {
        return view('pics.edit', compact('pic'));
    }

    public function update(Request $request, Pic $pic)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        $pic->update([
            'name'     => $request->name,
            'position' => $request->position,
        ]);

        return redirect()->route('pics.index')
            ->with('success', 'Data PIC berhasil diperbarui.');
    }

    public function destroy(Pic $pic)
    {
        $pic->delete();
        return back()->with('success', 'PIC berhasil dihapus.');
    }

    public function toggleActive(Pic $pic)
    {
        $pic->update(['is_active' => !$pic->is_active]);
        $status = $pic->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "PIC berhasil {$status}.");
    }
}
