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