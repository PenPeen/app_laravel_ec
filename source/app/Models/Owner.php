<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * 更新を許可するカラム
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

    // リレーション設定 Shop
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // リレーション設定 Image
    public function image()
    {
        return $this->HasMany(Image::class);
    }
}
