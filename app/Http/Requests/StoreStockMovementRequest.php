<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageMovements();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'reason' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'moved_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}
