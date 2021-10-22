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
        if (isset($Request->post['downloaddir']) || !empty($Request->post['downloaddir'])) {
            $redis = Redis::instance();
            $redis->set('downloaddir', $Request->post['downloaddir']);
            $redis->expire('downloaddir', time() + 86400);
        }
        $resource = $Request->post['resource'];
        $download = self::getDownloadDir();
        if (!$this->addAsyncTask('annie', $download, $resource)) {
            $this->json(500, ['msg' => 'add task fail']);
        }
        $result = [
            'msg' => '下载任务添加成功',
            'resource' => $resource,
            'download' => $download,
        ];
        $this->json(200, $result);
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

    /**
     * 获取下载地址
     * @return string
     */
    public static function getDownloadDir()
    {
        $redis = Redis::instance();
        $downloaddir = $redis->get('downloaddir');
        if (!$downloaddir) {
            $config = Config::get('config');
            $downloaddir = $config['downloadDir'];
        }
        return $downloaddir;
    }
}
