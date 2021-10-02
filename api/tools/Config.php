<?php

namespace api\tools;

class Config
{
    /**
     * namespace 根目录
     * @var string
     * @author decezz@qq.com
     */
    private static $namespaceDir = __DIR__ . '/../../';

    /**
     * 配置文件目录
     * @var string
     * @author decezz@qq.com
     */
    private static $configDir = __DIR__ . '/../config/';

    /**
     * 获取配置目录的配置
     * @author decezz@qq.com
     * @param string $configName
     * @return mixed
     */
    public static function get(string $configName)
    {
        $configName = str_replace('.php', '', $configName);
        $config = self::$configDir . $configName . '.php';
        return self::includeFileConfig($config);
    }

    /**
     * 通过命名空间获取配置
     * @author decezz@qq.com
     * @param string $namespace
     * @return mixed
     */
    public static function getByNamespce(string $namespace)
    {
        $namespace = str_replace('.php', '', $namespace);
        $config = self::$namespaceDir . $namespace . '.php';
        return self::includeFileConfig($config);
    }

    /**
     * 引入文件配置
     * @author decezz@qq.com
     * @param string $file
     * @return mixed
     */
    protected static function includeFileConfig(string $file)
    {
        if (!file_exists($file)) {
            return false;
        }
        return include_once $file;
    }
}
