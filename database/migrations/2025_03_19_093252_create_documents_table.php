<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du fichier
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id'); // Associer à un utilisateur
            $table->integer('size'); // Taille du fichier en Ko
            $table->enum('status', ['en attente', 'validé', 'refusé'])->default('en attente'); // Statut
            $table->timestamps(); // Date d'insertion
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Relation avec l'utilisateur
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
