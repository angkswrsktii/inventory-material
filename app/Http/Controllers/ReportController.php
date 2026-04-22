<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockCard;
use App\Models\WithdrawalCard;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        $materials = Material::withCount('stockCards')
            ->orderBy('name')
            ->get();

        return view('reports.stock', compact('materials'));
    }

    public function transactionReport(Request $request)
    {
        $query = StockCard::with('material')
            ->orderBy('transaction_date', 'desc');

        if ($request->material_id) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $materials = Material::orderBy('name')->get();

        $summary = [
            'total_in' => $query->clone()->sum('quantity_in'),
            'total_out' => $query->clone()->sum('quantity_out'),
        ];

        return view('reports.transactions', compact('transactions', 'materials', 'summary'));
    }

    public function withdrawalReport(Request $request)
    {
        $query = WithdrawalCard::with(['items.material', 'creator'])
            ->orderBy('withdrawal_date', 'desc');

        if ($request->date_from) {
            $query->whereDate('withdrawal_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('withdrawal_date', '<=', $request->date_to);
        }

        if ($request->line) {
            $query->where('line', 'like', "%{$request->line}%");
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('reports.withdrawals', compact('withdrawals'));
    }

    public function printStockCard(Material $material)
    {
        $stockCards = $material->stockCards()
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('reports.print-stock-card', compact('material', 'stockCards'));
    }

    public function printWithdrawal(WithdrawalCard $withdrawalCard)
    {
        $withdrawalCard->load(['items.material', 'creator', 'approver']);
        return view('reports.print-withdrawal', compact('withdrawalCard'));
    }
}
