<?php

    
use Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;
require_once $_SERVER['DOCUMENT_ROOT'].'/mail_token/vendor/autoload.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


class Token {
    public static function generate($name=null){
        if($name === 'login'){
            if(Session::exists(Config::get('session/login_token_name'))){
                Session::delete(Config::get('session/login_token_name'));  
            }
            return Session::put(Config::get('session/login_token_name'),md5(uniqid()));           //token generator md5() -- md5 hash of a string ,uniqid()--generates unique id
        }
        else if($name === 'signup'){

            if(Session::exists(Config::get('session/signup_token_name'))){
                Session::delete(Config::get('session/signup_token_name'));  
            }
            return Session::put(Config::get('session/signup_token_name'),md5(uniqid()));
        }
        else if($name === 'update_token'){
            if(Session::exists(Config::get('session/update_token_name'))){
                Session::delete(Config::get('session/update_token_name'));  
            }
            return Session::put(Config::get('session/update_token_name'),md5(uniqid()));
        }
        else if($name === 'csrf'){
            if(Session::exists(Config::get('session/csrf_token_name'))){
                Session::delete(Config::get('session/csrf_token_name'));  
            }
            return Session::put(Config::get('session/csrf_token_name'),md5(uniqid()));
        }
        else{
            if(Session::exists(Config::get('session/token_name'))){
                Session::delete(Config::get('session/token_name'));  
            }
            return Session::put(Config::get('session/token_name'),md5(uniqid()));
        }
        
    }


    public static function check($token,$name=null){     
        if($name === 'login'){
            $tokenName = Config::get('session/login_token_name');
        }
        else if($name === 'signup'){
            //echo 'i am from signup ';
            $tokenName = Config::get('session/signup_token_name');
        }
        else if($name ==='update_token'){
            
            $tokenName = Config::get('session/update_token_name');
        }
        else if($name ==='csrf'){
            
            $tokenName = Config::get('session/csrf_token_name');
        }
        else {
            $tokenName = Config::get('session/token_name');
        }                                              //checks if token mathches the token stored on server side
        
        if(Session::exists($tokenName) && $token === Session::get($tokenName)){
           // Session::delete($tokenName);                                                   //deleting the token if confirmed
            return true;
        }
        return false;
    }

    

    /*............jwt ...................*/


    public static function jwtGenerate($user,$time = 86400){
     
        $payload = array(
            "iss" => "http://codegreenback.com",
            "iat" => time(),
            "nbf" => 1357000000,
            "user"=> $user,
            "exp"=>time()+ $time,

        );

        return JWT::encode($payload , "codegreenback");

    }

    public static function jwtVerify($token)
    {
        try{
            $data = JWT::decode($token ,"codegreenback", array('HS256'));
            $data = (array) $data;
            //echo $data['user'];
          //  echo Session::get('user');
            if(Session::get('user') == $data['user']){
                return 1;
            }
            else{
                return -1;
            }


        }catch(Exception $e){
            return -1;
        }
    }


    public static function jwtEmailTokenVerify($token){
        try{
            $data = JWT::decode($token,'codegreenback',array('HS256'));
            $data = (array) $data;
            return $data;
        }catch(Exception $e){
            return 0;
        }
    }

public static function jwt_challenge_token($challengeid, $ques = array(), $user,$exptime = 86400)
    {
        $payload = array(
            "iss" => "http://codegreenback.com",
            "iat" => time(),
            "nbf" => 1357000000,
            "user"=> $user,
            "cid" => $challengeid,
            'ques' => $ques,
            "exp"=>$exptime,

        );

        return JWT::encode($payload , "codegreenback");
    }

    public static function jwt_challenge_verify($token)
    {
        try{
            $data = JWT::decode($token,'codegreenback',array('HS256'));
            $data = (array) $data;
            if(Session::get('user') != $data['user']){
                return 0;
            }
            return $data;
        }catch(Exception $e){
            return 0;
        }
    }

}
