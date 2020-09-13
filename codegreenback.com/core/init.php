<?php

if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}

//this init file initialises everithing 

session_start();                                                     //to start user session

$GLOBALS['config'] = array(
    'mysql' => array(                                                //mysql config
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'password',
        'db' => 'main_db'
    ),
    'remember' => array(                                            //remember me config
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800,

    ),
    'session' => array(                                                //session config
        'session_name' => 'user',
        'token_name' => 'token',
        'login_token_name' => 'login_token',
        'signup_token_name' => 'signup_token',
        'update_token_name' => 'update_token',
        'csrf_token_name' => 'csrf',
        'user_type' => 'user_type',
        'user_email' => 'email',
        'user_league' => 'league',
        'user_cc' => "cc",
        'user_img' => "img",
        'user_name' => "name"
    ),
    'TokenKey'=>"codegreenback",
    'serverIP' => '13.232.245.225',
    'mongodb' => array(
        'username' => 'amit123',
        'password' => 'amit123',
        'db' => 'events'
)
);
                                                             //requiring all the classes 
    /*
    
    require_once 'classes/Config.php';
    require_once 'classes/cookie.php';
    require_once 'classes/DB.php';
    
    or
    */

    spl_autoload_register(function($class)
    {   
	    /*fastest method for autoloading the classes */
	   
	     require_once(dirname(__DIR__).'/classes/'.$class.'.php');

	    
           });


    require_once(dirname(__DIR__,1).'/functions/sanitize.php');

    if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
        $hash = Cookie::get(Config::get('remember/cookie_name'));
        $hashmatches = DB::getInstance()->get('users_session',array('hash','=',$hash));
        if($hashmatches->count()){
            
            $user = new User($hashmatches->first()->user_id);
            $user->login();
        }
    }
