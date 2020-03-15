<?php

use tools\Crypt;

require dirname(__DIR__).'/vendor/autoload.php';

//加密
$encrypt = Crypt::getInstance()->crypt('tqIM9ko6So3ADRQ','ENDODE','skinrun');
var_dump(urlencode($encrypt));
//解密
$dencrypt = Crypt::getInstance()->crypt(urldecode($encrypt),'DECODE','skinrun');
var_dump($dencrypt);