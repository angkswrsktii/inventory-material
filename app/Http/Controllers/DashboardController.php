<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockCard;
use App\Models\WithdrawalCard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_materials' => Material::count(),
            'low_stock' => Material::whereColumn('current_stock', '<=', 'minimum_stock')
                ->where('current_stock', '>', 0)->count(),
            'empty_stock' => Material::where('current_stock', '<=', 0)->count(),
            'today_in' => StockCard::where('type', 'in')->whereDate('transaction_date', today())->sum('quantity_in'),
            'today_out' => StockCard::where('type', 'out')->whereDate('transaction_date', today())->sum('quantity_out'),
            'monthly_withdrawals' => WithdrawalCard::whereMonth('withdrawal_date', now()->month)
                ->whereYear('withdrawal_date', now()->year)->count(),
        ];

        $lowStockMaterials = Material::whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->take(5)
            ->get();

        $recentTransactions = StockCard::with('material')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $recentWithdrawals = WithdrawalCard::with(['items.material', 'creator'])
            ->orderBy('withdrawal_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Chart data - last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d M'),
                'in' => StockCard::where('type', 'in')->whereDate('transaction_date', $date)->sum('quantity_in'),
                'out' => StockCard::where('type', 'out')->whereDate('transaction_date', $date)->sum('quantity_out'),
            ];
        }

        return view('dashboard', compact('stats', 'lowStockMaterials', 'recentTransactions', 'recentWithdrawals', 'chartData'));
    }
}
