<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;

class TagController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
           $this->tools = $tools;
    }

    /**
     * 推送标签群发消息
     */
    public function push_tag_message(Request $request)
    {
        return view('Tag.pushTagMsg',['tagid'=>$request->all()['tagid']]);
    }

    public function do_push_tag_message(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->tools->get_wechat_access_token();
        $data = [
            'filter' => [
                'is_to_all'=>false,
                'tag_id'=>$req['tagid']
            ],
            'text'=>[
                'content'=>$req['message']
            ],
            'msgtype'=>'text'
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }

    public function user_tag_list(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$this->tools->get_wechat_access_token();
        $data = [
            'openid'=>$req['openid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        $tag = file_get_contents('https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_wechat_access_token());
        $tag_result = json_decode($tag,1);
        $tag_arr = [];
        foreach($tag_result['tags'] as $v){
            $tag_arr[$v['id']] = $v['name'];
        }
        foreach($result['tagid_list'] as $v){
            echo $tag_arr[$v]."<br/>";
        }
    }

    public function tag_openid(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->tools->get_wechat_access_token();
        $data = [
            'openid_list'=>$req['openid_list'],
            'tagid'=>$req['tagid']
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }

    /**
     * 标签下粉丝列表
     */
    public function tag_openid_list(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->tools->get_wechat_access_token();
        $data = [
            'tagid' => $req['tagid'],
            'next_openid' => ''
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        dd($result);
    }

    /**
     * 公众号的tag管理页
     */
    public function tag_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_wechat_access_token();
        $re = file_get_contents($url);
        $result = json_decode($re,1);
        return view('Tag.tagList',['info'=>$result['tags']]);
    }

    public function add_tag()
    {
        return view('Tag.addTag');
    }

    public function do_add_tag(Request $request)
    {
        $req = $request->all();
        $data = [
            'tag'=>[
                'name'=>$req['tag_name']
            ]
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->tools->get_wechat_access_token();
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }
}
