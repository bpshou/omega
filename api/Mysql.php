<?php

namespace api;

use think\facade\Db;

class Mysql
{
    /**
     * 初始化数据库
     * @return null
     */
    public function initMysql()
    {
        // 数据库配置信息设置（全局有效）
        Db::setConfig([
            // 默认数据连接标识
            'default'     => 'localhost',
            // 数据库连接信息
            'connections' => [
                'localhost' => [
                    // 数据库类型
                    'type'      => 'mysql',
                    // 主机地址
                    'hostname'  => '127.0.0.1',
                    // 用户名
                    'username'  => 'root',
                    // 数据库名
                    'database'  => 'app',
                    // 数据库密码
                    'password'  => '123456',
                    // 数据库连接端口
                    'hostport'  => '3306',
                    // 数据库编码默认采用utf8
                    'charset'   => 'utf8',
                    // 数据库表前缀
                    'prefix'    => '',
                    // 数据库调试模式
                    'debug'     => true,
                ],
            ],
        ]);
    }
}
