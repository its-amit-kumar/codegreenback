<?php

require_once dirname(__DIR__).'/core/init.php';

class RedisDB
{
    private $_redis;
    public function __construct()
    {
        $this->_redis = new Redis();
        $this->_redis->connect('127.0.0.1', 6379); 
	$this->_redis->auth("OrGv9eYP+h+wbHssKQhF6sEwUBSAbTERHiDETqoqRsBESZhbJcrSmOQUmwC2WIiumZVXbQ6UCfINvvVx");
    }
    

    public function make_new_challenge3($opponent, $cc)
    {
        /**
         * this function is only for challenge3 
         * used for verification
         * 
         */

        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));
            $access_token = Hash::generate_challenge3_token();
            $this->_redis->hMSet($user."_c3",array(
                "challenger"    => $user,
                "opponent"      => $opponent,
                "cc"            => $cc,
                'Token'         => $access_token
            ));

            $this->_redis->expire($user."_c3", 60);
            
            return $access_token;
        }
        else{
            return false;
        }
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



    /**
     * redis_001 : funtion to store data temporarly  for 10 min 
     * to process cc redeem 
     * @param $data : json containing :(status, account_no, cc_to_withdraw, contact, ifsc, processing_id, re_account_no)
     * 
     */

    public function redis_001($data)
    {

        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $pin = Hash::pin_generate();                                //generate 6 digit pin

            $this->_redis->hMSet($user."_redeem",array(                                                               //hash : ayush123_redeem
                'user' => $user,
                'data' => $data,
                'pin' => $pin
            ));

            $this->_redis->expire($user."_redeem", 1200);                              //key will expire in 10 min
            
            return $pin;
        }

        return null;


    }


    /**
     * redis_002 : this function is to verify 6-digit pin 
     * for cc redeem
     * 
     * @param : $pin, $processing_id
     * @return data|false
     */

    public function redis_002($pin, $processing_id = null)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $key = $user.'_redeem';
            if($this->_redis->exists($key))
            {
                $data = $this->_redis->hGetAll($key);
                if($data['pin'] == $pin)
                {
                    return $data['data'];
                }
            }
        }
        return false;

    }
}


?>
