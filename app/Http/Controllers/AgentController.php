<?php

namespace App\Http\Controllers;
use App\Tools\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use GuzzleHttp\Client;

class AgentController extends Controller
{
    public $tools;
    public $client;
    public function __construct(Tools $tools,Client $client)
    {
        $this->tools = $tools;
        $this->client = $client;
    }
    public function agent_list()
    {
        $user_info = DB::connection('mysql_cart')->table('user')->get();
        return view('Agent.userList',['info'=>$user_info]);
    }

    public function create_qrcode(Request $request)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->tools->get_wechat_access_token();
        $data = [
            'expire_seconds'=> 30 * 24 * 3600,
            'action_name'=> 'QR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>$request->all()['uid']
                ]
            ]
        ];
        $re = $this->tools->curl_post($url,json_encode($data));
        $result = json_decode($re,1);
        $qrcode_info = file_get_contents('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($result['ticket']));
        //$res = $this->client->request('GET', 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($result['ticket']));
        //$header_arr = $res->getHeaders();
        //dd($header_arr);
        $path = '/wechat/qrcode/'.time().rand(1000,9999).'.jpg';
        Storage::put($path, $qrcode_info);
        DB::connection('mysql_cart')->table('user')->where(['id'=>$request->all()['uid']])->update([
            'qrcode_url'=> '/storage'.$path
        ]);
        return redirect('/agent/agent_list');
    }
}
