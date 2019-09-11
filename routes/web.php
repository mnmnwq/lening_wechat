<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login','LoginController@login');
////////////////////////////////////////////// 标签////////////////////////////////////////////////////
///
Route::get('/wechat/tag_list','TagController@tag_list');  //公众号标签列表
Route::get('/wechat/add_tag','TagController@add_tag');
Route::post('/wechat/do_add_tag','TagController@do_add_tag');

Route::get('/wechat/tag_openid_list','TagController@tag_openid_list'); //标签下用户的openid列表
Route::post('/wechat/tag_openid','TagController@tag_openid'); //为用户打标签
Route::get('/wechat/user_tag_list','TagController@user_tag_list'); //用户下的标签列表
Route::get('/wechat/push_tag_message','TagController@push_tag_message'); //推送标签消息
Route::post('/wechat/do_push_tag_message','TagController@do_push_tag_message'); //执行推送标签消息

//////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/wechat/clear_api','WechatController@clear_api');
Route::get('/wechat/source','WechatController@wechat_source'); //素材管理
Route::get('/wechat/download_source','WechatController@download_source'); //下载资源

Route::get('/wechat/upload','WechatController@upload'); //上传
Route::post('/wechat/do_upload','WechatController@do_upload'); //上传

Route::get('wechat/get_access_token','WechatController@get_access_token'); //获取access_token

Route::get('/wechat/get_user_list','WechatController@get_user_list'); //获取用户列表


Route::get('/wechat/login','LoginController@wechat_login'); //微信授权登陆
Route::get('/wechat/code','LoginController@code'); //接收code

