<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class InventoryStockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['warehouse', 'material', 'part']);

        if ($request->search) {
            $query->whereHas('material', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            })->orWhereHas('part', function($q) use ($request) {
                $q->where('part_name', 'like', "%{$request->search}%")
                  ->orWhere('part_no', 'like', "%{$request->search}%");
            });
        }

        $stocks = $query->paginate(15)->withQueryString();

        return view('inventory-stocks.index', compact('stocks'));
    }

    public function show($id)
    {
        $stock = Stock::with(['warehouse', 'material', 'part'])->findOrFail($id);
        
        $queryMutasi = \App\Models\Mutasi::where('m_warehouse_id', $stock->m_warehouse_id);
        if ($stock->m_material_id) {
            $queryMutasi->where('m_material_id', $stock->m_material_id);
        } else if ($stock->m_part_id) {
            $queryMutasi->where('m_part_id', $stock->m_part_id);
        }

        $mutasis = $queryMutasi->with(['creator', 'reference'])->latest()->paginate(20);

        return view('inventory-stocks.show', compact('stock', 'mutasis'));
    }
}
