<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;


return new class extends Migration {
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('password')
                ->default(Hash::make('123456'))
                ->after('curp');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};

