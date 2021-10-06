<?php

namespace api;

use origin\Log;

class Douyin extends Base
{
    public $charlesDir = 'C:\Users\origin\Desktop\douyin';

    /**
     * api
     * @return json
     */
    public function service()
    {
        echo "<pre>";
        $result = $this->hookCharles();
        $this->json(200, ['api' => 'success', 'result' => $result]);
    }

    /**
     * charls抓包数据处理
     * @author decezz@qq.com
     * @return array
     */
    public function hookCharles()
    {
        $dirList = scandir($this->charlesDir);
        $result = [];
        foreach ($dirList as $fileName) {
            if (in_array($fileName, ['.', '..'])) {
                continue;
            }
            $file = $this->charlesDir . DIRECTORY_SEPARATOR . $fileName;
            $json = file_get_contents($file);
            $data = $this->pickData($json);
            unlink($file);
            $result = array_merge($result, $data);
        }
        return $result;
    }

    /**
     * 处理数据
     * @param string $json
     * @return array
     */
    public function pickData($json)
    {
        $data = json_decode($json, true);
        $result = [];
        foreach ($data as $key => $value) {
            // 指定url过滤
            if ($value['path'] != '/aweme/v1/web/tab/feed/') {
                continue;
            }
            if (empty($value['response']['body']['encoded'])) {
                continue;
            }

            $json = base64_decode($value['response']['body']['encoded']);
            $body = json_decode($json, true);
            $urls = $this->getVideoUrl($body['aweme_list']);
            $result = array_merge($result, $urls);
        }
        return $result;
    }

    /**
     * 获取抖音视频url
     * @author decezz@qq.com
     * @param array $aweme_list
     * @return array
     */
    public function getVideoUrl(array $aweme_list = [])
    {
        $result = [];
        foreach ($aweme_list as $item) {
            Download::addAsyncTask('aria2', $item['desc'], $item['video']['play_addr']['url_list'][0]);
            $result[] = $item['video']['play_addr']['url_list'][0];
        }
        Log::debug('getVideoUrl', $result);
        return $result;
    }
}
