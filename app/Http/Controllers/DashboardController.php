<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Stock;
use App\Models\Mutasi;
use App\Models\GoodIssue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_materials' => Material::count(),
            'low_stock' => Stock::whereColumn('current_stock', '<=', 'minimum_stock')
                ->where('current_stock', '>', 0)->count(),
            'empty_stock' => Stock::where('current_stock', '<=', 0)->count(),
            'today_in' => Mutasi::where('type', 'in')->whereDate('created_at', today())->sum('quantity'),
            'today_out' => Mutasi::where('type', 'out')->whereDate('created_at', today())->sum('quantity'),
            'monthly_withdrawals' => GoodIssue::whereMonth('issue_date', now()->month)
                ->whereYear('issue_date', now()->year)->count(),
        ];

        $lowStocks = Stock::with(['material', 'part', 'warehouse'])
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->take(5)
            ->get();

        $recentTransactions = Mutasi::with(['material', 'part', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $recentWithdrawals = GoodIssue::with(['items.material', 'issuer'])
            ->orderBy('issue_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Chart data - last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d M'),
                'in' => Mutasi::where('type', 'in')->whereDate('created_at', $date)->sum('quantity'),
                'out' => Mutasi::where('type', 'out')->whereDate('created_at', $date)->sum('quantity'),
            ];
        }

        return view('dashboard', compact('stats', 'lowStocks', 'recentTransactions', 'recentWithdrawals', 'chartData'));
    }
}
