<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentier extends Model
{
    use HasFactory;
    protected $table = 'sentiers';

    protected $fillable = ['nom', 'description', 'duree', 'longueur', 'point_depart', 'point_arrive', 'photo'];

    public function themes() {
        return $this->belongsToMany(Theme::class, 'sentier_theme');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }
}