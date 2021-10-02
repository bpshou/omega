<?php

namespace api\tools;

use origin\Log;

class Redis
{
    /**
     * Redis instance
     * @author decezz@qq.com
     * @return mixed
     */
    private static $redis = null;

    /**
     * api
     * @return json
     */
    public static function instance()
    {
        if (self::$redis !== null) {
            return self::$redis;
        }
        try {
            $redisConfig = Config::get('redis');
            $redis = new \Redis();
            $redis->connect($redisConfig['host'], $redisConfig['port']);
            // check auth
            if (!empty($redisConfig['pass'])) {
                $redis->auth($redisConfig['pass']);
            }
        } catch (\RedisException $re) {
            $alert = sprintf('Redis exception in %s:%d', $re->getFile(), $re->getLine());
            Log::alert($alert);
            throw new \Exception($re->getMessage(), $re->getCode());
        }
        return $redis;
    }
}
