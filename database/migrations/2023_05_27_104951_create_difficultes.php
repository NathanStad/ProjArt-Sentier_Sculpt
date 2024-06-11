<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'difficultes' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - graduation : niveau de difficulté (integer)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('difficultes', function (Blueprint $table) {
            $table->id();
            $table->integer('graduation');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('difficultes');
    }
};