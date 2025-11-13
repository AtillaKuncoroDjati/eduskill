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
        Schema::create('kursuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('thumbnail')->nullable();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->enum('category', ['programming', 'design', 'marketing', 'business', 'cybersecurity']);
            $table->enum('difficulty', ['pemula', 'menengah', 'lanjutan']);
            $table->boolean('certificate')->default(false);
            $table->enum('status', ['aktif', 'nonaktif', 'arsip'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursuses');
    }
};
