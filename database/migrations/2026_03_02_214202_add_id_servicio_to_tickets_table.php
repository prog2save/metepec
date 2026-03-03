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
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('id_servicio')->nullable()->after('id_direccion_municipal');

            $table->foreign('id_servicio')
                ->references('id')
                ->on('servicios')
                ->nullOnDelete(); // si borran servicio, deja null el ticket

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['id_servicio']);
            $table->dropColumn('id_servicio');
        });
    }
};
