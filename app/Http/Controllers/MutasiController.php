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
            $query->whereHas('material', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            })->orWhereHas('part', function($q) use ($request) {
                $q->where('part_name', 'like', "%{$request->search}%")
                  ->orWhere('part_no', 'like', "%{$request->search}%");
            })->orWhere('notes', 'like', "%{$request->search}%");
        }

        if ($request->type) {
            if ($request->type == 'in_return') {
                $query->where('type', 'in')->where('reference_type', 'App\Models\ReturnGi');
            } else {
                $query->where('type', $request->type)->where('reference_type', '!=', 'App\Models\ReturnGi');
            }
        }

        $mutasis = $query->paginate(20)->withQueryString();

        return view('mutasi.index', compact('mutasis'));
    }
}
