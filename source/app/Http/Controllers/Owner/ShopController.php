<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

/**
 * Shopコントローラークラス
 */
class ShopController extends Controller
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth:owners');
    }

    /**
     * Index 自身が管理しているShopの一覧表示
     */
    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->get();
        return view('owner.shops.index', compact('shops'));
    }

    /**
     * edit Shop情報の編集画面表示
     */
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }

    /**
     * Shopの更新
     */
    public function update(UploadImageRequest $request, $id)
    {
        // バリデーション
        $request->validate(
            [
                'name' => 'required|string|max:50',
                'information' => 'required|string|max:1000',
                'is_selling' => 'required|integer|between:0,1'
            ]
        );

        // 画像処理
        $file = $request->file('image');
        if (!is_null($file) && $file->isValid()) {
            // サービスクラス呼び出し（ファットコントローラー対策）
            $fileNameToStore = ImageService::upload($file, 'shops');
        }

        // DB保存
        $shop = Shop::findOrFail($id);
        $shop->name = $request->input('name');
        $shop->information = $request->input('information');
        $shop->is_selling = $request->input('is_selling');

        // 画像が設定されている場合
        if (!is_null($file) && $file->isValid()) {
            $shop->filename = $fileNameToStore;
        }

        $shop->save();

        // リダイレクト処理 Flashメッセージ表示
        return redirect(route('owner.shops.index'))
            ->with([
                'message' => '登録が完了しました。',
                'status' => 'info'
            ]);;
    }
}
