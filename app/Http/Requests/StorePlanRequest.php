<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Plan::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'], // kobo
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'speed_limit_mbps' => ['nullable', 'integer', 'min:1'],
            'data_limit_mb' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ];
    }
}
