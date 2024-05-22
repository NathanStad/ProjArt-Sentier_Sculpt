<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MotCle;
use App\Models\Etape;

class Sentier extends Model
{
    use HasFactory;
    protected $table = 'sentiers';

    protected $fillable = ['nom', 'description', 'duree', 'longueur', 'point_depart', 'point_arrive', 'photo'];

    public function themes() {
        return $this->belongsToMany(MotCle::class, 'motcle_sentier');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }
}