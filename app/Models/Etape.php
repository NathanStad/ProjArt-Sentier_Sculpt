<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;
    protected $table = 'etapes';

    protected $fillable = ['sentier_id', 'nom', 'description', 'latitude', 'longitude', 'ordre', 'photo'];

    public function sentier() {
        return $this->belongsTo(Sentier::class);
    }

    public function pointsInteret() {
        return $this->belongsToMany(PointInteret::class, 'etape_point_interet');
    }
}