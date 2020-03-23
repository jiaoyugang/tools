<?php
require dirname(__DIR__).'/vendor/autoload.php';
use tools\snowflake\Snowflake;

$snow =  Snowflake::instance(1,1);

for ( $i = 0; $i < 10; $i++) {
    var_dump($snow->nextId());
}

/**
    [root@skinrun tools]# php test/snowflake_test.php
    string(13) "5252501800960"
    string(13) "5252501800961"
    string(13) "5252501800962"
    string(13) "5252501800963"
    string(13) "5252501800964"
    string(13) "5252501800965"
    string(13) "5252501800966"
    string(13) "5252501800967"
    string(13) "5252501800968"
    string(13) "5252501800969"
 */


// var_dump(array_unique($id));
// echo session_create_id();

// var_dump(get_loaded_extensions());