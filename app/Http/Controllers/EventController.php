<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Tools\Tools;

class EventController extends Controller
{
    public  $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * 接收微信发送的消息【用户互动】
     */
    public function event()
    {
        $xml_string = file_get_contents('php://input');  //获取
        $wechat_log_psth = storage_path('logs/wechat/'.date('Y-m-d').'.log');
        file_put_contents($wechat_log_psth,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_psth,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_psth,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);
        //dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
        //echo $_GET['echostr'];
        //业务逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){
            //关注
            //opnid拿到用户基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $user_re = file_get_contents($url);
            $user_info = json_decode($user_re,1);
            $message = '欢迎'.$user_info['nickname'].'同学，感谢您的关注';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;
        }
    }
}
