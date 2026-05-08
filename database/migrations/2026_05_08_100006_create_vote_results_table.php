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
        Schema::create('vote_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polling_station_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('party_votes');
            $table->integer('total_votes');
            $table->integer('dpt');
            $table->integer('voters_present');
            $table->foreignId('submitted_by')->constrained('users');
            $table->timestamp('submitted_at');
            $table->boolean('verified')->default(false);
            $table->timestamps();

            $table->index('submitted_by');
            $table->index('verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_results');
    }
};
