<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function post_test()
    {
        dd($_POST);
    }

    /**
     * 上传
     */
    public function upload(){
        return view('Wechat.upload',[]);
    }

    /**
     * image video voice thumb
     * id media_id path ['/storage/wechat/image/imagename.jpg'] add_time
     * @param Request $request
     */
    public function do_upload(Request $request){

        $name = 'file_name';
        if(!empty($request->hasFile($name)) && request()->file($name)->isValid()){
            //$size = $request->file($name)->getClientSize() / 1024 / 1024;
            $ext = $request->file($name)->getClientOriginalExtension();  //文件类型
            $file_name = time().rand(1000,9999).'.'.$ext;
            $path = request()->file($name)->storeAs('wechat\voice',$file_name);
            $path = realpath('./storage/'.$path);
            $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->get_wechat_access_token().'&type=voice';
            $result = $this->curl_upload($url,$path);
            dd($result);
        }
    }

    /**
     * 获取用户列表
     */
    public function get_user_list()
    {
        $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->get_wechat_access_token().'&next_openid=');
        $re = json_decode($result,1);
        $last_info = [];
        foreach($re['data']['openid'] as $k=>$v){
            $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN');
            $user = json_decode($user_info,1);
            $last_info[$k]['nickname'] = $user['nickname'];
            $last_info[$k]['openid'] = $v;
        }
        dd($last_info);
        //dd($re['data']['openid']);
        return view('Wechat.userList',['info'=>$re['data']['openid']]);
    }

    /**
     * 获取access_token
     */
    public function get_access_token()
    {
        return $this->get_wechat_access_token();
    }

    /**
     * curl上传微信素材
     * @param $url
     * @param $path
     * @return bool|string
     */
    public function curl_upload($url,$path)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        $form_data = [
            'meida' => new \CURLFile($path)
        ];
        curl_setopt($curl,CURLOPT_POSTFIELDS,$form_data);
        $data = curl_exec($curl);
        //$errno = curl_errno($curl);  //错误码
        //$err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }


    public function get_wechat_access_token()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        //加入缓存
        $access_token_key = 'wechat_access_token';
        if($redis->exists($access_token_key)){
            //存在
            return $redis->get($access_token_key);
        }else{
            //不存在
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET'));
            $re = json_decode($result,1);
            $redis->set($access_token_key,$re['access_token'],$re['expires_in']);  //加入缓存
            return $re['access_token'];
        }
    }
}
