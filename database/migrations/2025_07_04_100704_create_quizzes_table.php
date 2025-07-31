<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable()->default(30);
            $table->integer('note_reussite');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('formateur_id')->constrained()->onDelete('cascade');
            // Période de disponibilité
            $table->dateTime('disponible_du')->nullable();
            $table->dateTime('disponible_jusquau')->nullable();
            // La disponibilité du quiz aux étudiants -> le verrouillage de la modification côté formateur
            $table->boolean('est_actif')->default(false);
            $table->boolean('archived')->default(false); // champ pour archiver le quiz

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
