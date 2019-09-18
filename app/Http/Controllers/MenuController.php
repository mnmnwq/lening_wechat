<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;
use DB;
class MenuController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function menu_list()
    {
        $info = DB::connection('mysql_cart')->table('menu')->orderBy('name1','asc','name2','asc')->get();
        return view('Menu.menuList',['info'=>$info]);
    }

    public function del_menu(Request $request)
    {
        $id = $request->all()['id'];
        $del_result = DB::connection('mysql_cart')->table('menu')->where(['id'=>$id])->delete();
        if(!$del_result){
            dd('删除失败');
        }
        //根据表数据翻译成菜单结构
        $this->load_menu();
    }

    public function create_menu(Request $request)
    {
        $req = $request->all();
        $button_type = !empty($req['name2'])?2:1;
        $result = DB::connection('mysql_cart')->table('menu')->insert([
            'name1'=>$req['name1'],
            'name2'=>$req['name2'],
            'type'=>$req['type'],
            'button_type'=>$button_type,
            'event_value'=>$req['event_value']
        ]);
        if(!$result){
            dd('插入失败');
        }
        //根据表数据翻译成菜单结构
        $this->load_menu();
    }

    /**
     * 根据数据库表数据刷新菜单
     */
    public function load_menu()
    {
        $data = [];
        $menu_list = DB::connection('mysql_cart')->table('menu')->select(['name1'])->groupBy('name1')->get();
        foreach($menu_list as $vv){
            $menu_info = DB::connection('mysql_cart')->table('menu')->where(['name1'=>$vv->name1])->get();
            $menu = [];
            foreach ($menu_info as $v){
                $menu[] = (array)$v;
            }
            $arr = [];
            foreach($menu as $v){
                if($v['button_type'] == 1){ //普通一级菜单
                    if($v['type'] == 1){ //click
                        $arr = [
                            'type'=>'click',
                            'name'=>$v['name1'],
                            'key'=>$v['event_value']
                        ];
                    }elseif($v['type'] == 2){//view
                        $arr = [
                            'type'=>'view',
                            'name'=>$v['name1'],
                            'url'=>$v['event_value']
                        ];
                    }
                }elseif($v['button_type'] == 2){ //带有二级菜单的一级菜单

                    $arr['name'] = $v['name1'];
                    if($v['type'] == 1){ //click
                        $button_arr = [
                            'type'=>'click',
                            'name'=>$v['name2'],
                            'key'=>$v['event_value']
                        ];
                    }elseif($v['type'] == 2){//view
                        $button_arr = [
                            'type'=>'view',
                            'name'=>$v['name2'],
                            'url'=>$v['event_value']
                        ];
                    }
                    $arr['sub_button'][] = $button_arr;
                }
            }
            $data['button'][] = $arr;
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->tools->get_wechat_access_token();
        /*$data = [
            'button'=> [
                [
                    'type'=>'click',
                    'name'=>'今日歌曲',
                    'key'=>'V1001_TODAY_MUSIC'
                ],
                [
                    'name'=>'菜单',
                    'sub_button'=>[
                        [
                            'type'=>'view',
                            'name'=>'搜索',
                            'url'=>'http://www.soso.com/'
                        ],
                        [
                            'type'=>'miniprogram',
                            'name'=>'wxa',
                            'url'=>'http://mp.weixin.qq.com',
                            'appid'=>'wx286b93c14bbf93aa',
                            'pagepat'=>'pages/lunar/index'
                        ],
                        [
                            'type'=>'click',
                            'name'=>'赞一下我们',
                            'key'=>'V1001_GOOD'
                        ]
                    ]
                ]
            ]
        ];*/
        $re = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($re,1);
        dd($result);
    }
}
