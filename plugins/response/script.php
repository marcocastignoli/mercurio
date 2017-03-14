<?php
namespace response;

/**
*
* @method debug asd
* @method json asd
*/
class script {
    static $debug=array();

    static public function debug($data){
        self::$debug[]=$data;
    }

    public function json($_ARRAY){
        $_ARRAY['debug']=self::$debug;
        echo json_encode($_ARRAY);
    }
}
