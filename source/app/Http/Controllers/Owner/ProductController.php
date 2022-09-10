<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    /**
     *　Constructor
     */
    public function __construct()
    {
        $this->middleware('auth:owners');

        // ミドルウェア
        // 自身が管理するProduct以外は表示できないようにする
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('product');

            if (!is_null($id)) {
                $productOwnerId = (int)Product::findOrFail($id)->shop->owner->id;
                $owner_id = Auth::id();

                if ($owner_id !== $productOwnerId) {
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ownerInfo = Owner::with('shop.product.imageFirst')->where('id', Auth::id())->get();

        return view('owner.products.index', compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name')
            ->get();

        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PrimaryCategory::with('secondaryCategory')->get();

        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::create($request->all());

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity
                ]);
            });
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return redirect(route('owner.products.index'))
            ->with([
                'message' => '商品を登録しました',
                'status' => 'info'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name')
            ->get();

        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PrimaryCategory::with('secondaryCategory')->get();

        return view('owner.products.edit', compact('product', 'quantity', 'shops', 'images', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $request->validate([
            'current_quantity' => 'required|integer'
        ]);

        // 在庫数に変動があった場合
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        if (intval($request->input('current_quantity')) !== $quantity) {
            $id = $request->route()->parameter('product');
            return redirect()
                ->route('owner.products.edit', ['product' => $id])
                ->with([
                    'message' => '在庫数が変更されています。再度確認してください。',
                    'status' => 'alert',
                ])
                ->withInput();
        }

        // DB保存処理（トランザクション処理）
        try {
            DB::transaction(function () use ($request, $id) {
                // products
                $product = Product::findOrFail($id);
                $product->update($request->all());

                // stocks
                if ($request->input('type') === \Constant::PRODUCT_LIST['add']) {
                    // 入庫
                    $quantity = $request->input('quantity');
                } elseif ($request->input('type') === \Constant::PRODUCT_LIST['reduce']) {
                    // 出庫
                    $quantity = $request->input('quantity') * -1;
                }
                Stock::create([
                    'product_id' => $product->id,
                    'type' => $request->input('type'),
                    'quantity' => $quantity,
                ]);

                // }
            });
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }

        // PRG
        return redirect(route('owner.products.index'))
            ->with([
                'message' => '商品を編集しました。',
                'status' => 'info'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect(route('owner.products.index'))
            ->with([
                'message' => '商品を削除しました。',
                'status' => 'alert'
            ]);
    }
}
