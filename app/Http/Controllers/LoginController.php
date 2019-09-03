<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LoginController extends Controller
{

    public function login()
    {
        return view('Login.login');
    }

    /**
     * 微信登陆
     */
    public function wechat_login()
    {
        $redirect_uri = 'http://www.wechat.com/wechat/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header('Location:'.$url);
    }

    /**
     * 接收code 第二部
     */
    public function code(Request $request)
    {
        $req = $request->all();
        $result = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code');
        $re = json_decode($result,1);
       $user_info = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$re['access_token'].'&openid='.$re['openid'].'&lang=zh_CN');
       $wechat_user_info = json_decode($user_info,1);
        $openid = $re['openid'];
        $wechat_info = DB::connection('mysql_cart')->table('user_wechat')->where(['openid'=>$openid])->first();
        dd($wechat_info);
        if(!empty($wechat_info)){
            //存在,登陆
            $request->session()->put('uid',$wechat_info->uid);
            echo "ok";
           // return redirect('');  //主页
        }else{
            //不存在,注册,登陆
            //插入user表数据一条
            DB::connection('mysql_cart')->beginTransaction();  //打开事物
            $uid = DB::connection('mysql_cart')->table('user')->insertGetId([
                'name'=>$wechat_user_info['nickname'],
                'password'=>'',
                'reg_time'=>time()
            ]);
            $insert_result = DB::connection('mysql_cart')->table('user_wechat')->insert([
                'uid'=>$uid,
                'openid'=>$openid
            ]);
            //登陆操作
            $request->session()->put('uid',$wechat_info->uid);
            echo "ok";
            // return redirect('');  //主页
        }
    }
}
