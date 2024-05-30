<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etape;
use App\Http\Requests\EtapeStoreRequest;
use App\Http\Requests\EtapeUpdateRequest;

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

        public function store(EtapeStoreRequest $request) {
            $etape = Etape::create([
                'sentier_id' => $request->sentier_id,
                'nom' => $request->nom,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'ordre' => $request->ordre,
                'photo' => $request->photo,
            ]);
    
            $this->updateDistancesAndDurations($etape->sentier_id);
    
            return response()->json($etape, 201);
        }
    
        public function update(EtapeUpdateRequest $request, $id) {
            $etape = Etape::findOrFail($id);
            $etape->update([
                'nom' => $request->nom,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'ordre' => $request->ordre,
                'photo' => $request->photo,
            ]);
    
            $this->updateDistancesAndDurations($etape->sentier_id);
    
            return response()->json($etape, 200);
        }
    
        private function updateDistancesAndDurations($sentier_id) {
            $etapes = Etape::where('sentier_id', $sentier_id)->orderBy('ordre')->get();
    
            for ($i = 0; $i < $etapes->count(); $i++) {
                $etape = $etapes[$i];
    
                if ($i < $etapes->count() - 1) {
                    $nextEtape = $etapes[$i + 1];
                    $distance = $this->haversineGreatCircleDistance($etape->latitude, $etape->longitude, $nextEtape->latitude, $nextEtape->longitude);
                    $duree = ($distance / 5) * 3600;
    
                    $etape->update([
                        'distance' => $distance,
                        'duree' => $duree,
                    ]);
                } else {
                    $etape->update([
                        'distance' => 0,
                        'duree' => 0,
                    ]);
                }
            }
        }

        private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371) {
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);
    
            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;
    
            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        }

        


}