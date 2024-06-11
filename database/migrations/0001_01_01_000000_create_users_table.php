<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

     /**
    * Run the migrations.
    *
    * Cette fonction crée la table 'users' avec les colonnes suivantes :
    * - id : clé primaire auto-incrémentée
    * - name : nom de l'utilisateur (string, longueur maximale de 50 caractères)
    * - email : adresse email de l'utilisateur (string, longueur maximale de 255 caractères, unique)
    * - email_verified_at : timestamp de vérification de l'email (nullable)
    * - password : mot de passe de l'utilisateur (string)
    * - role : rôle de l'utilisateur (string, longueur maximale de 50 caractères)
    * - photo : photo de l'utilisateur (longText, nullable)
    * - remember_token : token pour la fonctionnalité "Remember Me"
    * - timestamps : colonnes 'created_at' et 'updated_at' pour les timestamps automatiques
    *
    * @return void
    */


    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 50);
            $table->longText('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};