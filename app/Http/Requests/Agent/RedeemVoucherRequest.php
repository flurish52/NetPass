<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class RedeemVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20'],
            'mac' => ['required', 'string', 'max:17'],
            'ip' => ['nullable', 'ip'],
        ];
    }
}
