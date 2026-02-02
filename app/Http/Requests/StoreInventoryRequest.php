<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageInventories();
    }

    public function rules(): array
    {
        return [
            'performed_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}
