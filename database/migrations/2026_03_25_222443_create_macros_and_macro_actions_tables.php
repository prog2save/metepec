<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('macros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->constrained('usuarios');
            $table->timestamps();
        });

        Schema::create('macro_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('macro_id')->constrained()->cascadeOnDelete();
            $table->string('field'); 
            $table->text('value')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('macro_actions');
        Schema::dropIfExists('macros');
    }
};