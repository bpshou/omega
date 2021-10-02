<?php

namespace api\tools;

use think\facade\Db;

class Mysql
{
    /**
     * 初始化数据库
     * @return null
     */
    public static function initMysql()
    {
        // 数据库配置信息设置（全局有效）
        Db::setConfig(Config::get('mysql'));
    }
}
