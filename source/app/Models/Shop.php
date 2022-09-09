<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // 更新を許可するカラム
    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    /**
     * Ownerモデルとのリレーション設定 一対一
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Productクラスとのリレーション設定
     */
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
