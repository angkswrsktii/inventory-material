<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GoodsAdjustmentController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductionQcController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\GoodIssueController;
use App\Http\Controllers\GoodReceiptController;
use App\Http\Controllers\InventoryStockController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ReturnGiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Materials
    Route::resource('materials', MaterialController::class);

    // Parts
    Route::resource('parts', PartController::class);

    // Inventory Stocks
    Route::resource('inventory-stocks', InventoryStockController::class)->only(['index', 'show']);

    // Project
    Route::resource('projects', ProjectController::class)->except(['show', 'destroy']);

    // Mutasi
    Route::get('mutasi', [MutasiController::class, 'index'])->name('mutasi.index');

    // Good Receipts
    Route::resource('good-receipts', GoodReceiptController::class);
    Route::get('good-receipts/print/{id}', [GoodReceiptController::class, 'index'])->name('reports.print.good-receipt');

    // Good Issues
    Route::resource('good-issues', GoodIssueController::class);
    Route::get('/good-issues/{goodIssue}/print', [GoodIssueController::class, 'print'])
        ->name('reports.print.good-issue');

    // Return GI
    Route::get('return-gi/{returnGi}/print', [ReturnGiController::class, 'print'])->name('return-gi.print');
    Route::resource('return-gi', ReturnGiController::class);

    // ── PURCHASE REQUESTS ─────────────────────────────────
    // Kepala Gudang bisa buat PR, Pimpinan approve
    Route::resource('purchase-requests', PurchaseRequestController::class);

    // goods adjustment
    Route::post('goods-adjustment/update-material', [GoodsAdjustmentController::class, 'updateMaterialData'])->name('goods-adjustment.update-material');
    Route::resource('goods-adjustment',GoodsAdjustmentController::class)->only(['index', 'create', 'store']);

    // Aksi alur status PR
    Route::post('purchase-requests/{purchaseRequest}/submit',       [PurchaseRequestController::class, 'submit'])->name('purchase-requests.submit');
    Route::post('purchase-requests/{purchaseRequest}/approve',      [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
    Route::post('purchase-requests/{purchaseRequest}/reject',       [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

    // Aksi untuk mengembalikan status pending menjadi draft agar bisa diedit kembali
    Route::post('purchase-requests/{purchaseRequest}/revert-draft', [PurchaseRequestController::class, 'revertToDraft'])->name('purchase-requests.revert-draft');

    // Aksi ke status akhir (Pastikan method di controller sesuai, apakah markOrdered atau markCompleted)
    Route::post('purchase-requests/{purchaseRequest}/mark-ordered', [PurchaseRequestController::class, 'markOrdered'])->name('purchase-requests.mark-ordered');

    // Fitur cetak dokumen
    Route::get('purchase-requests/{purchaseRequest}/print',         [PurchaseRequestController::class, 'print'])->name('purchase-requests.print');

    // ── PURCHASE ORDERS ───────────────────────────────────
    Route::prefix('purchase-orders')->name('purchase-orders.')->controller(PurchaseOrderController::class)->group(function () {

        // CRUD Dasar
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        // ➔ TAMBAHAN BARU: Rute untuk menampilkan form edit dan menyimpan perubahannya
        Route::get('/{purchaseOrder}/edit', 'edit')->name('edit');
        Route::put('/{purchaseOrder}', 'update')->name('update');

        Route::get('/{purchaseOrder}', 'show')->name('show');

        // Custom Actions (Metode Bisnis Logik)
        Route::post('/{purchaseOrder}/send', 'send')->name('send');
        Route::post('/{purchaseOrder}/cancel', 'cancel')->name('cancel');
        Route::post('/{purchaseOrder}/receive', 'receive')->name('receive');
        Route::get('/{purchaseOrder}/print', 'print')->name('print');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('receiving',  [ReportController::class, 'receivingReport'])->name('receiving');
        Route::get('disbursal',  [ReportController::class, 'disbursalReport'])->name('disbursal');
        Route::get('print/withdrawal/{goodIssue}', [ReportController::class, 'printWithdrawal'])->name('print.withdrawal');
    });

    // ── SUPPLIERS ─────────────────────────────────────────
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])->name('suppliers.toggle-active');

    // ── CUSTOMERS ─────────────────────────────────────────
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');

    // ── QC Produksi ──────────────────────────────────────
    Route::get('production-qc',                         [ProductionQcController::class, 'index'])->name('production-qc.index');
    Route::get('production-qc/create',                  [ProductionQcController::class, 'create'])->name('production-qc.create');
    Route::post('production-qc',                        [ProductionQcController::class, 'store'])->name('production-qc.store');
    Route::get('production-qc/{productionQc}',          [ProductionQcController::class, 'show'])->name('production-qc.show');
    Route::post('production-qc/{productionQc}/approve', [ProductionQcController::class, 'approve'])->name('production-qc.approve');
    Route::post('production-qc/{productionQc}/reject',  [ProductionQcController::class, 'reject'])->name('production-qc.reject');
    Route::delete('production-qc/{productionQc}',       [ProductionQcController::class, 'destroy'])->name('production-qc.destroy');
    // Tambahkan route ini sebelum route resource jika menggunakan resource
    Route::get('production-qc/{productionQc}/print', [ProductionQcController::class, 'print'])->name('production-qc.print');
    Route::post('production-qc/{productionQc}/approve', [ProductionQcController::class, 'approve'])->name('production-qc.approve');

    Route::resource('production-qc', ProductionQcController::class);

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
// ── Language Switcher ─────────────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    $supported = ['id', 'en'];
    if (in_array($locale, $supported)) {
        \Illuminate\Support\Facades\Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');