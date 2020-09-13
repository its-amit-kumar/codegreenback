<?php
if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}




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
        'user_cc' => "cc"
    ),
    'TokenKey'=>"codegreenback",
    'serverIP' => '127.0.0.1'

    );
class Config
{
    public static function get($path=null)
    {   if($path)
        {
            $config = $GLOBALS['config'];
            $path = explode('/',$path);
            foreach($path as $bit)
            {
                if(isset($config[$bit])){
                    $config = $config[$bit];
                }
            }
        return $config;
        }   
        return false;
    }

}
