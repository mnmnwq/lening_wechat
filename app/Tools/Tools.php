<?php
/**
 * Created by PhpStorm.
 * User: baiwei
 * Date: 2019/9/6
 * Time: 15:26
 */
namespace App\Tools;

class Tools {

    public $redis;
    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }

}