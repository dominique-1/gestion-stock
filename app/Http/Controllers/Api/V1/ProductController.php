<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('barcode', 'like', '%' . $request->q . '%')
                  ->orWhere('supplier', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('barcode')) {
            $query->where('barcode', $request->barcode);
        }

        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        if ($request->boolean('overstock')) {
            $query->overstock();
        }

        if ($request->boolean('expiring_soon')) {
            $query->expiringSoon();
        }

        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $query->orderBy($sort, $order);

        return $query->paginate($request->input('per_page', 15));
    }

    public function select(Request $request)
    {
        $query = Product::select('id', 'name', 'barcode');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('barcode', 'like', '%' . $request->q . '%');
            });
        }

        $limit = min($request->input('limit', 20), 100);
        $items = $query->limit($limit)->get()->map(function ($product) {
            $label = $product->name;
            if ($product->barcode) {
                $label .= ' (' . $product->barcode . ')';
            }
            return ['id' => $product->id, 'label' => $label];
        });

        return response()->json($items);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load('category', 'documents', 'stockMovements', 'alerts');

        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
