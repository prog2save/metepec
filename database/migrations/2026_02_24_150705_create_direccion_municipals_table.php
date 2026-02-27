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
        Schema::create('direccion_municipals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_direccion');
            $table->string('contacto_principal')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            $table->boolean('estatus')->default(true);

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccion_municipals');
    }
};
