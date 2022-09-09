<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Support\Facades\Log;

/**
 * Owner情報の管理に関するコントローラー
 */
class OwnersController extends Controller
{

    /**
     * コンストラクタ
     * 
     * Admin認証状態時のみ、操作を許可
     */
    public function __construct()
    {
        $this->middleware(('auth:admin'));
    }
    /**
     * オーナー一覧を表示する。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // オーナー情報取得（ページネーション）
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(5);

        return view('admin.owners.index', compact('owners'));
    }

    /**
     * オーナー作成画面の表示
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * オーナーの新規登録
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Owner / Shopの作成
        // トランザクション処理
        try {

            DB::transaction(function () use ($request) {

                // オーナー作成
                $owner = Owner::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password'))
                ]);

                // ショップ作成
                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => false
                ]);
            });

            // 例外処理
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return redirect(route('admin.owners.index'))
            ->with([
                'message' => '登録が完了しました。',
                'status' => 'info'
            ]);
    }

    /**
     * Ownerの編集画面を表示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $owner = Owner::findOrFail($id);

        return view('admin.owners.edit', compact('owner'));
    }

    /**
     * オーナー情報の更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション処理
        $request->validate(
            [
                'name' => 'required|string|max:255',
                // Emailは、自身のアドレスは検査対象外
                'email' =>
                [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('owners')->ignore($request->id, 'id')
                ],
                'password' => 'required|string|confirmed|min:8',
            ]
        );

        // レコード挿入
        $owner = Owner::findOrFail($id);
        $owner->name = $request->input('name');
        $owner->email = $request->input('email');
        $owner->password = Hash::make($request->input('password'));
        $owner->save();

        // リダイレクト
        return redirect(route('admin.owners.index'))
            ->with(
                [
                    'message' => 'オーナー情報を更新しました。',
                    'status' => 'update'
                ]
            );
    }

    /**
     * オーナーの削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Owner::findOrFail($id)->delete();

        // リダイレクト+Flashメッセージ
        return redirect(route('admin.owners.index'))
            ->with([
                'message' => 'オーナーを削除しました。',
                'status' => 'delete'
            ]);
    }

    /**
     * 期限切れオーナー一覧表示
     * 
     * @return view
     */
    public function expiredOwnerIndex()
    {
        // 削除扱いオーナー取得
        $expiredOwners = Owner::onlyTrashed()->paginate(5);

        return view('admin.expired-owners', compact('expiredOwners'));
    }

    /**
     * 期限切れオーナー削除
     */
    public function expiredOwnerDestroy($id)
    {
        // 完全に削除
        Owner::onlyTrashed()->findOrFail($id)->ForceDelete();
        return redirect(route('admin.expired-owners.index'));
    }
}
