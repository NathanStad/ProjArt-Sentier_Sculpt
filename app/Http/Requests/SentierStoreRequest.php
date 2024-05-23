<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SentierStoreRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'nom' => 'required|string|max:100',
            'description' => 'required|string',
            'duree' => 'required|integer',
            'longueur' => 'required|numeric',
            'point_depart' => 'required|string|max:50',
            'point_arrive' => 'required|string|max:50',
            'photo' => 'nullable|url',
            'theme_id' => 'required|exists:themes,id',
            'etapes' => 'required|array',
            'etapes.*.nom' => 'required|string|max:100',
            'etapes.*.description' => 'required|string',
            'etapes.*.latitude' => 'required|numeric',
            'etapes.*.longitude' => 'required|numeric',
            'etapes.*.ordre' => 'required|integer',
            'etapes.*.points_interet' => 'nullable|array',
            'etapes.*.points_interet.*.nom' => 'required_with:etapes.*.points_interet|string|max:100',
            'etapes.*.points_interet.*.description' => 'required_with:etapes.*.points_interet|string',
            'etapes.*.points_interet.*.latitude' => 'required_with:etapes.*.points_interet|numeric',
            'etapes.*.points_interet.*.longitude' => 'required_with:etapes.*.points_interet|numeric',
            'etapes.*.points_interet.*.photo' => 'nullable|url',
        ];
    }
}