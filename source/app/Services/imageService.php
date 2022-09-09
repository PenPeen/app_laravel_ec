<?php

namespace App\Services;

use InterventionImage;
use Illuminate\Support\Facades\Storage;

/**
 * Serviceクラス
 * 
 * ファットコントローラー対策
 */
class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        $file = $imageFile;

        // ファイルのリサイズ処理
        // PHP InterventionImage・GDライブラリ使用
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

        // 一意なファイル名生成
        $fileName = uniqid(rand() . '_');
        $extension = $file->extension();
        $fileNameToStore = $fileName . '.' . $extension;

        // ファイル保存
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage,);

        // 作成したファイル名を返す
        return $fileNameToStore;
    }
}
