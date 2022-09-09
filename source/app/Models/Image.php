<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model
{
    use HasFactory;

    // 更新を許可するカラム
    protected $fillable = [
        'owner_id',
        'filename',
        'title'
    ];

    /**
     * Ownerモデルとのリレーション設定 一対多
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
