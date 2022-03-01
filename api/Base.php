<?php

namespace api;

use origin\Response;
use origin\Log;

/**
 * 服务主类
 */
class Base
{
    /**
     * @var string
     */
    public $endpoint = 'service';

    /**
     * 构造
     */
    public function __construct()
    {
        try {
            Log::instance('app', __DIR__ . '/../../logs/omega.log', 100);
            call_user_func_array([$this, $this->endpoint], []);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->json($code, ['message' => $message]);
        }
    }

    /**
     * 服务入口
     */
    public function service()
    {
    }

    /**
     * 输出json
     */
    public function json($code = 200, $content = [], $header = [])
    {
        $contentType = 'json';
        return $this->result($code, $contentType, $content, $header);
    }

    /**
     * 输出html
     */
    public function html($code = 200, $content = [], $header = [])
    {
        $contentType = 'html';
        return $this->result($code, $contentType, $content, $header);
    }

    /**
     * 返回结果
     */
    protected function result($code, $contentType, $content, $header)
    {
        $Response = new Response();
        $Response->cross($_SERVER['HTTP_ORIGIN'] ?? '*')
            ->code($code)
            ->header($header)
            ->content($content)
            ->contentType($contentType)
            ->send();
        exit;
    }

}
