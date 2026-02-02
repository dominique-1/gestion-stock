<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageProducts();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'supplier' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_optimal' => 'required|integer|min:2',
            'current_stock' => 'required|integer|min:0',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ];
    }
}
