<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\StockMovementController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\AlertController;
use App\Http\Controllers\Web\ExportController;
use App\Http\Controllers\Web\SmartAlertController;
use App\Http\Controllers\Web\PredictionController;
use Illuminate\Support\Facades\Route;

// Guest routes (non-authentifiés)
Route::get('/', function () {
    return view('landing');
});

// Login routes (toujours accessibles)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('session')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/{product}/documents/{document}/download', [ProductController::class, 'downloadDocument'])->name('products.documents.download');
    Route::delete('products/{product}/documents/{document}', [ProductController::class, 'deleteDocument'])->name('products.documents.delete');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Stock Movements
    Route::resource('movements', StockMovementController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
    
    // Inventories
    Route::resource('inventories', InventoryController::class);
    Route::post('inventories/{inventory}/close', [InventoryController::class, 'close'])->name('inventories.close');
    Route::post('inventories/{inventory}/archive', [InventoryController::class, 'archive'])->name('inventories.archive');
    Route::post('inventories/{inventory}/restore', [InventoryController::class, 'restore'])->name('inventories.restore');
    
    // Alerts
    Route::resource('alerts', AlertController::class);
    Route::post('alerts/{id}/mark-read', [AlertController::class, 'markAsRead'])->name('alerts.mark-read');
    Route::post('alerts/mark-all-read', [AlertController::class, 'markAllAsRead'])->name('alerts.mark-all-read');
    Route::get('alerts/send-emails', [AlertController::class, 'sendEmails'])->name('alerts.send-emails');
    Route::get('alerts/emails', [AlertController::class, 'emails'])->name('alerts.emails');
    Route::get('alerts/stats', [AlertController::class, 'stats'])->name('alerts.stats');
    Route::get('alerts/test-email', [AlertController::class, 'testEmail'])->name('alerts.test-email');
    Route::get('alerts/cleanup', [AlertController::class, 'cleanup'])->name('alerts.cleanup');
    
    
    // Prédictions
    Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');
    Route::get('/api/predictions', [PredictionController::class, 'apiPredictions'])->name('api.predictions');
    
    // Smart Alerts - Nouveau système intelligent
    Route::get('/smart-alerts', [SmartAlertController::class, 'index'])->name('smart-alerts.index');
    Route::post('/smart-alerts/send-emails', [SmartAlertController::class, 'sendEmails'])->name('smart-alerts.send-emails');
    Route::post('/smart-alerts/mark-read/{id}', [SmartAlertController::class, 'markAsRead'])->name('smart-alerts.mark-read');
    Route::post('/smart-alerts/mark-all-read', [SmartAlertController::class, 'markAllAsRead'])->name('smart-alerts.mark-all-read');
    Route::post('/smart-alerts/dismiss/{id}', [SmartAlertController::class, 'dismiss'])->name('smart-alerts.dismiss');
    Route::get('/smart-alerts/analytics', [SmartAlertController::class, 'analytics'])->name('smart-alerts.analytics');
    Route::get('/smart-alerts/dashboard', [SmartAlertController::class, 'dashboard'])->name('smart-alerts.dashboard');
});

// Exports - Accessibles sans session
Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
Route::get('/exports/test', [ExportController::class, 'test'])->name('exports.test');
Route::get('/exports/stock.csv', [ExportController::class, 'stockCsv'])->name('exports.stock.csv');
Route::get('/exports/movements.csv', [ExportController::class, 'movementsCsv'])->name('exports.movements.csv');

// Exports PDF
Route::get('/exports/inventaires.pdf', [ExportController::class, 'inventoriesPdf'])->name('exports.inventaires.pdf');
Route::get('/exports/produits.pdf', [ExportController::class, 'productsListPdf'])->name('exports.products.pdf');
Route::get('/exports/rapport.pdf', [ExportController::class, 'reportsPdf'])->name('exports.reports.pdf');

// Exports Excel
Route::get('/exports/stock.xls', [ExportController::class, 'stockExcel'])->name('exports.stock.xls');
Route::get('/exports/mouvements.xls', [ExportController::class, 'movementsExcel'])->name('exports.movements.xls');

// Export fiche produit
Route::get('/exports/product/{id}/sheet.pdf', [ExportController::class, 'productSheetPdf'])->name('exports.product.sheet.pdf');
