<?php

/* this class is only for static Helper Functions */

require_once dirname(__DIR__).'/ClassesTemp/DB.php';

class StaticFunc 
{



        /**
     * FUNTIONS FROM HERE ARE FOR SOCKET ONLY
     * NOTE: SINCE THE SOCKET IS BUILT THROUGH CLI 
     * IT CANNOT ACCESS ANYTHING FROM SESSION, COOKIE, GET,POST ETC
     */

    public static function verify_challenge_accepter($username, $cc_bet)
    {
        /**
         * this function verifies that 
         * if the given user can accept the challenge or not 
         * for the first time
         */

        $db = new DB();
        $data = $db->sql_001($username);
        if($data != false)
        {
            /**
             * check user type 
             * if non- elite check for challenge limit
             * if success then check for cc in account
             * return appropriate msg on error
             * 
             * Error code : 801 : challenge limit exceeded
             *              802 : not enough code coins
             */

            if($data->user_type == '0' && $data->totalChallenges > 5)
            {
                return array('status' => 0, 'errorcode' => 801);
            }
            else if((int)$data->cc < $cc_bet)
            {
                return array('status' => 0 , 'errorcode' => 802);
            }
            
            
            return array('status' => 1);
            
        }
    }
    

	
    public static function make_new_faceoff($challenger, $accepter, $cc)
    {
        $db = new DB();

        $cid = $db->sql_003($challenger, $accepter, $cc);

        $db->close();

        return $cid;
    }

    /**verify_challenge_accepter
      * SOCKET PROGRAMMING FUNCTIONS END
      */

}


?>
