<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentaireRequest;

class CommentaireController extends Controller {
    public function index() {
        $commentaires = Commentaire::all();
        return response()->json($commentaires);
    }

    public function getBySentier($sentier_id) {
        $commentaires = Commentaire::where('sentier_id', $sentier_id)->get();
        return response()->json($commentaires);
    }

    public function store(CommentaireRequest $request) {

        $commentaire = Commentaire::create([
            'sentier_id' => $request->sentier_id,
            'email' => $request->email,
            'name' => $request->name,
            'message' => $request->message,
        ]);

        return response()->json($commentaire, 201);
    }
}