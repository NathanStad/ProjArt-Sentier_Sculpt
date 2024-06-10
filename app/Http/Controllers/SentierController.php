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

class SentierController extends Controller
{
    public function index()
    {
        $sentiers = Sentier::with(['etapes', 'commentaires', 'theme', 'criteres', 'motcles', 'user', 'difficulte'])->get();

        return response()->json($sentiers);
    }

    public function show($id)
    {
        $sentier = Sentier::with(['etapes', 'commentaires', 'theme', 'criteres', 'motcles', 'user', 'difficulte'])->find($id);

        if ($sentier) {
            return response()->json($sentier);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function store(SentierStoreRequest $request)
    {

            try {
                // Créer le sentier
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
                    'user_id' => $request->user_id,
                    'difficulte_id' => $request->difficulte_id,
                ]);

                // Créer les étapes
                foreach ($request->etapes as $etapeData) {
                    $etape = Etape::create([
                        'sentier_id' => $sentier->id,
                        'nom' => $etapeData['nom'],
                        'description' => $etapeData['description'],
                        'latitude' => $etapeData['latitude'],
                        'longitude' => $etapeData['longitude'],
                        'distance' => $etapeData['distance'],
                        'duree' => $etapeData['duree'],
                        'ordre' => $etapeData['ordre'],
                        'photo' => $etapeData['photo'],
                    ]);

                    // Ajouter les points d'intérêt
                    if (isset($etapeData['points_interet'])) {
                        foreach ($etapeData['points_interet'] as $poiData) {
                            $pointInteret = PointInteret::create([
                                'nom' => $poiData['nom'],
                                'photo' => $poiData['photo'] ?? null,
                            ]);

                            $etape->pointsInteret()->attach($pointInteret->id);
                        }
                    }
                }

                // Attacher les critères
                if ($request->has('criteres')) {
                    $criteresIds = CritereController::transformToArray($request->criteres);
                    $criteres = Critere::whereIn('id', $criteresIds)->get();
                    $sentier->criteres()->attach($criteres);
                }

                // Attacher les mots-clés
                if ($request->has('motcles')) {
                    $motclesIds = MotClesController::transformToArray($request->motcles);
                    $motcles = MotCle::whereIn('id', $motclesIds)->get();
                    $sentier->motcles()->attach($motcles);
                }

                return response()->json($sentier, 201);
            } catch (\Exception $e) {
                // Renvoyer une réponse avec les détails de l'erreur
                return response()->json([
                    'message' => 'Une erreur est survenue lors de la création du sentier.',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
    }

    public function update(SentierUpdateRequest $request, $id)
    {            $sentier = Sentier::find($id);

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

            // Mettre à jour les étapes
            $existingEtapes = $sentier->etapes->keyBy('id');
            foreach ($request->etapes as $etapeData) {
                if (isset($etapeData['id']) && $existingEtapes->has($etapeData['id'])) {
                    $etape = $existingEtapes->get($etapeData['id']);
                    $etape->update([
                        'nom' => $etapeData['nom'],
                        'description' => $etapeData['description'],
                        'latitude' => $etapeData['latitude'],
                        'longitude' => $etapeData['longitude'],
                        'distance' => $etapeData['distance'],
                        'duree' => $etapeData['duree'],
                        'ordre' => $etapeData['ordre'],
                        'photo' => $etapeData['photo'],
                    ]);
                } else {
                    $etape = Etape::create([
                        'sentier_id' => $sentier->id,
                        'nom' => $etapeData['nom'],
                        'description' => $etapeData['description'],
                        'latitude' => $etapeData['latitude'],
                        'longitude' => $etapeData['longitude'],
                        'distance' => $etapeData['distance'],
                        'duree' => $etapeData['duree'],
                        'ordre' => $etapeData['ordre'],
                        'photo' => $etapeData['photo'],
                    ]);
                }

                // Mettre à jour les points d'intérêt
                if (isset($etapeData['points_interet'])) {
                    $existingPointsInteret = $etape->pointsInteret->keyBy('id');
                    foreach ($etapeData['points_interet'] as $poiData) {
                        if (isset($poiData['id']) && $existingPointsInteret->has($poiData['id'])) {
                            $pointInteret = $existingPointsInteret->get($poiData['id']);
                            $pointInteret->update([
                                'nom' => $poiData['nom'],
                                'photo' => $poiData['photo'] ?? $pointInteret->photo,
                            ]);
                        } else {
                            $pointInteret = PointInteret::create([
                                'nom' => $poiData['nom'],
                                'photo' => $poiData['photo'] ?? null,
                            ]);

                            $etape->pointsInteret()->attach($pointInteret->id);
                        }
                    }
                }
            }

            // Mettre à jour les critères
            if ($request->has('criteres')) {
                $criteresIds = CritereController::transformToArray($request->criteres);
                $criteres = Critere::whereIn('id', $criteresIds)->get();
                $sentier->criteres()->sync($criteres);
            }

            // Mettre à jour les mots-clés
            if ($request->has('motcles')) {
                $motclesIds = MotClesController::transformToArray($request->motcles);
                $motcles = MotCle::whereIn('id', $motclesIds)->get();
                $sentier->motcles()->sync($motcles);
            }

            return response()->json($sentier, 200);
    }

    public function destroy($id)
    {
        $sentier = Sentier::find($id);

        if ($sentier) {
            $sentier->delete();
            return response()->json(['message' => 'Sentier deleted'], 200);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function incrCompteur($id)
    {
        $sentier = Sentier::find($id);

        if ($sentier) {
            $sentier->increment('compteur');
            return response()->json(['message' => 'Compteur incremented'], 200);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function topClickedSentiers()
    {
        $sentiers = Sentier::orderBy('compteur', 'desc')->take(3)->get();

        return response()->json($sentiers);
    }

    public function showByUser($userId)
    {
        $sentiers = Sentier::with(['etapes', 'commentaires', 'theme', 'criteres', 'motcles', 'user', 'difficulte'])
            ->where('user_id', $userId)
            ->get();

        if ($sentiers->isNotEmpty()) {
            return response()->json($sentiers);
        } else {
            return response()->json(['message' => 'Aucun sentier trouvé pour cet utilisateur'], 404);
        }
    }

    public function toggleArchive($id)
    {
        $sentier = Sentier::find($id);

        if ($sentier) {
            $sentier->archive = !$sentier->archive;
            $sentier->save();
            return response()->json(['message' => 'Archive status toggled', 'archive' => $sentier->archive], 200);
        } else {
            return response()->json(['message' => 'Sentier not found'], 404);
        }
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('photoSentier')) {
            $file = $request->file('photoSentier');
            $path = $file->store('sentiers', 'public');
            $fileName = $file->hashName(); // Obtenez le nom du fichier généré
            return response()->json(['message' => 'Sentier created successfully!', 'path' => $path, 'fileName' => $fileName]);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }
}

