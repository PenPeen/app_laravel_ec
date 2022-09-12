<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

    /**
     * コンストラクタ
     * 
     * ログインユーザのみアクセスを許可
     */
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Item一覧を表示する
     * 
     * 表示条件
     * 1. 販売Shopが開業中
     * 2. 対象製品が販売中
     * 3. 在庫数が１以上
     */
    public function index()
    {
        // 在庫数が１以上のテーブル取得（サブクエリ）
        $stocks = DB::table('t_stocks')
            ->select(
                'product_id',
                DB::raw('sum(quantity) as quantity')
            )->groupBy('product_id')
            ->having('quantity', '>', '0');

        // 条件に合致する製品情報を一覧取得
        $products = DB::table('products')
            ->joinSub($stocks, 'stock', function ($join) {
                $join->on('products.id', '=', 'stock.product_id');
            })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->join('images as image2', 'products.image2', '=', 'image2.id')
            ->join('images as image3', 'products.image3', '=', 'image3.id')
            ->join('images as image4', 'products.image4', '=', 'image4.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select('products.id as id', 'products.name as name', 'products.price', 'products.sort_order as sort_order', 'products.information', 'secondary_categories.name as category', 'image1.filename as filename')
            ->paginate(8);

        // dd($products);

        return view('user.index', compact('products'));
    }

    /**
     * 商品の詳細画面を表示する。
     * 
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.show', compact('product'));
    }
}
