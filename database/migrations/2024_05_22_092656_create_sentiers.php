<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sentiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->longText('description');
            $table->integer('duree');
            $table->float('longueur');
            $table->string('point_depart', 50);
            $table->string('point_arrive', 50);
            $table->longText('photo')->nullable();
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