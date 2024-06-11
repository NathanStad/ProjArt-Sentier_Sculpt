<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'points_interet' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - nom : nom du point d'intérêt (string, longueur maximale de 100 caractères)
     * - photo : photo du point d'intérêt (longText, nullable)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('points_interet', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->longText('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_interet');
    }
};