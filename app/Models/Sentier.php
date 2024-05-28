<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MotCle;
use App\Models\Etape;
use App\Models\User;
use App\Models\Difficulte;

class Sentier extends Model
{
    use HasFactory;
    protected $table = 'sentiers';

    protected $fillable = ['nom', 'description', 'duree', 'longueur', 'point_depart', 'point_arrive', 'photo', 'theme_id', 'user_id', 'difficulte_id'];

    public function motcles() {
        return $this->belongsToMany(MotCle::class, 'motcle_sentier');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function criteres()
    {
        return $this->belongsToMany(Critere::class, 'critere_sentier');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function difficulte()
    {
        return $this->belongsTo(Difficulte::class);
    }
}