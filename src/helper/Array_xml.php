<?php
namespace tools\helper;

/**
 * @params   array    data   数组
 * @return   string   xml    xml数据
 * @function static arrayToXml
 */
final class Array_xml
{

    private static $instance;

    /**
     * 禁止实例化
     */
    private function __construct()
    {}

    /**
     * 私有属性的克隆方法 防止被克隆
     */
    private function __clone()
    {}

    /**
     * 静态方法 用以实例化调用
     */
    public static function getInstance()
    {

        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 数组转为xml
     * @param $data
     * @return string
     */
    public function arrayToXml($data)
    {
        if (!empty($data) && is_array($data)) {
            $xml = "<xml>";
            foreach ($data as $key => $val) {
                if (is_numeric($val)) {
                    $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
                } else {
                    $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
                }
            }
            $xml .= "</xml>";
            return $xml;
        } else {
            return null;
        }
    }

    /**
     * 将XML转为array
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        #禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(
            json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA))
            , true);
        return $values;
    }

}
