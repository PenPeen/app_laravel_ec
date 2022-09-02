<?php

namespace App\Services;

use InterventionImage;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function upload($file, $folderName)
    {
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

        $fileName = uniqid(rand() . '_');
        $extension = $file->extension();
        $fileNameToStore = $fileName . '.' . $extension;

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage,);

        // 作成したファイル名を返す
        return $fileNameToStore;
    }
}
