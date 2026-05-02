<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StockCardController;
use App\Http\Controllers\WithdrawalCardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Materials
    Route::resource('materials', MaterialController::class);

    // Stock Cards
    Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
    Route::get('stock-cards/create', [StockCardController::class, 'create'])->name('stock-cards.create');
    Route::post('stock-cards', [StockCardController::class, 'store'])->name('stock-cards.store');
    Route::get('stock-cards/material/{material}', [StockCardController::class, 'show'])->name('stock-cards.show');

    // Withdrawal Cards
    Route::resource('withdrawal-cards', WithdrawalCardController::class)->except(['edit', 'update']);

    // Purchase Requests
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::post('purchase-requests/{purchaseRequest}/submit',      [PurchaseRequestController::class, 'submit'])->name('purchase-requests.submit');
    Route::post('purchase-requests/{purchaseRequest}/approve',     [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
    Route::post('purchase-requests/{purchaseRequest}/reject',      [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    Route::post('purchase-requests/{purchaseRequest}/mark-ordered', [PurchaseRequestController::class, 'markOrdered'])->name('purchase-requests.mark-ordered');
    Route::get('purchase-requests/{purchaseRequest}/print',        [PurchaseRequestController::class, 'print'])->name('purchase-requests.print');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('stock', [ReportController::class, 'stockReport'])->name('stock');
        Route::get('transactions', [ReportController::class, 'transactionReport'])->name('transactions');
        Route::get('withdrawals', [ReportController::class, 'withdrawalReport'])->name('withdrawals');
        Route::get('print/stock-card/{material}', [ReportController::class, 'printStockCard'])->name('print.stock-card');
        Route::get('print/withdrawal/{withdrawalCard}', [ReportController::class, 'printWithdrawal'])->name('print.withdrawal');
    });
});

// Auth routes would go here (Laravel Breeze/Fortify)
require __DIR__ . '/auth.php';
