<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'password',
        'email_verified_at',
        'password_changed_at',
        'is_otp',
        'otp_code',
        'otp_expires_at',
        'permission',
        'status',
        'is_email_notification_enabled',
        'is_whatsapp_notification_enabled',
        'active_device',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'is_otp' => 'boolean',
        'is_email_notification_enabled' => 'boolean',
        'is_whatsapp_notification_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'avatar' => 'default-avatar.png',
        'permission' => 'user',
        'status' => 'aktif',
        'is_email_notification_enabled' => true,
        'is_whatsapp_notification_enabled' => true,
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function contentProgress()
    {
        return $this->hasMany(UserContentProgress::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    /**
     *  Cek apakah pengguna telah mendaftar pada kursus tertentu.
     *
     * @param  int  $kursusId
     * @return bool
     */
    public function hasEnrolled($kursusId)
    {
        return $this->enrolledCourses()->where('kursus_id', $kursusId)->exists();
    }

    /**
     * Mengambil data pendaftaran kursus tertentu untuk pengguna ini.
     *
     * @param  int  $kursusId
     * @return \App\Models\UserCourse|null
     */
    public function getEnrolledCourse($kursusId)
    {
        return $this->enrolledCourses()->where('kursus_id', $kursusId)->first();
    }
}
