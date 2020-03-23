<?php
/**
 * 字符串对称加密类
 */
namespace tools\crypt;

final class Crypt{

    /** 创建静态私有的变量保存该类对象 */
    private static $instance;

    /**加密干扰项 */
    private $salt = 'TZL6hLceXpCg8kpo2ZjquSGHGHgoaTI7';

    //防止使用new直接创建对象
    private function __construct(){}

    //防止使用clone克隆对象
    private function __clone(){}

    public static function getInstance()
    {
        //判断$instance是否是Singleton的对象，不是则创建
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 用户信息加密解密函数
     *
     * 待加密内容用\t分割
     * @return String 加密或解密字符串
     * @param String $string 待加密或解密字符串
     * @param String $operation 操作类型定义 DECODE=解密 ENDODE=加密
     * @param String $key 加密算子
     */
    final public function crypt($string, $operation = 'ENDODE', $key = '') {
        /**
         * 获取密码算子,如未指定，采取系统默认算子
         * 默认算子是论坛授权码和用户浏览器信息的md5散列值
         * $GLOBALS['discuz_auth_key']----全局变量
         * 取值为:md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT'])
         * $_DCACHE['settings']['authkey']是论坛安装时生成的15位随机字符串
         */
        $key = md5($key ? $key : $this->salt);
        $key_length = strlen($key);
        /**
         * 如果解密，先对密文解码
         * 如果加密,将密码算子和待加密字符串进行md5运算后取前8位
         * 并将这8位字符串和待加密字符串连接成新的待加密字符串
         */
        $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';

        /**
         * 初始化加密变量,$rndkey和$box
         */
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }

        /**
         * $box数组打散供加密用
         */
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        /**
         * $box继续打散,并用异或运算实现加密或解密
         */
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        
        if ($operation == 'DECODE') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}