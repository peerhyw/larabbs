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

$api = app('Dingo\Api\Routing\Router');

//namespace 所有v1版本的路由都会指向App\Http\Controllers\Api
$api->version('v1',[
    'namespace' => 'App\Http\Controllers\Api'
],function ($api){
    //短信验证码
    $api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');
    //用户注册
    $api->post('users','UsersController@store')->name('api.users.store');
});
