<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class DeviceConnectedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mac' => ['required', 'string', 'max:17'],
            'ip' => ['required', 'ip'],
        ];
    }
}
