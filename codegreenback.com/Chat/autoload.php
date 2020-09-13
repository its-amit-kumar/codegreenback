<?php
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
        'update_token_name' => 'update_token',
        'csrf_token_name' => 'csrf',
        'user_type' => 'user_type',
        'user_email' => 'email',
        'user_league' => 'league',
        'user_cc' => "cc"
    ),
    'TokenKey'=>"codegreenback"

    );

    
  spl_autoload_register(function($class)
    {                                /*fastest method for autoloading the classes */
        require_once('ClassesTemp/'.$class.'.php');
    });


