<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $type
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function initialize(): void
    {
        $time = date('Y-m-d H:i:s');
        self::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@' . env('APP_DOMAIN'),
                'email_verified_at' => $time,
                'password' => password_hash(12345678, PASSWORD_DEFAULT),
                'remember_token' => bin2hex(openssl_random_pseudo_bytes(32)),
                'type' => 'admin',
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'Garçom',
                'email' => 'garcom@' . env('APP_DOMAIN'),
                'email_verified_at' => $time,
                'password' => password_hash(12345678, PASSWORD_DEFAULT),
                'remember_token' => bin2hex(openssl_random_pseudo_bytes(32)),
                'type' => 'garcom',
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }
}
