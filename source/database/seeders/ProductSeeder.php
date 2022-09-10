<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('products')->insert([
            [
                'shop_id' => 1,
                'name' => '製品A',
                'information' => '素晴らしい製品',
                'price' => 1000,
                'is_selling' => 0,
                'secondary_category_id' => 1,
                'image1' => 1
            ],
            [
                'shop_id' => 1,
                'name' => '製品B',
                'information' => '大変素晴らしい製品',
                'price' => 1500,
                'is_selling' => 1,
                'secondary_category_id' => 2,
                'image1' => 2
            ],
            [
                'shop_id' => 1,
                'name' => '製品C',
                'information' => 'いい製品',
                'price' => 750,
                'is_selling' => 0,
                'secondary_category_id' => 3,
                'image1' => 4
            ],
            [
                'shop_id' => 1,
                'name' => '製品D',
                'information' => '素晴らしい製品',
                'price' => 1000,
                'is_selling' => 0,
                'secondary_category_id' => 4,
                'image1' => 3
            ],
            [
                'shop_id' => 1,
                'name' => '製品E',
                'information' => '素晴らしい製品',
                'price' => 10000,
                'is_selling' => 0,
                'secondary_category_id' => 5,
                'image1' => null
            ],
            [
                'shop_id' => 2,
                'name' => '製品F',
                'information' => '素晴らしい製品',
                'price' => 100,
                'is_selling' => 0,
                'secondary_category_id' => 5,
                'image1' => null
            ],
        ]);
    }
}
