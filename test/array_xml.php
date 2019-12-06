<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 18:15
 */
require_once dirname(__DIR__).'/vendor/autoload.php';
use tools\Array_xml;


//数组转为xml
//var_dump(Array_xml::arrayToXml(['name' => 'gang','age' => 21 ,'sex' => 'man']));


//xml转为数组

var_dump(Array_xml::xmlToArray('<xml><name><![CDATA[gang]]></name><age>21</age><sex><![CDATA[man]]></sex></xml>'));
