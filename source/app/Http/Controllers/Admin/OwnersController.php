<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Support\Facades\Log;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(5);

        return view(
            'admin.owners.index',
            [
                'owners' => $owners
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
        ]);

        try {

            DB::transaction(function () use ($request) {

                $owner = Owner::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password'))
                ]);

                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => false
                ]);
            });
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $owner = Owner::findOrFail($id);

        return view(
            'admin.owners.edit',
            [
                'owner' => $owner
            ]
        );
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
        $request->validate(
            [
                'name' => 'required|string|max:255',
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


        $owner = Owner::findOrFail($id);
        $owner->name = $request->input('name');
        $owner->email = $request->input('email');
        $owner->password = Hash::make($request->input('password'));

        $owner->save();

        return redirect(route('admin.owners.index'))
            ->with(
                [
                    'message' => 'オーナー情報を更新しました。',
                    'status' => 'update'
                ]
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Owner::findOrFail($id)->delete();

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

        return view(
            'admin.expired-owners',
            ['expiredOwners' => $expiredOwners]
        );
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
