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
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('role');
            $table->char('district_id', 7)->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('phone');
            $table->index('role');
            $table->index('status');
            $table->index('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
