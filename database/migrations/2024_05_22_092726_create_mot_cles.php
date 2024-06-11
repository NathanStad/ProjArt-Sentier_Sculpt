<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'mot-cles' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - name : nom du mot-clé (string, longueur maximale de 100 caractères)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('mot-cles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mot-cles');
    }
};