<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommunicationLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprfController;

// Authentication Routes (admin-only, no register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes — require authentication
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/asscm', [DashboardController::class, 'asscm'])->name('asscm');
    Route::post('/asscm', [DashboardController::class, 'asscmStore'])->name('asscm.store');
    Route::get('/asscm/{caseId}/view', [DashboardController::class, 'asscmView'])->name('asscm.view');
    Route::post('/asscm/{caseId}/update', [DashboardController::class, 'asscmUpdate'])->name('asscm.update');
    Route::redirect('/sprf', '/SPRF');
    Route::get('/SPRF', [DashboardController::class, 'sprf'])->name('sprf');
    Route::get('/som', [DashboardController::class, 'som'])->name('som');
    Route::get('/db-schema', [DashboardController::class, 'dbSchema'])->name('db-schema');

    Route::get('/som/new-order', [DashboardController::class, 'somNewOrder'])->name('som.new-order');
    Route::post('/som/new-order', [DashboardController::class, 'somSaveOrder'])->name('som.save-order');

    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [DashboardController::class, 'crmDashboard'])->name('dashboard');
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
        Route::post('/segmentation', [DashboardController::class, 'crmSegmentationStore'])->name('segmentation.store');
        Route::post('/segmentation/update', [DashboardController::class, 'crmSegmentationUpdate'])->name('segmentation.update');
    });

    Route::prefix('SPRF')->name('sprf.')->group(function () {
        Route::get('/deals', [DashboardController::class, 'sprfDeals'])->name('deals');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

