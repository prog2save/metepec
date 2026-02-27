<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_estado_check");

        DB::statement("DROP TYPE IF EXISTS estado_enum_new");

        DB::statement("
            DO $$ BEGIN
                CREATE TYPE estado_enum_new AS ENUM ('Nuevo','Abierto','Pendiente','Resuelto');
            EXCEPTION
                WHEN duplicate_object THEN null;
            END $$;
        ");

        DB::statement("
            UPDATE tickets
            SET estado = CASE
                WHEN estado IN ('En Proceso') THEN 'Pendiente'
                WHEN estado IN ('Cerrado') THEN 'Resuelto'
                WHEN estado IN ('Abierto') THEN 'Abierto'
                WHEN estado IN ('Pendiente') THEN 'Pendiente'
                WHEN estado IN ('Resuelto') THEN 'Resuelto'
                WHEN estado IN ('Nuevo') THEN 'Nuevo'
                ELSE 'Nuevo'
            END
        ");

        DB::statement("ALTER TABLE tickets ALTER COLUMN estado DROP DEFAULT");

        DB::statement("
            ALTER TABLE tickets
            ALTER COLUMN estado TYPE estado_enum_new
            USING estado::text::estado_enum_new
        ");

        DB::statement("DROP TYPE IF EXISTS estado_enum");
        DB::statement("ALTER TYPE estado_enum_new RENAME TO estado_enum");

        DB::statement("ALTER TABLE tickets ALTER COLUMN estado SET DEFAULT 'Nuevo'");

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('tipo_ticket', 30)->nullable()->after('tipo_servicio');
        });


        DB::statement("
            DO $$ BEGIN
                ALTER TYPE prioridad ADD VALUE IF NOT EXISTS 'Urgente';
            EXCEPTION
                WHEN undefined_object THEN null;
            END $$;
        ");
    }

    public function down(): void
    {
         Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('tipo_ticket');
        });
    }
};