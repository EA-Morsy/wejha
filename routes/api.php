    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['languageMobile']], function () {
        Route::post('login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
        Route::post('reset-password', [App\Http\Controllers\Api\V1\AuthController::class, 'resetPassword']);
        Route::post('confirm-reset', [App\Http\Controllers\Api\V1\AuthController::class, 'confirmReset']);
        Route::post('check-code', [App\Http\Controllers\Api\V1\AuthController::class, 'checkCode']);
        Route::post('register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
        Route::post('verify', [App\Http\Controllers\Api\V1\AuthController::class, 'verify']);
        Route::get('privacy', [App\Http\Controllers\Api\V1\SettingController::class, 'privacy']);
        Route::get('terms', [App\Http\Controllers\Api\V1\SettingController::class, 'terms']);
        Route::get('contact', [App\Http\Controllers\Api\V1\SettingController::class, 'contact']);

        Route::get('sliders', [App\Http\Controllers\Api\V1\PageController::class,'sliders']);
        Route::get('cities', [App\Http\Controllers\Api\V1\PageController::class,'cities']);
        Route::get('companies', [App\Http\Controllers\Api\V1\PageController::class,'companies']);
        Route::get('categories', [App\Http\Controllers\Api\V1\PageController::class,'categories']);
        Route::get('home', [App\Http\Controllers\Api\V1\PageController::class,'home']);
        Route::get('copounBycategory/{id}', [App\Http\Controllers\Api\V1\PageController::class,'categoryCoupons']);
        Route::get('coupons', [App\Http\Controllers\Api\V1\PageController::class,'coupons']);
        Route::get('coupon/{id}', [App\Http\Controllers\Api\V1\PageController::class,'coupon']);

        Route::group(['middleware' => 'auth:sanctum'], function () {
            /////user/////
            Route::get('my-coupons', [App\Http\Controllers\Api\V1\CouponController::class,'myCoupons']);
            Route::post('add-coupon', [App\Http\Controllers\Api\V1\CouponController::class, 'create']);
            Route::delete('delete-coupon/{id}', [App\Http\Controllers\Api\V1\CouponController::class, 'delete']);
            Route::get('change-language/{language}', [App\Http\Controllers\Api\V1\PageController::class, 'changeLang']);
            Route::post('update-profile', [App\Http\Controllers\Api\V1\AuthController::class, 'updateProfile']);
            Route::get('profile', [App\Http\Controllers\Api\V1\AuthController::class, 'profile']);
            Route::post('update-image', [App\Http\Controllers\Api\V1\AuthController::class, 'updateimage']);
            Route::post('logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
            Route::delete('delete-account', [App\Http\Controllers\Api\V1\AuthController::class, 'deleteAccount']);
            Route::post('fcm-token', [App\Http\Controllers\Api\V1\AuthController::class, 'updateToken']);
        });
    });
});
