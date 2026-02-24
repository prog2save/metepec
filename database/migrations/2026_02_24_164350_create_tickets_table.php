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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            //Llaves foraneas
            $table->foreignId('ciudadano_id')
                ->constrained('ciudadanos')
                ->restrictOnDelete();

            $table->foreignId('direccion_id')
                ->constrained('direccion_municipals')
                ->restrictOnDelete();

            $table->foreignId('agente_id')
                ->constrained('usuarios')
                ->restrictOnDelete();

            
            $table->string('asunto');
            $table->text('descripcion');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
