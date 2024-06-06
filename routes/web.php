<?php

use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\CritereController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\MotClesController;
use App\Http\Controllers\POIController;
use App\Http\Controllers\SentierController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Récupération de données
Route::get('/data-sentiers', [SentierController::class, 'index']);
Route::get("/data-sentier-{id}", [SentierController::class, 'show']);
Route::get("/data-step-{id}", [EtapeController::class, 'show']);
Route::get("/data-sentier/prefere-incr-{id}", [SentierController::class, 'incrCompteur']);
Route::get('/data-sentiers/prefere', [SentierController::class, 'topClickedSentiers']);
Route::get('/data-theme', [ThemeController::class, 'index']);
Route::get('/data-motcles', [MotClesController::class, 'index']);
Route::get('/data-critere', [CritereController::class, 'index']);
Route::get('/data-poi', [POIController::class, 'index']);
Route::get('/commentaire', [CommentaireController::class, 'store']);

// Vérification de compte et login
Route::get('/data-user-{id}', [UserController::class, 'show']);
Route::get('/data-sentiers/by-user-{userId}', [SentierController::class, 'showByUser']);
Route::post('/checkdata-user', [UserController::class, 'authenticate']);
Route::post('/insertdata-user', [UserController::class, 'store']);
Route::patch('/set-sentier/{id}/archive', [SentierController::class, 'toggleArchive']);
Route::delete('/delete-sentier/{id}', [SentierController::class, 'destroy']);

// Insertion de données
Route::post('/submit-sentier', [SentierController::class, 'store']);
Route::post('/update-sentier', [SentierController::class, 'update']);

// Insertion de fichier
Route::post('/submit-file-sentier', [SentierController::class, 'uploadFile']);
Route::post('/submit-file-poi', [POIController::class, 'uploadFile']);
Route::post('/submit-file-etapes', [EtapeController::class, 'uploadFile']);

// Faire que tous les liens ammènes à l'application
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


