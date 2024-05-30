<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointInteret;
use App\Models\Etape;
use App\Http\Requests\POIStoreRequest;
use App\Http\Requests\POIUpdateRequest;

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

        public function store(POIStoreRequest $request) {
            $poi = PointInteret::create([
                'nom' => $request->nom,
                'photo' => $request->photo,
            ]);
    
            $etape = Etape::find($request->etape_id);
            if ($etape) {
                $etape->pointsInteret()->attach($poi->id);
            }
    
            return response()->json($poi, 201);
        }
    
        public function update(POIUpdateRequest $request, $id) {
            $poi = PointInteret::findOrFail($id);
            $poi->update([
                'nom' => $request->nom,
                'photo' => $request->photo,
            ]);
    
            $etape = Etape::find($request->etape_id);
            if ($etape) {
                $etape->pointsInteret()->syncWithoutDetaching([$poi->id]);
            }
    
            return response()->json($poi, 200);
        }
}