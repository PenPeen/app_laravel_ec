<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

/**
 * ImageController
 * 
 * Imageクラスに関する捜査を実施する。
 */
class ImageController extends Controller
{
    /**
     *　Constructor
     */
    public function __construct()
    {
        $this->middleware('auth:owners');

        // ミドルウェア
        // 自身が管理するImage以外は表示できないようにする
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('image');

            if (!is_null($id)) {
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $owner_id = Auth::id();
                $image_id = (int)$imagesOwnerId;

                if ($owner_id !== $image_id) {
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    /**
     * Index 画像一覧の表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        return view(
            'owner.images.index',
            ['images' => $images]
        );
    }

    /**
     * 画像の作成画面表示
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * 画像の作成処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        // ファイルの取得
        $formFiles = $request->file('files');
        if (!is_null($formFiles)) {

            // 複数ファイルに対応（ name="files[image][]" )
            foreach ($formFiles['image'] as $imageFile) {
                // 画像ファイルの保存
                $fileNameToStore = ImageService::upload($imageFile, 'products');

                // 画像レコードの作成
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore,
                ]);
            }

            // リダイレクト Flashメッセージの表示
            return redirect(route('owner.images.index'))
                ->with([
                    'message' => '画像登録を実施いたしました。',
                    'status' => 'info'
                ]);;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);

        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // バリデーション
        $request->validate(
            ['title' => 'string|max:50',]
        );

        // DB保存
        $image = Image::findOrFail($id);
        $image->title = $request->input('title');
        $image->save();

        return redirect(route('owner.images.index'))
            ->with([
                'message' => '画像タイトルの編集が完了しました。',
                'status' => 'info'
            ]);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Storageにファイルが存在する場合は削除する。
        $image = Image::findOrFail($id);
        $storagePath = 'public/products/' . $image->filename;
        if (Storage::exists($storagePath)) {
            Storage::delete($storagePath);
        }

        $image->delete();

        return redirect(route('owner.images.index'))
            ->with([
                'message' => '画像を削除しました。',
                'status' => 'delete'
            ]);
    }
}
