<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table pivot 'critere_sentier' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - sentier_id : référence étrangère vers la table 'sentiers' (foreignId)
     * - critere_id : référence étrangère vers la table 'criteres' (foreignId)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('critere_sentier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sentier_id')->constrained('sentiers')->onDelete('cascade');
            $table->foreignId('critere_id')->constrained('criteres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('critere_sentier');
    }
};