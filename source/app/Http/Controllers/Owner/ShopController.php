<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

/**
 * Shopコントローラークラス
 */
class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');
    }

    /**
     * 自身が管理しているShopの一覧表示
     */
    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view(
            'owner.shops.index',
            ['shops' => $shops]
        );
    }

    /**
     * Shop情報の編集画面
     */
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);

        return view('owner.shops.edit', compact('shop'));
    }

    /**
     * Shopの更新
     */
    public function update(Request $request, $id)
    {
        $file = $request->file('image');

        // FacadesImage::make($file)->resize(1920, 1080)->encode();

        if (!is_null($file) && $file->isValid()) {
            $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

            $fileName = uniqid(rand() . '_');
            $extension = $file->extension();
            $fileNameToStore = $fileName . '.' . $extension;

            Storage::put('public/shops/' . $fileNameToStore, $resizedImage,);
            // Storage::putFileAs('public/shops/', $resizedImage, $fileNameToStore);
        }
        return redirect(route('owner.shops.index'));
    }
}
