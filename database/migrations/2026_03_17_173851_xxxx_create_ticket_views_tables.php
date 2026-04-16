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
        Schema::create('ticket_views', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('visibility', ['all_agents', 'only_me'])->default('all_agents');
            $table->foreignId('created_by')->constrained('usuarios')->cascadeOnDelete();
            $table->integer('position')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ticket_view_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_view_id')->constrained('ticket_views')->cascadeOnDelete();
            $table->enum('match_type', ['all', 'any'])->default('all');
            $table->string('field', 100);
            $table->string('operator', 50);
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_view_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_view_id')->constrained('ticket_views')->cascadeOnDelete();
            $table->string('column_key', 100);
            $table->string('label', 100)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('ticket_view_sorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_view_id')->constrained('ticket_views')->cascadeOnDelete();
            $table->enum('sort_type', ['group_by', 'order_by']);
            $table->string('column_key', 100);
            $table->enum('direction', ['asc', 'desc'])->default('asc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_view_sorts');
        Schema::dropIfExists('ticket_view_columns');
        Schema::dropIfExists('ticket_view_conditions');
        Schema::dropIfExists('ticket_views');
    }
};
