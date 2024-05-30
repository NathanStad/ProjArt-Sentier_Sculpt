<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class POIUpdateRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:100',
            'photo' => 'nullable|string',
            'etape_id' => 'required|exists:etapes,id',
        ];
    }
}