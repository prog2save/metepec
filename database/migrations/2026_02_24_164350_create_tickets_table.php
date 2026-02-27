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
            $table->foreignId('id_ciudadano')
                ->constrained('ciudadanos')
                ->restrictOnDelete();

            $table->foreignId('id_direccion_municipal')
                ->constrained('direccion_municipals')
                ->restrictOnDelete();

            $table->foreignId('id_agente_asignado')
                ->constrained('usuarios')
                ->restrictOnDelete();

            
            $table->string('asunto',200);
            $table->text('descripcion');
            $table->string('tipo_servicio');
            $table->enum('estado',['Abierto','En Proceso', 'Resuelto', 'Cerrado'])->default('Abierto');
            $table->date('fecha_resolucion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable(); //Entrada de API ArcGIS
            $table->decimal('longitud', 10, 7)->nullable(); //Entrada de API ArcGIS
            $table->text('observaciones')->nullable();
            $table->json('adjuntos')->nullable(); // urls/metadatos

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
