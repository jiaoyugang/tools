<?php
/**
 * @desc url图片地址转为base64
 * @params  img_url 图片链接
 * @return  string
 * @function ImageToBase64($img_url)
 *
 *
 * @desc base64数据库转为url
 * @params file_dir  文件保存位置
 * @params img_base64 base64数据
 * @return  string
 * @function Base64ToImage($file_dir,$img_base64)
 */
namespace tools;

class Image_Base64 {

    public function __construct()
    {
        
    }


    public function __destruct()
    {
        
    }
    /**
     * 将image_url转换为base64
     *
     * @param $img_url
     * @return string
     */
    public static function ImageToBase64($img_url)
    {
        try{
            $preg = "/^http(s)?:\\/\\/.+/";
            if(preg_match($preg,$img_url)){
                return base64_encode( file_get_contents($img_url) );
            }else{
                throw new Exception('获取图片数据异常');
            }
        }catch (\DivisionByZeroError  $exc){
            return ['code' => 400, 'msg' => $exc->getMessage()];
        }

    }

    /**
     * 将base64数据转换为image_url
     *
     * @param $file_dir
     * @param $img_base64
     * @return array
     */
    public static function Base64ToImage($file_dir,$img_base64)
    {
        try{
            //判断目录是否够存在，否则就创建
            if(!is_dir(dirname($file_dir)) || !file_exists(dirname($file_dir))){
                mkdir($file_dir,0755,true);
            }
            $res = file_put_contents($file_dir,base64_decode($img_base64));
            if(is_numeric($res)){
                return $img_base64;
            }else{
                throw new Exception('数据写入失败');
            }
        }catch (\Exception $exc){
            return ['code' => 400, 'msg' => $exc->getMessage()];
        }

    }
}