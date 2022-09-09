<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Shopsテーブル Seeder処理
 */
class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('shops')
            ->insert([
                [
                    'owner_id' => '1',
                    'name' => '店名A',
                    'information' => 'お店の説明！お店の説明だよ！！！',
                    'filename' => '',
                    'is_selling' => true
                ],
                [
                    'owner_id' => '2',
                    'name' => '店名A',
                    'information' => 'お店の説明！お店の説明だよ！！！',
                    'filename' => '',
                    'is_selling' => true
                ],
            ]);
    }
}
