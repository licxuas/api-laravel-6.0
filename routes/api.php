<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['cors', 'serializer:array', 'bindings']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        $api->post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');      //发送验证码
        $api->post('authorizations', 'AuthorizationsController@store')->name('authorizations.store');               //用户登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')->name('socials.authorizations.store');   //第三方登录
    });


    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {


        $api->group(['middleware' => ['token.canrefresh']], function ($api) {

            $api->get('me', 'UserCentersController@index')->name('userCenters.me');     //我的个人信息

        });
    });
});


$api->version('v2', [
    'namespace' => 'App\Http\Controllers\Api\V2',
    'middleware' => ['cors', 'serializer:array', 'bindings']
], function($api) {

});
