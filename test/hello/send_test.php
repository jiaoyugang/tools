<?php
require dirname(__DIR__).'/../vendor/autoload.php';
use tools\amq\rabbitmq\Rabbitmq as RabbitmqRabbitmq;

$rabbit = new RabbitmqRabbitmq('localhost','5672','guest','guest');
$rabbit->send('hello',json_encode(['age' => 21 ,'uid' => 12 ,'order_no' => 'DD2232GRDSHT']));