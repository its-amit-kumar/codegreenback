<?php

//this init file initialises everithing 

session_start();                                                     //to start user session

$GLOBALS['config'] = array(
    'mysql' => array(                                                //mysql config
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'Ayush170@',
        'db' => 'php_oop'
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
        'coderun_token_name' => 'coderun_token'
    )

    );
                                                             //requiring all the classes 
    /*
    
    require_once 'classes/Config.php';
    require_once 'classes/cookie.php';
    require_once 'classes/DB.php';
    
    or
    */

    spl_autoload_register(function($class){                                /*fastest method for autoloading the classes */
        require_once('classes/'.$class.'.php');
    });


    require_once('functions/sanitize.php');

    if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
        $hash = Cookie::get(Config::get('remember/cookie_name'));
        $hashmatches = DB::getInstance()->get('users_session',array('hash','=',$hash));
        if($hashmatches->count()){
            
            $user = new User($hashmatches->first()->user_id);
            $user->login();
        }
    }
