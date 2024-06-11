<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'sentiers' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - nom : nom du sentier (string, longueur maximale de 100 caractères)
     * - description : description du sentier (longText)
     * - duree : durée estimée du parcours (integer)
     * - longueur : longueur du sentier en kilomètres (float)
     * - localisation : localisation du sentier (string, longueur maximale de 150 caractères)
     * - compteur : compteur de fréquentation (integer)
     * - archive : statut d'archivage (boolean)
     * - photo : photo du sentier (longText, nullable)
     * - theme_id : référence étrangère vers la table 'themes' (foreignId)
     * - user_id : référence étrangère vers la table 'users' (foreignId)
     * - difficulte_id : référence étrangère vers la table 'difficultes' (foreignId)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */

    public function up(): void
    {
        Schema::create('sentiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->longText('description');
            $table->integer('duree');
            $table->float('longueur');
            $table->string('localisation', 150);
            $table->integer('compteur');
            $table->boolean('archive');
            $table->longText('photo')->nullable();
            $table->foreignId('theme_id')->constrained('themes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('difficulte_id')->constrained('difficultes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiers');
    }
};