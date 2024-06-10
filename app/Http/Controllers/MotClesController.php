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

    public function transformToArray(Request $jsonParam) {

        if (is_null($jsonParam)) {
            return [];
        }

        return json_decode(json_encode($jsonParam), true);
    }
}