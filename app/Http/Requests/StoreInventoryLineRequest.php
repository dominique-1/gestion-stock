<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryLineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageInventories() && $this->inventory->status === 'draft';
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'qty_expected' => ['required', 'integer', 'min:0'],
            'qty_counted' => ['required', 'integer', 'min:0'],
            'justification' => ['nullable', 'string'],
        ];
    }
}
