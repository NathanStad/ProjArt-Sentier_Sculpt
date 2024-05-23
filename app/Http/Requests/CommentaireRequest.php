<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentaireRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sentier_id' => 'required|exists:sentiers,id',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string',
        ];
    }
}