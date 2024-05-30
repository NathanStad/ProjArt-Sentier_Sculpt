<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtapeStoreRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sentier_id' => 'required|exists:sentiers,id',
            'nom' => 'required|string|max:100',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ordre' => 'required|integer',
            'photo' => 'nullable|string',
        ];
    }
}