<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\{
    UserController,
    HomeController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'as' => 'api.',
], function (Router $router) {
    $router->group([
        'middleware' => ['auth:sanctum', 'api'],
    ], function (Router $router) {
        $router
            ->resource('/home', HomeController::class)
            ->only([
                'index',
            ]);

        $router
            ->resource('/user', UserController::class)
            ->only([
                'index',
            ]);
    });
});


//Route::post('/auth/set-password',  [\App\Http\Controllers\AuthController::class, 'setPassword'])->name('auth.set-password');
