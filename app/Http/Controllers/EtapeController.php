<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etape;

class EtapeController extends Controller {
        public function index() {
            $etapes = Etape::with('pointsInteret')->get();
            
            return response()->json($etapes);
        }

        public function show($id) {
            $etape = Etape::with('pointsInteret')->find($id);
            
            if ($etape) {
                return response()->json($etape);
            } else {
                return response()->json(['message' => 'Etape not found'], 404);
            }
        }
}