<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    /**
     * Modèle représentant un critère.
     * Les critères peuvent être associés à plusieurs sentiers pour définir des caractéristiques spécifiques.
     */
    use HasFactory;
    
    protected $table = 'criteres';

    protected $fillable = ['name', 'icone'];

    public function sentiers()
    {
        return $this->belongsToMany(Sentier::class, 'critere_sentier');
    }
}