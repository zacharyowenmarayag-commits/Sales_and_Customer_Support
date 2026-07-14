<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprfController;
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/asscm', [DashboardController::class, 'asscm'])->name('asscm');
Route::redirect('/sprf', '/SPRF');
Route::get('/SPRF', [SprfController::class, 'index'])->name('sprf');
Route::get('/som', [DashboardController::class, 'som'])->name('som');

Route::get('/som/new-order', [DashboardController::class, 'somNewOrder'])->name('som.new-order');

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