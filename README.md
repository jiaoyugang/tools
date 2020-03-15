# descript
开发工具封装，常用工具汇总（个人开发中使用的组件）

# example

> curl模拟HTTP请求（http request）

```php
require_once dirname(__DIR__).'/vendor/autoload.php';
use tools\Http;

#（1）测试用例，发送get请求
var_dump(Http::get('www.baidu.com'));

//发送post请求分多钟情况
//全局请求参数
$data = [
    'image' => 'http://three-api.oss-cn-beijing.aliyuncs.com/xxxxxx.png',
    'birthday' => '1988-01-01',
    'gender' => 1
];

#（1）post请求不设置请求头参数(Content-Type)
#传入请求头参数
$options = [
    'headers' => [
        'version:1.0' , 
        'token:bf76e9c8152e790f06bf9b370758ad16'
        ]
    ];
var_dump(Http::post('http://xxxxx/xxxx',$data,$options));


#（2）post请求②设置请求头参数（"Content-Type: application/json"）
#传入请求头参数,并设置请求头类型
$options = [
    'headers' => [
        'version:1.0' , 
        'token:bf76e9c8152e790f06bf9b370758ad16',
        'Content-Type: application/json'
        ]
    ];
var_dump(Http::post('http://xxx.com/get_user_info',$data,$options));

```

> 字符串加密（crypt）
```php
require dirname(__DIR__).'/vendor/autoload.php';
use tools\Crypt;

//加密
$encrypt = Crypt::getInstance()->crypt('tqIM9ko6So3ADRQ','ENDODE','skinrun');
var_dump(urlencode($encrypt));
//解密
$dencrypt = Crypt::getInstance()->crypt(urldecode($encrypt),'DECODE','skinrun');
var_dump($dencrypt);
```

> 图片转为base64（Image to base64）

```php
require dirname(__DIR__).'/vendor/autoload.php';
use tools\Image_Base64;

#图片转为base64
$base64 = Image_Base64::ImageToBase64('http://three-api.oss-cn-beijing.aliyuncs.com/xxx.png');

#base64转为图片文件
Image_Base64::Base64ToImage('./test.png',base64_decode($base64,true));

```

> 微信发红包算法

```php
require_once dirname(__DIR__).'/vendor/autoload.php';

use tools\Red;
#此算法在保证每个人至少能分到0.01元的前提下，分裂
$red = Red::getInstance()->getRandMoney_1(8,5,0.01);
$res2 = Red::getInstance()->getRandMoney_2(8,5,0.01);
var_dump($res,$res2);
```
