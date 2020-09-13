<?php


// require_once dirname(__DIR__,1).'/core/init.php';


// Predis\Autoloader::register();


class RedisDB
{
    private $_redis;
    public function __construct()
    {
        $this->_redis = new Redis();
        $this->_redis->connect('127.0.0.1', 6379);
        $this->_redis->auth("OrGv9eYP+h+wbHssKQhF6sEwUBSAbTERHiDETqoqRsBESZhbJcrSmOQUmwC2WIiumZVXbQ6UCfINvvVx");

    }
    

/**
 * this function is to verify 
 * challenge3 acceptance from the socket
 */
    public function verify_c3($accepter, $challenger,$cc, $token)
    {
        $key = $challenger.'_c3';
        if($this->_redis->exists($key))
        {
            $data = $this->_redis->hGetAll($key);
            if($data['challenger'] == $challenger)
            {
                if($data['opponent'] == $accepter)
                {
                    if($data['cc'] == $cc)
                    {
                        if($data['Token'] == $token)
                        {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }
}


?>
