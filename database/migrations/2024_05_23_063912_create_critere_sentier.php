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