<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'criteres' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - name : nom du critère (string, longueur maximale de 50 caractères)
     * - icone : icône représentant le critère (longText, nullable)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void {
        Schema::create('criteres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->longtext('icone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteres');
    }
};