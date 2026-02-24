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
        Schema::table('ciudadanos', function (Blueprint $table) {
            // Estatus: ACTIVO / SUSPENDIDO
            $table->string('estatus', 20)->default('ACTIVO')->after('historial_interacciones');

            // Para ordenar por "Última interacción"
            $table->timestamp('ultima_interaccion_at')->nullable()->after('estatus');

            // Índices / anti-duplicados
            $table->unique('telefono_principal');

            // Email: si quieres anti-duplicados cuando exista, usa unique.
            $table->unique('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciudadanos', function (Blueprint $table) {
            $table->dropUnique(['telefono_principal']);
            $table->dropUnique(['email']);

            $table->dropColumn(['estatus', 'ultima_interaccion_at']);
        });
    }
};
