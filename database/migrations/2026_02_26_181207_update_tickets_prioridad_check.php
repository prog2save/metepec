<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Quitar constraint viejo
        DB::statement("ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_prioridad_check");

        // Crear constraint nuevo con Urgente
        DB::statement("
            ALTER TABLE tickets
            ADD CONSTRAINT tickets_prioridad_check
            CHECK (prioridad IN ('Baja','Media','Alta','Urgente'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_prioridad_check");

        DB::statement("
            ALTER TABLE tickets
            ADD CONSTRAINT tickets_prioridad_check
            CHECK (prioridad IN ('Baja','Media','Alta'))
        ");
    }
};
