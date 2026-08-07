<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateVoucherBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage vouchers');
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'exists:plans,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }
}
