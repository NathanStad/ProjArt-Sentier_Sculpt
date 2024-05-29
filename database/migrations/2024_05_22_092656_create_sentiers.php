<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

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