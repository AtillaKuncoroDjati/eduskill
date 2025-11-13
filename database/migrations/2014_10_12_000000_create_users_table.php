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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Identitas dasar
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable()->default('default-avatar.png');

            // Autentikasi & keamanan
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_changed_at')->nullable();

            // OTP / verifikasi tambahan
            $table->boolean('is_otp')->default(false);
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            // Hak akses & status
            $table->enum('permission', ['admin', 'user'])->default('user');
            $table->enum('status', ['aktif', 'nonaktif', 'banned'])->default('aktif');

            // Preferensi notifikasi
            $table->boolean('is_email_notification_enabled')->default(true);
            $table->boolean('is_whatsapp_notification_enabled')->default(true);

            // Aktivitas & sistem
            $table->string('active_device')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
