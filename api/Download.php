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
     * @var string
     * @author decezz@qq.com
     */
    public $downloadTpl = 'annie -o %s %s';

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
        if (!$this->addAsyncTask($resource)) {
            $this->json(500, ['msg' => 'add task fail']);
        }
        $this->json(200, ['msg' => '下载任务添加成功', 'resource' => $resource]);
    }
    
    /**
     * 添加异步任务
     * @author decezz@qq.com
     * @param string $resource
     * @return bool
     */
    public function addAsyncTask($resource)
    {
        $config = Config::get('config');
        $rpushKey = $config['downloadKey'];
        $download = $config['downloadDir'];
        $task = sprintf($this->downloadTpl, $download, $resource);
        $redis = Redis::instance();
        return $redis->rpush($rpushKey, $task);
    }

    /**
     * 消费任务 (worker/MicroTimer下消费)
     * @author decezz@qq.com
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
