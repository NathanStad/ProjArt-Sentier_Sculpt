<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;
    
    protected $table = 'criteres';

    protected $fillable = ['name'];

    public function sentiers()
    {
        return $this->belongsToMany(Sentier::class, 'critere_sentier');
    }
}