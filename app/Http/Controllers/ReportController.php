<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Mutasi;
use App\Models\GoodIssue;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        // Ambil material beserta total stok dari seluruh gudang
        $materials = Material::with('stocks', 'supplier')->orderBy('name')->get();

        $chartLabels = [];
        $chartData = [];
        $chartColors = [];

        foreach ($materials as $m) {
            // Kalkulasi stok dan min stok (sesuai rumus 10 * cut_per_day)
            $m->total_stock = $m->stocks->sum('current_stock');
            $m->min_stock = ($m->cut_per_day ?? 0.1) * 10;

            // Ambil 15 material dengan stok terbanyak untuk ditampilkan di grafik
            if (count($chartLabels) < 15 && $m->total_stock > 0) {
                $chartLabels[] = $m->name;
                $chartData[] = $m->total_stock;
                // Merah jika di bawah min_stock, Hijau jika aman
                $chartColors[] = $m->total_stock <= $m->min_stock ? '#ef4444' : '#10b981';
            }
        }

        return view('reports.stock', compact('materials', 'chartLabels', 'chartData', 'chartColors'));
    }

    public function withdrawalReport(Request $request)
    {
        $query = GoodIssue::with(['items.material', 'pic', 'part', 'issuer'])
            ->orderBy('issue_date', 'desc');

        if ($request->date_from) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }
        if ($request->pic) {
            $query->whereHas('pic', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->pic}%");
            });
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('reports.withdrawals', compact('withdrawals'));
    }

    public function printStockCard(Material $material)
    {
        $stockCards = Mutasi::where('m_material_id', $material->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('reports.print-stock-card', compact('material', 'stockCards'));
    }

    public function printWithdrawal(GoodIssue $goodIssue)
    {
        $goodIssue->load(['items.material', 'issuer', 'receiver', 'pic', 'part']);
        return view('reports.print-withdrawal', compact('goodIssue'));
    }
}