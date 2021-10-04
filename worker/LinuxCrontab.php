<?php

namespace worker;

use Workerman\Crontab\Crontab;

class LinuxCrontab
{
    /**
     * Linux计划任务列表
     * @param int $workerId
     * @return void
     */
    public function crontabList($workerId)
    {
        // // 每分钟的第1秒执行.
        // new Crontab('1 * * * * *', function(){
        //     echo date('Y-m-d H:i:s')."\n";
        // });
        // // 每天的7点50执行，注意这里省略了秒位.
        // new Crontab('50 7 * * *', function(){
        //     echo date('Y-m-d H:i:s')."\n";
        // });
    }
}
