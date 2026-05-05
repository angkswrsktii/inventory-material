<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StockCardController;
use App\Http\Controllers\WithdrawalCardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

    // ── PURCHASE REQUESTS ─────────────────────────────────
    // Kepala Gudang bisa buat PR, Pimpinan approve
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::post('purchase-requests/{purchaseRequest}/submit',       [PurchaseRequestController::class, 'submit'])->name('purchase-requests.submit');
    Route::post('purchase-requests/{purchaseRequest}/approve',      [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
    Route::post('purchase-requests/{purchaseRequest}/reject',       [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    Route::post('purchase-requests/{purchaseRequest}/mark-ordered', [PurchaseRequestController::class, 'markOrdered'])->name('purchase-requests.mark-ordered');
    Route::get('purchase-requests/{purchaseRequest}/print',         [PurchaseRequestController::class, 'print'])->name('purchase-requests.print');

    // ── PURCHASE ORDERS ───────────────────────────────────
    // Kepala Gudang buat PO, Pimpinan send/approve
    Route::get('purchase-orders',                           [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('purchase-orders/create',                    [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('purchase-orders',                          [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchaseOrder}',           [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::post('purchase-orders/{purchaseOrder}/send',     [PurchaseOrderController::class, 'send'])->name('purchase-orders.send');
    Route::post('purchase-orders/{purchaseOrder}/cancel',   [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
    Route::post('purchase-orders/{purchaseOrder}/receive',  [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::get('purchase-orders/{purchaseOrder}/print',     [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('stock',        [ReportController::class, 'stockReport'])->name('stock');
        Route::get('transactions', [ReportController::class, 'transactionReport'])->name('transactions');
        Route::get('withdrawals',  [ReportController::class, 'withdrawalReport'])->name('withdrawals');
        Route::get('print/stock-card/{material}',         [ReportController::class, 'printStockCard'])->name('print.stock-card');
        Route::get('print/withdrawal/{withdrawalCard}',   [ReportController::class, 'printWithdrawal'])->name('print.withdrawal');
    });

    // ── SUPPLIERS ─────────────────────────────────────────
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])->name('suppliers.toggle-active');

    // User management (pimpinan/admin only — middleware di controller)
    Route::get('users',                         [UserController::class, 'index'])->name('users.index');
    Route::get('users/create',                  [UserController::class, 'create'])->name('users.create');
    Route::post('users',                        [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit',             [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}',                  [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}',               [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/toggle-active',  [UserController::class, 'toggleActive'])->name('users.toggle-active');
});

require __DIR__ . '/auth.php';