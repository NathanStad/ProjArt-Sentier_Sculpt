<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sentier;


class MotCle extends Model
{
    /**
     * Modèle représentant un mot-clé.
     * Les mots-clés peuvent être associés à plusieurs sentiers pour les catégoriser.
     */
    
    use HasFactory;
    protected $table = 'mot-cles';

    protected $fillable = ['name'];

    public function sentiers() {
        return $this->belongsToMany(Sentier::class, 'motcle_sentier');
    }
}