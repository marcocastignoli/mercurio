<?php
namespace experiments;
/**
* @title Experiments
* @description All my experiments
* @method UPPERCASE Make text uppercase
* @method sha1 Generate sha1
* @method md5 Generate md5
*/
class script {
    /**
    * @title UPPERCASE
    * @description Write here something and it will return from the server upper case.
    * @param string The string
    * @return string
    */
    public function UPPERCASE($string){
        \response\script::json( [
            'error'=>false,
            'data'=>strtoupper($string)
        ]);
    }

    /**
    * @title Sha1
    * @description sha1ize
    * @param string The string
    * @return string
    */
    public function sha1($string){
        \response\script::json( [
            'error'=>false,
            'data'=>sha1($string)
        ]);
    }

    /**
    * @title md5
    * @description md5ize
    * @param string The string
    * @return string
    */
    public function md5($string){
        \response\script::json( [
            'error'=>false,
            'data'=>md5($string)
        ]);
    }
}
