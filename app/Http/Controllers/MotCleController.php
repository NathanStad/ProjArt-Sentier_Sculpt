<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotCle;

class MotCleController extends Controller {
    public function index() {
        $motcles = Critere::all();
            
        return response()->json($motcles);
    }
}