<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/10
 * Time: 17:11
 */

require_once dirname(__DIR__).'/vendor/autoload.php';

$config = [
    'host' => 'smtp.exmail.qq.com',
    'sendUser' => '1355528968@qq.com',
    'sendPassword' => '201307104120@adg',
    'form' => ['1355528968@qq.com' => 'gang'],
    'msg' => 'hello world'
];
$email = new \tools\Email($config);

$email::sendMsg('hello',['1355528968@qq.com' => 'gang'],['18838952961@163.com' => '测试'],'测试');