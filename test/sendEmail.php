<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/10
 * Time: 17:11
 */

require dirname(__DIR__).'/vendor/autoload.php';

/**
 *  邮箱配置pop  smtp（对应host参数）
 *  smtp.qq.com
 *  smtp.163.com
 *  smtp.gmail.com
 *  smtp.exmail.qq.com 腾讯企业邮箱
 * */

$config = [
    'host' => 'smtp.qq.com',
    'port' => '25',
    'sendUse' => '1355528968@qq.com',
    'sendPwd' => '201307104120@adg',
    'fromUser' => ['1355528968@qq.com' => '测试'],
    'toUser' => ['18838952961@163.com'],
    'subject' => '主题：测试报告',
    'msgBody' => 'hello world'
];
$email = new \tools\Email();

var_dump($email::sendMsg($config));