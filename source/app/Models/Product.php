<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    /**
     * Shopモデルとのリレーション設定：多
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * SecondaryCategoryモデルとのリレーション設定：多
     */
    public function secondaryCategory()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    /**
     * Imageモデルとのリレーション設定 ：多
     */
    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

    /**
     * Imageモデルとのリレーション設定 ：多
     */
    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    /**
     * Imageモデルとのリレーション設定 ：多
     */
    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    /**
     * Imageモデルとのリレーション設定 ：多
     */
    public function imageFourth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    /**
     * Stockモデルとのリレーション設定 ：多
     */
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
