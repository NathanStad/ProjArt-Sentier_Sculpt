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
}
