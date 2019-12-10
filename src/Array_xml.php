<?php
/**
 * @params   array    data   数组
 * @return   string   xml    xml数据
 * @function static arrayToXml
 */

namespace tools;

class Array_xml {

    public function __construct()
    {

    }


    public function __destruct()
    {

    }

    /**
     * 数组转为xml
     * @param $data
     * @return string
     */
    public static function  arrayToXml($data){
         if(!empty($data) && is_array($data)){
             $xml = "<xml>";
             foreach ($data as $key=>$val)
             {
                 if (is_numeric($val)){
                     $xml.="<".$key.">".$val."</".$key.">";
                 }else{
                     $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                 }
             }
             $xml.="</xml>";
             return $xml;
         }else{
             return null;
         }
    }

    /**
     * 将XML转为array
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml){
         #禁止引用外部xml实体
         libxml_disable_entity_loader(true);
         $values = json_decode(
             json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA))
             , true);
         return $values;
    }

    /**
     * 生成32位随机字符串
     * @param int $length
     * @return string
     */
    public static function create_noncestr($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = '';
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }


}
