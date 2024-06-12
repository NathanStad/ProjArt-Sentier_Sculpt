<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    /**
     * Modèle représentant une étape d'un sentier.
     * Chaque étape est associée à un sentier et peut avoir plusieurs points d'intérêt.
     */
    use HasFactory;
    protected $table = 'etapes';

    protected $fillable = ['sentier_id', 'nom', 'description', 'latitude', 'longitude', 'distance', 'duree', 'ordre', 'photo'];

    public function sentier() {
        return $this->belongsTo(Sentier::class);
    }

    public function pointsInteret() {
        return $this->belongsToMany(PointInteret::class, 'etape_point_interet');
    }
}