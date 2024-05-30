<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sentier;
use App\Models\Etape;
use App\Models\Critere;
use App\Models\MotCle;
use App\Models\PointInteret;
use App\Http\Requests\SentierStoreRequest;
use App\Http\Requests\SentierUpdateRequest;

class SentierController extends Controller {
    
    public function index() {
        $sentiers = Sentier::with(['etapes', 'commentaires', 'theme', 'criteres', 'motcles', 'user', 'difficulte'])->get();
        
        return response()->json($sentiers);
    }

    public function show($id) {
        $sentier = Sentier::with(['etapes', 'commentaires', 'theme', 'criteres', 'motcles', 'user', 'difficulte'])->find($id);
        
        if ($sentier) {
            return response()->json($sentier);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function store(SentierStoreRequest $request) {
        $user = Auth::user();
        
        if ($user && $user->role === 'institution') {
            $sentier = Sentier::create([
                'nom' => $request->nom,
                'description' => $request->description,
                'duree' => $request->duree,
                'longueur' => $request->longueur,
                'localisation' => $request->localisation,
                'compteur' => 0,
                'archive' => 0,
                'photo' => $request->photo,
                'theme_id' => $request->theme_id,
                'user_id' => $user->id,
                'difficulte_id' => $request->difficulte_id,
            ]);

            // Attacher les critères
            if ($request->has('criteres')) {
                $criteres = Critere::whereIn('id', $request->criteres)->get();
                $sentier->criteres()->attach($criteres);
            }

            // Attacher les mots-clés
            if ($request->has('motcles')) {
                $motcles = MotCle::whereIn('id', $request->motcles)->get();
                $sentier->motcles()->attach($motcles);
            }

            return response()->json($sentier, 201);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function update(SentierUpdateRequest $request, $id) {
        $user = Auth::user();
        
        if ($user && $user->role === 'institution') {
            $sentier = Sentier::find($id);
            
            if (!$sentier) {
                return response()->json(['message' => 'Sentier not found'], 404);
            }

            $sentier->update([
                'nom' => $request->nom,
                'description' => $request->description,
                'duree' => $request->duree,
                'longueur' => $request->longueur,
                'localisation' => $request->localisation,
                'photo' => $request->photo,
                'theme_id' => $request->theme_id,
                'difficulte_id' => $request->difficulte_id,
                'archive' => $request->archive ?? 0,
            ]);

            // Mettre à jour les critères
            if ($request->has('criteres')) {
                $criteres = Critere::whereIn('id', $request->criteres)->get();
                $sentier->criteres()->sync($criteres);
            }

            // Mettre à jour les mots-clés
            if ($request->has('motcles')) {
                $motcles = MotCle::whereIn('id', $request->motcles)->get();
                $sentier->motcles()->sync($motcles);
            }

            return response()->json($sentier, 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function incrCompteur($id) {
        $sentier = Sentier::find($id);
        
        if ($sentier) {
            $sentier->increment('compteur');
            return response()->json(['message' => 'Compteur incremented'], 200);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function topClickedSentiers() {
        $sentiers = Sentier::orderBy('compteur', 'desc')->take(3)->get();
        
        return response()->json($sentiers);
    }
    
    public function destroy($id) {
        $user = Auth::user();
        
        if ($user && $user->role === 'institution') {
            $sentier = Sentier::find($id);
            
            if (!$sentier) {
                return response()->json(['message' => 'Sentier not found'], 404);
            }

            $sentier->delete();
            return response()->json(['message' => 'Sentier deleted'], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}