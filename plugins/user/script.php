<?php
namespace user;
/**
* @title Manage users
* @description Manage all of your users
* @method login Login with the specified user
* @method info Get logged user's information
* @method logout Logout
*/
class script {
    /**
    * @title Login
    * @description Enter your credentials
    * @param string Username
    * @param string Password
    * @param hidden $token
    * @return string
    */
    public function login($username,$password,$token=false){
        if ($token=="super_secret_token") {
            \response\script::json( [
                'error'=>false,
                'data'=>'You are logged in'
            ]);
        } else if ($username=="marco" &&  $password=="12345") {
            \response\script::json( [
                'error'=>false,
                'data'=>'Logged in',
                'cookies'=>[
                    '$token'=>"super_secret_token"
                ]
            ]);
        } else {
            \response\script::json( [
                'error'=>false,
                'data'=>'Wrong credentials'
            ]);
        }
    }

    /**
    * @title Informations
    * @description These are your credentials
    * @param hidden $token
    * @return string
    */
    public function info($token=false){
        if ($token=="super_secret_token") {
            \response\script::json( [
                'error'=>false,
                'data'=>'Marco Castignoli'
            ]);
        } else {
            \response\script::json( [
                'error'=>false,
                'data'=>'You\'re not logged in'
            ]);
        }
    }

    /**
    * @title Logout
    * @description Logout
    * @param hidden $token
    * @return string
    */
    public function logout($token=false){
        if ($token=="super_secret_token") {
            \response\script::json( [
                'error'=>false,
                'data'=>'Ok, logging you out',
                'cookies'=>[
                    '$token'=>""
                ]
            ]);
        } else {
            \response\script::json( [
                'error'=>false,
                'data'=>'You\'re not logged in'
            ]);
        }
    }
}
