<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\StockMovementController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\InventoryLineController;
use App\Http\Controllers\Api\V1\AlertController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ExportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth (public)
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        // Dashboard
        Route::get('dashboard/summary', [DashboardController::class, 'summary']);
        Route::get('dashboard/charts/movements', [DashboardController::class, 'chartsMovements']);
        Route::get('dashboard/charts/sales', [DashboardController::class, 'salesChart']);
        Route::get('dashboard/predictions', [DashboardController::class, 'predictions']);
        Route::get('dashboard/top-products', [DashboardController::class, 'topProducts']);

        // Products
        Route::apiResource('products', ProductController::class);
        Route::get('select/products', [ProductController::class, 'select']);

        // Categories
        Route::apiResource('categories', CategoryController::class);
        Route::get('select/categories', [CategoryController::class, 'select']);
        Route::get('categories/tree', [CategoryController::class, 'index'])->middleware(['query:tree=1']);

        // Stock Movements
        Route::apiResource('movements', StockMovementController::class)->only(['index', 'show', 'store']);

        // Inventories
        Route::apiResource('inventories', InventoryController::class);
        Route::post('inventories/{inventory}/close', [InventoryController::class, 'close']);

        // Inventory Lines
        Route::post('inventories/{inventory}/lines', [InventoryLineController::class, 'store']);
        Route::put('inventories/{inventory}/lines/{line}', [InventoryLineController::class, 'update']);
        Route::delete('inventories/{inventory}/lines/{line}', [InventoryLineController::class, 'destroy']);

        // Alerts
        Route::apiResource('alerts', AlertController::class)->only(['index', 'destroy']);
        Route::post('alerts/{alert}/read', [AlertController::class, 'markAsRead']);

        // Exports
        Route::get('exports/stock.csv', [ExportController::class, 'stockCsv']);
        Route::get('exports/movements.csv', [ExportController::class, 'movementsCsv']);
        Route::get('exports/inventories/{inventory}.pdf', [ExportController::class, 'inventoryPdf']);
        Route::get('exports/products/{product}/sheet.pdf', [ExportController::class, 'productSheetPdf']);
    });
});
