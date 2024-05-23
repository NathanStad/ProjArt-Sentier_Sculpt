<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointInteret;

class POIController extends Controller {
        public function index() {
            $pois = PointInteret::all();
            
            return response()->json($pois);
        }
    
        public function show($id) {
            $poi = PointInteret::find($id);
            
            if ($poi) {
                return response()->json($poi);
            } else {
                return response()->json(['message' => 'Point of Interest not found'], 404);
            }
        }
}