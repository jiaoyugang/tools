<?php

use tools\crypt\Crypt;

require dirname(__DIR__).'/vendor/autoload.php';

//加密
$encrypt = Crypt::getInstance()->crypt('焦玉刚','ENDODE','skinrun');
var_dump(urlencode($encrypt));
//解密
$dencrypt = Crypt::getInstance()->crypt(urldecode($encrypt),'DECODE','skinrun');
var_dump($dencrypt);