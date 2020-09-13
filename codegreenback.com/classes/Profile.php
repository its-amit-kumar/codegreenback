<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class Profile 
{
    private $_db;
    
    function __construct()
    {
        $this->_db = DB::getInstance();
    }

    /*
    DATA TO BE SENT AS JSON FOR EACH CHALLENGE DATA:

    1. $status : Completed or Running
    2. $won : true or false
    3. $outGoingRequest : true or false  // true when this user has challenged and false when a challenge request is given to this user
    4. $opponent : username of the opponent
    5. $cc : code coins bet
    6. $redeemStatus : 0 or 1 //if $won is true
    7. $challengeid : challengeid   //send it when redeem is 0 
    8. $date : date of challenge
    9. $ccWon : amount of code coins won
    

    */

    public function getChallenge1_Data()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $data = $this->_db->getChallengedetails('challenge1',$user);

            if(!$data->error())
            {
                $data = $data->results();
                
                if(!empty($data))
                {
                    $dataToBeSend = array();

                    for($i = 0 ; $i<count($data); $i++)
                    {
                        $challenger = $data[$i]->challenger;
                        $opponent = $data[$i]->accepter;

                        if($user === $challenger)
                        {
                            $outGoingRequest = true;
                            $opponent = $opponent;
                        }
                        elseif($user === $opponent)
                        {
                            $outGoingRequest = false;
                            $opponent = $challenger;
                        }
                        else{
                            return 0;
                        }

                        $date = $data[$i]->accepter_start_time;
                        $cc = $data[$i]->cc;
                        $redeemStatus = '';
                        $challengeid = '';
                        $ccWon = '';

                        if(!empty($data[$i]->winner))
                        {
                            $status = "Completed";
                            if($data[$i]->winner == $user)
                            {
                                $won = true ;
                                if($data[$i]->cc_redeem == 0)
                                {
                                    $redeemStatus = false;
                                    $challengeid = $data[$i]->id;
                                    $ccWon = $data[$i]->cc_won;
                                }
                                elseif($data[$i]->cc_redeem == 1)
                                {
                                    $redeemStatus = true;
                                    $ccWon = $data[$i]->cc_won;
                                }
                            }
                            else
                            {
                                $won = false;
                            }

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => $won,
                                'redeemStatus' => $redeemStatus,
                                'challengeid' => $challengeid,
                                'ccWon' => $ccWon
                            );


                        }
                        else{
                            $status = "Running";

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => "NA"
                                
                            );
                        }
                    
                    }

                    return (json_encode($dataToBeSend));
                }
            }

        }
        return 0;
    }


    public function getChallenge2_Data()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $data = $this->_db->getChallengedetails('challenge2',$user);

            if(!$data->error())
            {
                $data = $data->results();
                
                if(!empty($data))
                {
                    $dataToBeSend = array();

                    for($i = 0 ; $i<count($data); $i++)
                    {
                        $challenger = $data[$i]->challenger;
                        $opponent = $data[$i]->opponent;

                        if($user === $challenger)
                        {
                            $outGoingRequest = true;
                            $opponent = $opponent;
                        }
                        elseif($user === $opponent)
                        {
                            $outGoingRequest = false;
                            $opponent = $challenger;
                        }
                        else{
                            return 0;
                        }

                        $date = $data[$i]->date_time;
                        $cc = $data[$i]->cc_bet;
                        $redeemStatus = '';
                        $challengeid = '';
                        $ccWon = '';

                        if(!empty($data[$i]->winner))
                        {
                            $status = "Completed";
                            if($data[$i]->winner == $user)
                            {
                                $won = true ;
                                if($data[$i]->cc_redeem == 0)
                                {
                                    $redeemStatus = false;
                                    $challengeid = $data[$i]->id;
                                    $ccWon = $data[$i]->cc_won;
                                }
                                elseif($data[$i]->cc_redeem == 1)
                                {
                                    $redeemStatus = true;
                                    $ccWon = $data[$i]->cc_won;
                                }
                            }
                            elseif ($data[$i]->winner == "NA") {
                                $redeemStatus = true;
                                $ccWon = $data[$i]->cc_won;
                                $won = true;
                            }
                            else
                            {
                                $won = false;
                            }

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => $won,
                                'redeemStatus' => $redeemStatus,
                                'challengeid' => $challengeid,
                                'ccWon' => $ccWon
                            );


                        }
                        else{
                            $status = "Running";

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => "NA"
                                
                            );
                        }
                    
                    }

                    return (json_encode($dataToBeSend));
                }
            }

        }
        return 0;
    }

    public function getChallenge3_Data()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $data = $this->_db->getChallengedetails('challenge3',$user);

            if(!$data->error())
            {
                $data = $data->results();
                
                if(!empty($data))
                {
                    $dataToBeSend = array();

                    for($i = 0 ; $i<count($data); $i++)
                    {
                        $challenger = $data[$i]->challenger;
                        $opponent = $data[$i]->opponent;

                        if($user === $challenger)
                        {
                            $outGoingRequest = true;
                            $opponent = $opponent;
                        }
                        elseif($user === $opponent)
                        {
                            $outGoingRequest = false;
                            $opponent = $challenger;
                        }
                        else{
                            return 0;
                        }

                        $date = $data[$i]->date_time;
                        $cc = $data[$i]->cc_bet;
                        $redeemStatus = '';
                        $challengeid = '';
                        $ccWon = '';

                        if(!empty($data[$i]->winner))
                        {
                            $status = "Completed";
                            if($data[$i]->winner == $user)
                            {
                                $won = true ;
                                if($data[$i]->cc_redeem == 0)
                                {
                                    $redeemStatus = false;
                                    $challengeid = $data[$i]->id;
                                    $ccWon = $data[$i]->cc_won;
                                }
                                elseif($data[$i]->cc_redeem == 1)
                                {
                                    $redeemStatus = true;
                                    $ccWon = $data[$i]->cc_won;
                                }
                            }
                            elseif ($data[$i]->winner == "NA") {
                                $redeemStatus = true;
                                $ccWon = $data[$i]->cc_won;
                                $won = true;
                            }
                            else
                            {
                                $won = false;
                            }

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => $won,
                                'redeemStatus' => $redeemStatus,
                                'challengeid' => $challengeid,
                                'ccWon' => $ccWon
                            );


                        }
                        else{
                            $status = "Running";

                            $dataToBeSend[$i] = array(
                                'status' => $status,
                                'outGoingRequest' => $outGoingRequest,
                                'opponent' => $opponent,
                                'date' => $date,
                                'cc' => $cc,
                                'won' => "NA"
                                
                            );
                        }
                    
                    }

                    return (json_encode($dataToBeSend));
                }
            }

        }
        return 0;
    }






    /*..............................................FUNCTION TO REDEEM CODECOINS...................................... */

        /*//  optimise theis function by using transaction and updating cookie efficiently ...//*/

    public function redeemCodeCoins($id , $challenge)
    {
      if(Session::exists(Config::get('session/session_name'))){
            $user = Session::get(Config::get('session/session_name'));
        }
        else{
            return 0;
        }

        $data = $this->_db->get($challenge , array('id','=',$id));
        
        if($data !== false)
        {
            $data = $data->results();
            if(empty($data))
            {
                return false;
            }

            if(count($data) == 1)
            {
            
                $winner = $data[0]->winner;
                if($user === $winner)
                {
                    // echo "this user is the winner";
                    if($data[0]->cc_redeem == 0)
                    {
                        //process cc update
                        $ccwon = $data[0]->cc_won;
                        $user_cc = $this->_db->get("cc",array('username' , '=', $user));                         //get user current codecoins
                        
                        if(is_int((int)$ccwon))
                        {
                            if($user_cc != false)
                            {
                                $user_cc = $user_cc->results();
                                if(!empty($user_cc))
                                {
                                    
                                    if($this->_db->sql_016($user, $id, $challenge,$ccwon,$user_cc[0]->cc))
                                    {
                                        //update user session for codecoins
                                        $CC_update = (int)$user_cc[0]->cc + (int)$ccwon;
                                        Session::put(Config::get('session/user_cc'), $CC_update);
                                        return true;
                                    }
                                    

                                }

                            }
                        
                        }
                
                    }
                }
            }
        }

        return false;
    }
    
}


?>
