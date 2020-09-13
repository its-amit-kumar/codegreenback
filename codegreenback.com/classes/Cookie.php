<?php
require_once dirname(__DIR__).'/core/init.php';

class Cookie {
    public static function exists($name){

        return (isset($_COOKIE[$name]));
    }

    public static function get($name){
        return $_COOKIE[$name];
    }

    public static function put($name,$value,$expiry){
        setcookie($name,$value,time()+$expiry,'/');
            $_COOKIE[$name] = $value;
            
        

    }

    public static function delete($name){
        //delete
        self::put($name,'',time()-1);
    }


    public static function makeCookieUser($fields){
        foreach($fields as $key=>$value){
            Cookie::put($key,$value,86400);

        }
    }

   
}