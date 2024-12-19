<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'address',
        'state',
        'profile_picture',
        'first_login',
        'last_login',
        'is_admin',
    ];

    const STATE_ACTIVE = 'Active';
    const STATE_BLOCKED = 'Blocked';
    const STATE_BANNED = 'Banned';
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
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'author');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller');
    }
    
    public function isSeller(): bool
    {
        return \DB::table('Product')->where('seller', $this->id)->exists();
    }

    public function countProducts(): int
    {
        return \DB::table('Product')
            ->where('seller', $this->id)
            ->count();
    }

    public function isBlocked()
    {
        return $this->state === self::STATE_BLOCKED;
    }

    public function isBanned()
    {
        return $this->state === self::STATE_BANNED;
    }

}
