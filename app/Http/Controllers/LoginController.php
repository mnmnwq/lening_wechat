<?php

namespace App\Http\Controllers;

use App\Tools\Tools;
use Illuminate\Http\Request;
use App\Libraries\Test;
use DB;

class LoginController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function login()
    {
        Test::index();

    }

    /**
     * 8月份B卷6题
     */
    public function push()
    {
        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
        $openid_info = file_get_contents($user_url);
        $user_result = json_decode($openid_info,1);
        foreach($user_result['data']['openid'] as $v){
            $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
            $data = [
                'touser'=>$v,
                'template_id'=>'yqzB2bWhBkD8kLYN-Wh2FIxSlTpOsapOV9ovWt-uHmA',
                'data'=>[
                ]
            ];
            $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * 微信登陆
     */
    public function wechat_login()
    {
        $redirect_uri = env('APP_URL').'/wechat/code';
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
