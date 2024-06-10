<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;

class CritereController extends Controller {
    public function index() {
        $criteres= Critere::all();
            
        return response()->json($criteres);
    }  

    public static function transformToArray($input)
    {
        if (is_string($input)) {
            return explode(',', trim($input, '[]'));
        }
    
        if (is_array($input)) {
            return $input;
        }
    
        return [];
    }

    public function arrayMethod(Request $request)
    {
        $array = $this->transformToArray($request, 'criteres');

        return response()->json($array);
    }
}