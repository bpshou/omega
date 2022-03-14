<?php

namespace api;


class DouyinUser extends Base
{
    public $charlesDir = 'C:\Users\origin\Downloads\douyin';


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
            // unlink($file);
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
            if (data_get($value, 'path', '') != '/aweme/v1/web/aweme/favorite/') {
                continue;
            }
            if (data_get($value, 'response.status') != 200) {
                continue;
            }

            $response_body = data_get($value, 'response.body.encoded', '');
            $response_body = base64_decode($response_body);
            if (empty($response_body)) {
                continue;
            }

            $response_body = json_decode($response_body, true);

            $aweme_list = data_get($response_body, 'aweme_list', []);
            $aweme = $this->handle_aweme_list($aweme_list);
            $result = array_merge($result, $aweme);
        }

        if (empty($result)) {
            return $result;
        }
        // $result = json_encode($result, JSON_PRETTY_PRINT);
        // var_dump(implode(PHP_EOL, $result));die;//PHP_EOL
        $file = __DIR__ . '/../runtime/' . microtime(true) . '.txt';
        file_put_contents($file, implode(PHP_EOL, $result), FILE_APPEND);
        return $result;
    }

    /**
     * 处理数据
     * @param string $json
     * @return array
     */
    public function handle_aweme_list(array $aweme_list)
    {
        $result = [];
        foreach ($aweme_list as $value) {
            $result[] = 'https://www.douyin.com/video/' . data_get($value, 'aweme_id', '');
            continue;
            $result[] = [
                'nickname' => data_get($value, 'author.nickname', ''),
                'aweme_id' => data_get($value, 'aweme_id', ''),
                'desc' => data_get($value, 'desc', ''),
            ];
        }
        return $result;
    }
    
}
