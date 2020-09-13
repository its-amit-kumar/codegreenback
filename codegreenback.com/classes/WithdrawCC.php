<?php

/**
 * This class is used for withdrawal procedure of code coins
 */

require_once dirname(__DIR__).'/core/init.php';



class WithdrawCC
{

    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }


    /**
     * getData() : it gives user cc and ccstats of past ten transactions
     * Status whether a user can request withdrawal based on cc 
     * @return array(
     *      'status' : //can withdraw or not
     *      'processing_id' :           //processing id for withdrawal if status is true
     *      'cc'     :     //current codecoins of user
     *      'past_trans : true|false
     *      'past_trans_data' :        //data of past transa if the above is true             
     *  )
     */


    /**
     * NOTE: WITHDRAWAL VALIDATION ORDER : 1.MEMBERTYPE : ELITE
     *                                     2.CC >= 100
     *                                     3.NO PAST REQUEST FOR WITHDRAWAL
     * 
     */
    public function getData()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $data = array(
                'status' => true,
                'processing_id' => "NA",
                'cc' => null,
                'past_trans' => false,
                'past_trans_data' => null,
                'ccstats' => null,
                'userData' => null 
            );
            $img_url = Session::get(Config::get('session/user_img'));
            if($img_url == null)
            {
                $img_url = '/public/img/avatar.png';
            }
            
            $data['userData'] = array(
                'username' => Session::get(Config::get('session/session_name')),
                'name' => Session::get(Config::get('session/user_name')),
                'email' => Session::get(Config::get('session/user_email')),
                'img_url' => $img_url
            );
            $user = Session::get(Config::get('session/session_name'));

                
                $user_data = $this->_db->sql_008($user);  
                if($user_data != null)
                {

                    if(Session::get(Config::get('session/user_type')) == 'non-elite' || $user_data->cc < 100)
                    {
                        $data['status'] = false;
                    }

                    $data['cc'] = $user_data->cc;
                    $data['ccstats'] = json_decode($user_data->ccstats);

                    /**
                     * update session cc value
                     */
                    Session::put(Config::get('session/user_cc'), $user_data->cc);

                    $past_withdraw = $this->_db->sql_009($user);
                    if($past_withdraw != null)
                    {
                        if($data['status'])
                        {
                            $data['status'] = false;
                        }

                        $data['past_trans'] = true;

                        $past_withdraw_date = $past_withdraw->date;
                        $past_withdraw = json_decode($past_withdraw->orderData);
                        $data['past_trans_data'] = array(
                            'processing_id' => $past_withdraw->processing_id,
                            'cc_redeem' => $past_withdraw->cc_to_withdraw,
                            'date' => $past_withdraw_date,
                        );
                    }
                    else if($past_withdraw == null)
                    {
                        if($data['status'])
                        {
                            /**
                             * get a new processing id 
                             */

                            $data['processing_id'] = Hash::generate_withdraw_id();
                        }
                    }
                }   
                
                return json_encode(($data));


        }
    }

    /**
     * makeTempReq() : this function will validate the date and 
     *                  store it for verification
     * @param : $data = JSON   Containing (status, account_no, cc_to_withdraw, contact, ifsc, processing_id, re_account_no)
     * 
     * returnCode: 1001 => non-elite member
     *             1002 => insufficient cc i.e. cc < 100 
     *             1003 => cc withdrawal more than current balance
     *             1004 => pending settlement  
     *             1005 => pin cannot be generated 
     *             1006 => Problem in email sending 
     */             

    public function makeTempReq($data)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $datavalidate = Validate::validate_001($data);
            if($datavalidate['status'] == -1)
            {
                return json_encode($datavalidate);
            }

            $data = json_decode($data);
            $processing_id = $data->processing_id;
            $cc_request = $data->cc_to_withdraw;
            $userEmail = Session::get(Config::get('session/user_email'));

            $user = Session::get(Config::get('session/session_name'));
            $usertype = Session::get(Config::get('session/user_type'));
            $usercc = Session::get(Config::get('session/user_cc'));

            if($usertype == 'non-elite')
            {
                return json_encode(array('status'=>0, 'error' => 1001));
            }

            if((int)$usercc < 100)
            {
                return json_encode(array('status'=>0, 'error' => 1002));
            }

            if((int)$usercc < (int)$data->cc_to_withdraw)
            {
                return json_encode(array('status'=>0, 'error' => 1003));
            }

            if($this->_db->sql_009($user) != null)
            {
                return json_encode(array('status'=>0, 'error' => 1004));
            }

            /**
             * store this data in temporary redis key for 10 min ang get a pin
             * send that pin to the user email for final verification
             */

            $redis_obj = new RedisDB();
            $pin = $redis_obj->redis_001(json_encode($data));
            if($pin != null)
            {
                /**
                 * send email to the user 
                 */
                if(Email::sendVerificationPin($user, $userEmail, $cc_request,$processing_id,$pin))
                {
                    return json_encode(array('status'=>1));
                }
                else
                {
                    return json_encode(array('status' => 0, 'error' => 1006));
                }

                
            }
            else
            {
                return json_encode(array('status'=>0, 'error' => 1005));
            }

            

        }
        else
        {
            return json_encode(array('status'=>0));
        }
    }


    /**
     * this function is to verify temp request for codecoin reddeem
     * @param $data = JSON : containing = (pin, processing_id)
     * @return array('status' => true|false|-1)                      -1 when pin did not matched
     */
    public function verifyTempReq($data)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $data = json_decode($data,true);
            $redis_obj = new RedisDB();
            $request_data = $redis_obj->redis_002($data['pin'], $data['processing_id']);

            if($request_data != false) 
            {
                $request_data = json_decode($request_data, true);
                $cc_withdrawal = (int)$request_data['cc_to_withdraw'];
                $ccStats = StaticFunc::update_cc_stats(-$cc_withdrawal, $request_data['processing_id']);
                if($ccStats != null)
                {
                    if($this->_db->sql_010($user, $request_data['processing_id'], json_encode($request_data),$ccStats, $cc_withdrawal ))
                    {
                        return json_encode(array('status' => true));
                    }
                }

            }
            else
            {
                return json_encode(array('status' => -1));
            }
        }


        return json_encode(array('status' => false));

    }

}





?>