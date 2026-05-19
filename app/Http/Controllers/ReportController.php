<?php

namespace App\Http\Controllers;

use App\Models\GoodIssue;
use App\Models\GoodReceipt;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function receivingReport(Request $request)
    {
        $query = GoodReceipt::with(['purchaseOrder', 'pic', 'receiver', 'items.material'])
            ->orderBy('receipt_date', 'desc');

        if ($request->date_from) {
            $query->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('receipt_date', '<=', $request->date_to);
        }
        if ($request->pic_id) {
            $query->where('m_pic_id', $request->pic_id);
        }

        $receipts = $query->paginate(15)->withQueryString();
        $users    = User::where('is_active', true)->orderBy('name')->get();

        return view('reports.receiving', compact('receipts', 'users'));
    }

    public function disbursalReport(Request $request)
    {
        $query = GoodIssue::with(['items.material', 'pic', 'part', 'issuer'])
            ->orderBy('issue_date', 'desc');

        if ($request->date_from) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }
        if ($request->pic_id) {
            $query->where('m_pic_id', $request->pic_id);
        }

        $withdrawals = $query->paginate(15)->withQueryString();
        $users       = User::where('is_active', true)->orderBy('name')->get();

        return view('reports.disbursal', compact('withdrawals', 'users'));
    }

    public function printWithdrawal(GoodIssue $goodIssue)
    {
        $goodIssue->load(['items.material', 'issuer', 'receiver', 'pic', 'part']);
        return view('reports.print-withdrawal', compact('goodIssue'));
    }
}