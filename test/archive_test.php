<?php

require dirname(__DIR__).'/vendor/autoload.php';

use tools\archive\Archive;
// 添加某个文件到压缩包中
try {
    $archive = new Archive();
    $archive->addZip('/home/www/test/20200325','/home/www/tools/test');
}catch(\Exception $e){
    var_dump($e->getMessage());
}

//解压压缩包
try{
    $archive = new Archive();
    $res = $archive->unzip('/home/www/test/2020.zip','/home/www/test/test');
    var_dump($res);
}catch(\Exception $e){
    var_dump($e->getMessage());
}