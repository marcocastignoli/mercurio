<?php
namespace math;
/**
* @title The math package
* @description All the mathematical stuff
* @method sum Sum two numbers
*/
class script {
    /**
    * @title Sum two numbers
    * @description Sum two numbers
    * @param int The first number
    * @param int The second number
    * @param hidden $user
    * @return int
    */
    public function sum($n1,$n2,$user){
        $result[0]=false;
        $r=0;
        if (is_int($n1) && is_int($n2) ) {
            $r = $n1+$n2;
        }
        \response\script::json( [
            'error'=>false,
            'data'=>$r
        ]);
    }
}
