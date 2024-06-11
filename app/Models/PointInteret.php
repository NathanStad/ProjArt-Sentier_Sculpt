<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointInteret extends Model
{
    /**
     * Modèle représentant un point d'intérêt.
     * Les points d'intérêt peuvent être associés à plusieurs étapes.
     */
    use HasFactory;
    protected $table = 'points_interet';

    protected $fillable = ['nom', 'photo'];

    public function etapes() {
        return $this->belongsToMany(Etape::class, 'etape_point_interet');
    }
}