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
        Schema::create('ciudadanos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');

            $table->string('telefono_principal');
            $table->string('telefono_alterno')->nullable();
            $table->string('email')->nullable();

            $table->string('direccion_calle');
            $table->string('direccion_numero')->nullable();
            $table->string('direccion_colonia');

            $table->decimal('latitud', 10, 7)->nullable(); //Entrada de API ArcGIS
            $table->decimal('longitud', 10, 7)->nullable(); //Entrada de API ArcGIS

            $table->json('historial_interacciones')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudadanos');
    }
};
