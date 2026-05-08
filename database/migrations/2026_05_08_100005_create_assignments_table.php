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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polling_station_id')->constrained()->cascadeOnDelete();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->string('confirmation_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['polling_station_id', 'officer_id']);
            $table->index('polling_station_id');
            $table->index('officer_id');
            $table->index('confirmation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
