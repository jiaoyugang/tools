<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

use tools\helper\Red;


$red = Red::getInstance();
$res = $red->getRandMoney_1(8,5,0.01);
$res2 = $red->getRandMoney_2(8,5,0.01);
var_dump($res,$res2);
