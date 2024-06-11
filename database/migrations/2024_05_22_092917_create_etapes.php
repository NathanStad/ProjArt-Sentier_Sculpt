<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette fonction crée la table 'etapes' avec les colonnes suivantes :
     * - id : clé primaire auto-incrémentée
     * - sentier_id : référence étrangère vers la table 'sentiers' (foreignId)
     * - nom : nom de l'étape (string, longueur maximale de 100 caractères)
     * - description : description de l'étape (longText)
     * - latitude : latitude de l'étape (float)
     * - longitude : longitude de l'étape (float)
     * - ordre : ordre de l'étape (integer)
     * - photo : photo de l'étape (longText, nullable)
     * - distance : distance à partir du point précédent (float, valeur par défaut 0)
     * - duree : durée estimée pour atteindre cette étape (integer, valeur par défaut 0)
     * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
     *
     * @return void
     */
    
    public function up(): void
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sentier_id')->constrained('sentiers')->onDelete('cascade');
            $table->string('nom', 100);
            $table->longText('description');
            $table->float('latitude');
            $table->float('longitude');
            $table->integer('ordre');
            $table->longText('photo')->nullable();
            $table->float('distance')->default(0);
            $table->integer('duree')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapes');
    }
};