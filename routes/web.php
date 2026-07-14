<?php

use App\Http\Controllers\CommunicationLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::redirect('/sprf', '/SPRF');
Route::get('/SPRF', [DashboardController::class, 'sprf'])->name('sprf');
Route::get('/som', [DashboardController::class, 'som'])->name('som');

Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', [DashboardController::class, 'crmDashboard'])->name('dashboard');
    // explicit dashboard route (requested reroute)
    Route::get('/dashboard', [DashboardController::class, 'crmDashboard'])->name('dashboard.page');


    Route::get('/customers', [DashboardController::class, 'crmCustomers'])->name('customers');
    Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');

    Route::get('/purchase-history', [\App\Http\Controllers\PurchaseHistoryController::class, 'index'])->name('purchaseHistory');
    Route::get('/purchase-history/export', [\App\Http\Controllers\PurchaseHistoryController::class, 'export'])->name('purchaseHistory.export');

    Route::get('/communication-logs', [DashboardController::class, 'crmCommunicationLogs'])->name('comlog');

    Route::post('/communication-logs', [CommunicationLogController::class, 'store'])->name('comlog.store');
    Route::get('/follow-ups', [DashboardController::class, 'crmFollowup'])->name('followup');
    Route::patch('/follow-ups/{followUpId}', [FollowUpController::class, 'update'])->name('followup.update');


    Route::get('/segmentation', [DashboardController::class, 'crmSegmentation'])->name('segmentation');
});


Route::prefix('SPRF')->name('sprf.')->group(function () {
    Route::get('/deals', [DashboardController::class, 'sprfDeals'])->name('deals');
});

