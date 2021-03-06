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

//Route::get('/user', function () {
//    return "ggg";
//});
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});





//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});





// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:api'], function () {

// ZIPファイルのアップロードだけ
    Route::post('/files/{user_id}', 'UploadFileController');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // OCR実行
    Route::get('/batch', 'BatchController');

    // 本一覧
    Route::get('/user/{userId}', 'BookController@list');
    Route::get('/book/{id}', 'BookController@read');
    Route::post('/book/delete/{bookId}', 'OcrTextController@delete');
    Route::post('/book/{bookId}', 'OcrTextController@edit');
    Route::post('/book', 'BookController@delete');

    Route::get('/capacities/{userId}', 'UserController@capacity');

    // ディレクトリのチェック
    Route::get('/check', 'DeleteExpiredImgDirController');
});


Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    //Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    //Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});
