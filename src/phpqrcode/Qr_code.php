<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/9
 * Time: 13:12
 */
namespace tools;


use org\phpqrcode\QRcode;

class Qr_code {

    public function __construct()
    {

    }

    /**
     * 生成二维码
     * @param $url
     * @param string $errorLevel 容错率
     * @param int $size  生成图片大小
     */
    public function saveToUrl($url,$errorLevel ="L",$size = 5)
    {
        return  QRcode::png($url, false, $errorLevel, $size);
    }
}