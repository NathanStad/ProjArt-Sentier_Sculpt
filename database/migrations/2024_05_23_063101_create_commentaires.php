<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'commentaires' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - email : adresse email de l'auteur du commentaire (string, longueur maximale de 255 caractères)
     * - name : nom de l'auteur du commentaire (string, longueur maximale de 100 caractères)
     * - message : contenu du commentaire (longText, longueur maximale de 1000 caractères)
     * - sentier_id : référence étrangère vers la table 'sentiers' (foreignId)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255);
            $table->string('name', 100);
            $table->longText('message', 1000);
            $table->foreignId('sentier_id')->constrained('sentiers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};