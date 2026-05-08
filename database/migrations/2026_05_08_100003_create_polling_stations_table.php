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
        Schema::create('polling_stations', function (Blueprint $table) {
            $table->id();
            $table->char('village_id', 10)->index();
            $table->char('district_id', 7)->index();
            $table->foreign('village_id')->references('id')->on('villages')->cascadeOnDelete();
            $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
            $table->integer('station_number');
            $table->string('venue_name', 200);
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['village_id', 'station_number']);
            $table->index('status');
            $table->index(['district_id', 'status']);
            $table->index('station_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polling_stations');
    }
};
