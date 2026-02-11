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
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;

// Guest routes (non-authentifiés)
Route::get('/', function () {
    return view('landing');
});

Route::get('/demo-messages', function () {
    // Simuler des messages flash pour la démo
    if (request()->has('flash')) {
        if (request()->get('flash') === 'success') {
            return redirect()->route('demo.messages')->with('success', 'Ceci est un message de succès de Laravel !');
        } elseif (request()->get('flash') === 'error') {
            return redirect()->route('demo.messages')->with('error', 'Ceci est un message d\'erreur de Laravel !');
        }
    }
    return view('demo-messages');
})->name('demo.messages');

Route::get('/reset-alerts', function () {
    // Forcer la réinitialisation des alertes
    session()->forget('alerts');
    return redirect()->route('alerts.index')->with('success', 'Alertes réinitialisées avec les nouvelles propriétés !');
})->name('reset.alerts');

Route::get('/reset-movements', function () {
    // Forcer la réinitialisation des mouvements
    session()->forget('movements');
    return redirect()->route('movements.index')->with('success', 'Mouvements réinitialisés avec succès !');
})->name('reset.movements');

Route::get('/init-movements', function () {
    // Forcer l'initialisation immédiate des mouvements
    $defaultMovements = [
        (object)[
            'id' => 1,
            'product' => (object)[
                'id' => 1,
                'name' => 'Laptop Pro 15"', 
                'barcode' => 'LP15-001'
            ],
            'type' => 'out',
            'quantity' => 2,
            'reason' => 'Vente client',
            'moved_at' => now()->subHours(1),
            'user' => (object)['name' => 'Admin'],
        ],
        (object)[
            'id' => 2,
            'product' => (object)[
                'id' => 2,
                'name' => 'Moniteur 27"', 
                'barcode' => 'MON27-001'
            ],
            'type' => 'in',
            'quantity' => 5,
            'reason' => 'Réception fournisseur',
            'moved_at' => now()->subHours(3),
            'user' => (object)['name' => 'Admin'],
        ],
        (object)[
            'id' => 3,
            'product' => (object)[
                'id' => 3,
                'name' => 'Clavier mécanique', 
                'barcode' => 'KEY-MEC-001'
            ],
            'type' => 'out',
            'quantity' => 1,
            'reason' => 'Retour client',
            'moved_at' => now()->subHours(5),
            'user' => (object)['name' => 'Admin'],
        ],
    ];
    session()->put('movements', $defaultMovements);
    return redirect()->route('movements.index')->with('success', '3 mouvements initialisés avec succès !');
})->name('init.movements');

// Route pour les migrations
Route::get('/migrate', [MigrationController::class, 'migrate'])->name('migrate');

// Login routes (toujours accessibles)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes publiques (sans authentification)
Route::get('/movements', [StockMovementController::class, 'index'])->name('movements.index');
Route::get('/movements/create', [StockMovementController::class, 'create'])->name('movements.create');
Route::get('/movements/{movement}', [StockMovementController::class, 'show'])->name('movements.show');
Route::post('/movements', [StockMovementController::class, 'store'])->name('movements.store');
Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
Route::get('/inventories/create', [InventoryController::class, 'create'])->name('inventories.create');
Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->name('inventories.show');
Route::get('/inventories/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventories.edit');
Route::delete('/inventories/{id}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

// Test d'archivage
Route::post('/inventories/{id}/archive', [InventoryController::class, 'archive'])->name('inventories.archive');
Route::post('/inventories/{id}/restore', [InventoryController::class, 'restore'])->name('inventories.restore');
Route::post('/inventories/{id}/close', [InventoryController::class, 'close'])->name('inventories.close');
Route::put('/inventories/{id}', [InventoryController::class, 'update'])->name('inventories.update');

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
    
    // Stock Movements (seulement edit/update/destroy nécessitent une auth)
    Route::get('/movements/{movement}/edit', [StockMovementController::class, 'edit'])->name('movements.edit');
    Route::put('/movements/{movement}', [StockMovementController::class, 'update'])->name('movements.update');
    Route::delete('/movements/{movement}', [StockMovementController::class, 'destroy'])->name('movements.destroy');
    
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
