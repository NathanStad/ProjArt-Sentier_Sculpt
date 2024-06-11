<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table pivot 'etape_point_interet' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - etape_id : référence étrangère vers la table 'etapes' (foreignId)
     * - point_interet_id : référence étrangère vers la table 'points_interet' (foreignId)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('etape_point_interet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etape_id')->constrained('etapes')->onDelete('cascade');
            $table->foreignId('point_interet_id')->constrained('points_interet')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape_point_interet');
    }
};