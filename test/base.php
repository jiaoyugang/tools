<?php
/**
 * 测试文件
 */

require_once dirname(__DIR__).'/vendor/autoload.php';

use tools\http\Http;
use tools\Image_Base64;
//-----------------------------------------------------curl模拟http请求测试--------------------
//测试用例，发送get请求
//var_dump(Http::get('www.baidu.com'));


//发送post请求分多钟情况
//post请求①不设置请求头参数(Content-Type)
/*$data = [
    'image' => 'http://three-api.oss-cn-beijing.aliyuncs.com/skin_test/2019-08-17/15659756543255.png',
    'birthday' => '1988-01-01',
    'gender' => 1
];

#传入请求头参数
$options = ['headers' => ['version:1.0' , 'token:bf76e9c8152e790f06bf9b370758ad16']];
var_dump(Http::post('http://xxxxx/xxxx',$data,$options));*/


//post请求②设置请求头参数（"Content-Type: application/json"）


 $data = json_encode([
    'image' => 'http://three-api.oss-cn-beijing.aliyuncs.com/skin_test/2019-08-17/15659756543255.png',
    'birthday' => '1988-01-01',
    'gender' => 1
]);

#传入请求头参数,并设置请求头类型
$options = ['headers' => ['version:1.0' , 'token:bf76e9c8152e790f06bf9b370758ad16' ,'Content-Type: application/json']];

$http = Http::getInstance();
var_dump($http->post('http://open.skinrun.me/face',$data,$options));




//---------------------------------------------------image图片转换----------------------------------

// $base64 = Image_Base64::ImageToBase64('http://three-api.oss-cn-beijing.aliyuncs.com/skin_test/2019-08-17/15659756543255.png');

// Image_Base64::Base64ToImage('./test.png',base64_decode($base64,true));

