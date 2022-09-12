<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

/**
 * ユーザーが認証していない場合、特定のページにリダイレクトする
 * 
 * @var $USER_LOGIN
 */
class Authenticate extends Middleware
{
    protected const USER_LOGIN = 'user.login';
    protected const OWNER_LOGIN = 'owner.login';
    protected const ADMIN_LOGIN = 'admin.login';

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {

            if (Route::is('owner.*')) {
                return route(self::OWNER_LOGIN);
            } else if (Route::is('admin.*')) {
                return route(self::ADMIN_LOGIN);
            }
            return route(self::USER_LOGIN);
        }
    }
}
