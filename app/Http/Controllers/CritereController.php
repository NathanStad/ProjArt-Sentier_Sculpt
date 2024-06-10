<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;

class CritereController extends Controller {
    public function index() {
        $criteres= Critere::all();
            
        return response()->json($criteres);
    }  

    public function transformToArray(Request $request, $key)
    {
        $jsonString = $request->input($key);

        if (is_null($jsonString)) {
            return [];
        }

        return json_decode($jsonString, true);
    }

    public function arrayMethod(Request $request)
    {
        $array = $this->transformToArray($request, 'criteres');

        return response()->json($array);
    }
}