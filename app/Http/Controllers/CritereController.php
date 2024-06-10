<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;

class CritereController extends Controller {
    public function index() {
        $criteres= Critere::all();
            
        return response()->json($criteres);
    }  

    public function transformToArray(Request $jsonParam) {
        if (is_null($jsonParam)) {
            return [];
        }

        return json_decode(json_encode($jsonParam), true);
    }
}