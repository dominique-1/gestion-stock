<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('product', 'user');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('reason')) {
            $query->where('reason', 'like', '%' . $request->reason . '%');
        }

        if ($request->filled('from')) {
            $query->whereDate('moved_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('moved_at', '<=', $request->to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $query->orderBy('moved_at', 'desc');

        return $query->paginate($request->input('per_page', 15));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $validated = $request->validated();

        $product = \App\Models\Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'out' && $product->current_stock < $validated['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $movement = StockMovement::create($validated);

        $newStock = $validated['type'] === 'in'
            ? $product->current_stock + $validated['quantity']
            : $product->current_stock - $validated['quantity'];

        $product->current_stock = $newStock;
        $product->save();

        return response()->json($movement, 201);
    }

    public function show(StockMovement $movement)
    {
        $movement->load('product', 'user');

        return response()->json($movement);
    }
}
