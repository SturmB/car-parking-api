<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'plate_number' => 'required',
            'description' => 'string|nullable'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
