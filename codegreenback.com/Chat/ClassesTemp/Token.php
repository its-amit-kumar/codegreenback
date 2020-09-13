<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class Token {
    public static function generate($name=null){
        if($name === 'login'){
            return Session::put(Config::get('session/login_token_name'),md5(uniqid()));           //token generator md5() -- md5 hash of a string ,uniqid()--generates unique id
        }
        else if($name === 'signup'){
            return Session::put(Config::get('session/signup_token_name'),md5(uniqid()));
        }
        else if($name === 'coderun'){
            return Session::put(Config::get('session/coderun_token_name'),md5(uniqid()));
        }
        else{
            return Session::put(Config::get('session/token_name'),md5(uniqid()));
        }
        
    }


    public static function check($token,$name=null){
        if($name==='login'){
            $tokenName = Config::get('session/login_token_name');
        }
        else if($name ==='signup'){
            //echo 'i am from signup ';
            $tokenName = Config::get('session/signup_token_name');
        }
        else if($name ==='coderun'){
            $tokenName = Config::get('session/coderun_token_name');
        }
        else {
            $tokenName = Config::get('session/token_name');
        }                                              //checks if token mathches the token stored on server side
        
        if(Session::exists($tokenName) && $token === Session::get($tokenName)){
            if ($name != 'coderun'){
            Session::delete($tokenName);
            }                                                   //deleting the token if confirmed
            return true;
        }
        return false;
    }

}
