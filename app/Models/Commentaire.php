<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    /**
     * Modèle représentant un commentaire.
     * Les commentaires sont associés à un sentier spécifique et contiennent l'email, le nom de l'auteur, 
     * ainsi qu'un message.
     */
    
    use HasFactory;

    protected $table = 'commentaires';

    protected $fillable = ['sentier_id', 'email', 'name', 'message'];

    public function sentier() {
        return $this->belongsTo(Sentier::class);
    }
}