<?php

namespace api;

use origin\Request;
use origin\Log;
use api\tools\Config;
use api\tools\Redis;

class Download extends Base
{
    /**
     * 下载任务模板
     * @var array
     */
    public static $downloadTpl = [
        'annie'  => 'annie -o %s %s',
        'aria2'  => 'aria2c -d %s -o \'%s\' \'%s\'',
    ];

    /**
     * api
     * @return json
     */
    public function service()
    {
        $Request = new Request();
        if (!isset($Request->post['resource']) || empty($Request->post['resource'])) {
            $this->json(400, ['msg' => 'params error:resource']);
        }
        $resource = $Request->post['resource'];
        if (!$this->addAsyncTask('annie', $resource)) {
            $this->json(500, ['msg' => 'add task fail']);
        }
        $this->json(200, ['msg' => '下载任务添加成功', 'resource' => $resource]);
    }
    
    /**
     * 添加异步任务
     * @param string $tools
     * @param mixed $resource
     * @return bool
     */
    public static function addAsyncTask(string $tools, ...$resource)
    {
        if (!array_key_exists($tools, self::$downloadTpl)) {
            return false;
        }
        $toolsTpl = self::$downloadTpl[$tools];
        $config = Config::get('config');
        // $rpushKey = $config['downloadKey'];
        $download = $config['downloadDir'];
        // 下载地址填入开头
        array_unshift($resource, $download);
        $task = vsprintf($toolsTpl, $resource);
        $redis = Redis::instance();
        // return $redis->rpush($rpushKey, $task);
        return $redis->publish('command', base64_encode($task));
    }

    /**
     * 消费任务 (worker/MicroTimer下消费)
     * @return bool|int
     */
    public static function consumeTask()
    {
        $config = Config::get('config');
        $rpushKey = $config['downloadKey'];
        $redis = Redis::instance();
        while ($cmd = $redis->lpop($rpushKey)) {
            exec($cmd, $output, $return_var);
            Log::debug($cmd, $output, $return_var);
        }
        return true;
    }
}
