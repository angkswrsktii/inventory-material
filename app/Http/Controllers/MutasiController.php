<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasi::with(['warehouse', 'material', 'part', 'creator', 'reference'])->orderByDesc('id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('material', function ($q2) use ($request) {
                    $q2->where('name', 'like', "%{$request->search}%")
                       ->orWhere('code', 'like', "%{$request->search}%");
                })->orWhereHas('part', function ($q2) use ($request) {
                    $q2->where('part_name', 'like', "%{$request->search}%")
                       ->orWhere('part_no', 'like', "%{$request->search}%");
                })->orWhere('notes', 'like', "%{$request->search}%");
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $mutasis = $query->paginate(20)->withQueryString();

        return view('mutasi.index', compact('mutasis'));
    }
}
