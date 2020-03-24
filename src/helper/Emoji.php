<?php
namespace tools\helper;

/**
 * 处理 Emoji 表情
 * Class Emoji
 * @package tools
 */
final class Emoji
{

    private static $instance;

    /**
     * 禁止实例化
     */
    private function __construct()
    {}

    /**
     * 私有属性的克隆方法 防止被克隆
     */
    private function __clone()
    {}

    /**
     * 静态方法 用以实例化调用
     */
    public static function getInstance()
    {

        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Emoji原形转换为String
     * @param string $content
     * @return string
     */
    public static function encode($content)
    {
        return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($maps) {
            return addslashes($maps[0]);
        }, json_encode($content)));
    }

    /**
     * Emoji字符串转换为原形
     * @param string $content
     * @return string
     */
    public static function decode($content)
    {
        return json_decode(preg_replace_callback('/\\\\\\\\/i', function () {
            return '\\';
        }, json_encode($content)));
    }

    /**
     * Emoji字符串清清理
     * @param string $content
     * @return string
     */
    public static function clear($content)
    {
        return preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $content);
    }
}
