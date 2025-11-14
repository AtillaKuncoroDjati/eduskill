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
        Schema::create('materis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kursus_id');
            $table->foreign('kursus_id')->references('id')->on('kursuses')->onDelete('cascade');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->boolean('is_quiz')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
