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
            'localisation' => 'required|string|max:150',
            'photo' => 'nullable|string',
            'theme_id' => 'required|exists:themes,id',
            'difficulte_id' => 'required|exists:difficultes,id',
            'criteres' => 'sometimes|array',
            'criteres.*' => 'exists:criteres,id',
            'motcles' => 'sometimes|array',
            'motcles.*' => 'exists:mot_cles,id',
            'etapes' => 'required|array',
            'etapes.*.nom' => 'required|string',
            'etapes.*.description' => 'required|string',
            'etapes.*.latitude' => 'required|numeric',
            'etapes.*.longitude' => 'required|numeric',
            'etapes.*.distance' => 'required|numeric',
            'etapes.*.duree' => 'required|numeric',
            'etapes.*.ordre' => 'required|integer',
            'etapes.*.photo' => 'nullable|string',
        ];
    }
}

