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
        $array = $this->transformToArray($request, 'motcles');

        return response()->json($array);
    }
}