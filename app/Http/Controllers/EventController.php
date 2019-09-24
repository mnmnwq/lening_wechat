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
        //签到逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK'){
            if($xml_arr['EventKey'] == 'sign'){
                //签到
                $today = date('Y-m-d',time()); //当天日期
                $last_day = date('Y-m-d',strtotime('-1 days'));  //昨天
                $openid_info = DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->first();
                if(empty($openid_info)){
                    //没有数据，存入
                    DB::connection('mysql_cart')->table("wechat_openid")->insert([
                        'openid'=>$xml_arr['FromUserName'],
                        'add_time'=>time()
                    ]);
                }
                $openid_info = DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->first();
                if($openid_info->sign_day == $today){
                    //已签到
                    $message = '您已签到';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }else{
                    //未签到  积分
                    if($last_day == $openid_info->sign_day){
                        //连续签到 五天一轮
                        if($openid_info->sign_days >= 5){
                            DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->update([
                                'sign_days'=>1,
                                'score' => $openid_info->score + 5,
                                'sign_day'=>$today
                            ]);
                        }else{
                            DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->update([
                                'sign_days'=>$openid_info->sign_days + 1,
                                'score' => $openid_info->score + 5 * ($openid_info->sign_days + 1),
                                'sign_day'=>$today
                            ]);
                        }
                    }else{
                        //非连续
                        //加积分  连续天数变1
                        DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->update([
                            'sign_days'=>1,
                            'score' => $openid_info->score + 5,
                            'sign_day'=>$today
                        ]);
                    }
                    $message = '签到成功';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }
            }
            if($xml_arr['EventKey'] == 'score'){
                //查几分
                $openid_info = DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->first();
                if(empty($openid_info)){
                    //没有数据，存入
                    DB::connection('mysql_cart')->table("wechat_openid")->insert([
                        'openid'=>$xml_arr['FromUserName'],
                        'add_time'=>time()
                    ]);
                    $message = '积分：0';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }else{
                    $message = '积分：'.$openid_info->score;
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }
            }
        }
        //关注逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){
            //关注
            //opnid拿到用户基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $user_re = file_get_contents($url);
            $user_info = json_decode($user_re,1);
            //存入数据库
            $db_user = DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$xml_arr['FromUserName']])->first();
            if(empty($db_user)){
                //没有数据，存入
                DB::connection('mysql_cart')->table("wechat_openid")->insert([
                    'openid'=>$xml_arr['FromUserName'],
                    'add_time'=>time()
                ]);
            }
            $message = '欢迎'.$user_info['nickname'].'同学，感谢您的关注';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;
        }
    }
}
