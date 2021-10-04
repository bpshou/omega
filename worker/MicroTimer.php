<?php

namespace worker;

use Workerman\Lib\Timer;
use api\Download;

class MicroTimer
{
    /**
     * 毫秒级定时器列表
     * @param int $workerId
     * @return void
     */
    public function timerList($workerId)
    {
        // 每一秒执行一次 (min 0.001 ms)
        Timer::add(1, function(){
            // echo "hi\r\n";
            Download::consumeTask();
        });
    }
}
