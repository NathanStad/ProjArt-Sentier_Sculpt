<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;

class CritereController extends Controller {
    public function index() {
        $criteres = Critere::all();
            
        return response()->json($themes);
    }
}