<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('plan'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'duration_minutes' => ['sometimes', 'required', 'integer', 'min:1'],
            'speed_limit_mbps' => ['nullable', 'integer', 'min:1'],
            'data_limit_mb' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ];
    }
}
