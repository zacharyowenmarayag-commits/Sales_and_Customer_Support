<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::redirect('/sprf', '/SPRF');
Route::get('/SPRF', [DashboardController::class, 'sprf'])->name('sprf');
Route::get('/som', [DashboardController::class, 'som'])->name('som');

Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', [DashboardController::class, 'crmDashboard'])->name('dashboard');
    Route::get('/customers', [DashboardController::class, 'crmCustomers'])->name('customers');
    Route::get('/purchase-history', [DashboardController::class, 'crmPurchaseHistory'])->name('purchaseHistory');
    Route::get('/communication-logs', [DashboardController::class, 'crmCommunicationLogs'])->name('comlog');
    Route::get('/follow-ups', [DashboardController::class, 'crmFollowup'])->name('followup');
    Route::get('/segmentation', [DashboardController::class, 'crmSegmentation'])->name('segmentation');
});

Route::prefix('SPRF')->name('sprf.')->group(function () {
    Route::get('/deals', [DashboardController::class, 'sprfDeals'])->name('deals');
});