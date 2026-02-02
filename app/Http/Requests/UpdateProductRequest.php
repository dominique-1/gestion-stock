<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageProducts();
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;
        
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $productId,
            'category_id' => 'nullable|exists:categories,id',
            'supplier' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock_min' => 'sometimes|integer|min:0',
            'stock_optimal' => 'sometimes|integer|min:2',
            'current_stock' => 'sometimes|integer|min:0',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ];
    }
}
