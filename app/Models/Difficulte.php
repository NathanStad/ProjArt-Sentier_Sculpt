<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sentier;

class Difficulte extends Model
{
    use HasFactory;

    protected $table = 'difficultes';

    protected $fillable = ['graduation'];

    public function sentiers()
    {
        return $this->hasMany(Sentier::class);
    }
}