<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    protected $fillable = ['sentier_id', 'email', 'name', 'message'];

    public function sentier() {
        return $this->belongsTo(Sentier::class);
    }
}