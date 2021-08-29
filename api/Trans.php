<?php

namespace api;

use origin\Request;
use origin\Openssl;

class Trans extends Base
{

    /**
     * 服务入口
     * @author decezz@qq.com
     * @return array
     */
    public function service() {
        $Request    = new Request();
        if (!isset($Request->post['type'])) {
            $this->json(400, ['msg' => 'params error:type']);
        }
        if (!isset($Request->post['data'])) {
            $this->json(400, ['msg' => 'params error:data']);
        }
        $type       = $Request->post['type'] ? $Request->post['type'] : 'formatJson' ;
        $result     = call_user_func_array([$this, $type], [$Request->post['data']]);
        $this->echo($result);
    }

    /**
     * 格式化json数据
     * @author decezz@qq.com
     * @param string|array $data
     * @return string
     */
    public function formatJson( $data ) {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        $result = json_encode($data, 448); // 448 = 320 + JSON_PRETTY_PRINT
        return $result;
    }

    /**
     * urldecode解析数组
     * @author decezz@qq.com
     * @param string $data
     * @return array
     */
    public function urldecode( $data ) {
        parse_str($data, $result);
        return $result;
    }

    /**
     * json解析数组
     * @author decezz@qq.com
     * @param string $data
     * @return array
     */
    public function toArray( $data ) {
        $result = json_decode($data, true);
        return $result;
    }

    /**
     * base64解析数组
     * @author decezz@qq.com
     * @param string $data
     * @return array
     */
    public function base64Decode( $data ) {
        $result = base64_decode($data, true);
        return $result;
    }

    /**
     * 非对称密钥加解密
     * @author decezz@qq.com
     * @param string $data
     * @return mixed
     */
    public function openssl( $data ) {
        $conf = [
            'pubkey'    => $data['public'],
            'prikey'    => $data['private'],
        ];
        $Openssl = new Openssl($conf);
        $method = $data['method'];
        $padding = $data['padding'];
        $content = $data['data'];
        $result = $Openssl->$method($content, $padding);
        return $result;
    }

    /**
     * AES加密
     * @author decezz@qq.com
     * @return mixed
     */
    public function cipherMethods() {
        $result = openssl_get_cipher_methods();
        return json_encode($result);
    }

    /**
     * AES加密
     * @author decezz@qq.com
     * @param string $data
     * @return mixed
     */
    public function encrypt( $data ) {
        $cipher_algo = $data['cipher_algo'];
        $passphrase = $data['passphrase'];
        $options = $data['options'];
        $iv = $data['iv'];
        $data = $data['data'];
        return openssl_encrypt($data, $cipher_algo, $passphrase, $options, $iv);
    }

    /**
     * AES解密
     * @author decezz@qq.com
     * @param string $data
     * @return mixed
     */
    public function decrypt( $data ) {
        $cipher_algo = $data['cipher_algo'];
        $passphrase = $data['passphrase'];
        $options = $data['options'];
        $iv = $data['iv'];
        $data = $data['data'];
        return openssl_decrypt($data, $cipher_algo, $passphrase, $options, $iv);
    }

    /**
     * 转换成时间戳
     * @author decezz@qq.com
     * @param string $data
     * @return string
     */
    public function strtotime( $data ) {
        return strtotime($data);
    }

    /**
     * 转换成时间戳
     * @author decezz@qq.com
     * @param string $data
     * @return string
     */
    public function date( $data ) {
        if (!is_numeric($data)) {
            return 'false';
        }
        return date('Y-m-d H:i:s', $data);
    }

    /**
     * 结果输出
     * @author decezz@qq.com
     * @param string $data
     * @return mixed
     */
    private function echo( $data ) {
        if (is_array($data)) {
            var_export($data);
        } else {
            echo $data;
        }
    }
}
