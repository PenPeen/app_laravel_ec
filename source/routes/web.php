<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth:users')
    ->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('items.index');
        Route::get('/show/{item}', [ItemController::class, 'show'])->name('items.show');
    });

require __DIR__ . '/auth.php';
