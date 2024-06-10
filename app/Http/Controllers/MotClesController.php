<?php

namespace App\Http\Controllers;

use App\Models\MotCle;
use Illuminate\Http\Request;

class MotClesController extends Controller
{
    public function index() {
        $pois = MotCle::all();
        
        return response()->json($pois);
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
        $array = $this->transformToArray($request, 'motcles');

        return response()->json($array);
    }
}