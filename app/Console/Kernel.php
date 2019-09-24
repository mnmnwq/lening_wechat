<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Tools\Tools;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */




    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
           //功能 业务逻辑
            //$tools = new Tools();
            \Log::Info('执行自动任务');
            $data = [];
            $url = '';
            $this->post_url($url,$data);
       // })->daily();
        })->everyMinute();

    }

    /**
     * @param $url
     * @param $data
     */
    public function curl_post($url,$data)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);

        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息

        curl_close($curl);
        return $data;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
