<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP INDEX IF EXISTS ciudadanos_email_unique_ci');

        Schema::table('ciudadanos', function (Blueprint $table) {
            $table->dropUnique(['email']); 
        });
    }

    public function down(): void
    {
        Schema::table('ciudadanos', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};