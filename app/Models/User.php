<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi lewat mass assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Penting! untuk sistem role user/admin
    ];

    /**
     * Kolom yang tidak boleh ditampilkan ke API.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Kirim link reset password ke email user.
     */
    public function sendPasswordResetNotification($token)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }
}

