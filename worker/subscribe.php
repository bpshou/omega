<?php

namespace worker;

require_once __DIR__ . '/../autoload.php';

use origin\Log;
use api\tools\Config;
use api\tools\Redis;

class subscribe
{
    /**
     * 构造
     */
    public function __construct()
    {
        set_time_limit(0);
        // socket设置不超时
        ini_set('default_socket_timeout', -1);
        // 设置时区，避免运行结果与预期不一致
        date_default_timezone_set('PRC');
        // 日志
        Log::instance('redis', __DIR__ . '/../../logs/redis.log', 100);
    }

    /**
     * 订阅
     * @return void
     */
    public function start()
    {
        $channelList = Config::get('subscribe');
        $redis = Redis::instance();
        $redis->subscribe($channelList, function ($instance, $channel, $message) {
            Log::debug('channel ==> ' . $channel, 'message ==> ' . $message);
            if ($channel == 'command') {
                $this->exec($message);
            }
        });
    }

    /**
     * 执行命令
     * @param string $command
     * @return void
     */
    public function exec($command)
    {
        $command = base64_decode($command);
        exec($command, $output, $return_var);
    }
}

(new subscribe)->start();
