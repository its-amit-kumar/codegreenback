<?php

require_once dirname(__DIR__,1).'/core/init.php';

require_once dirname(__DIR__,1).'/functions/getHeader.php';
$header = getBearerToken();

if($header)
{
    //echo $header;
    if(Token::jwtVerify($header) == -1)
    {
        echo json_encode(-1);
        exit;
    }
}

/**
 * THIS MIDDLEWARE IS FOR NAVBAR 
 * ALL THE REQUEST AND EVERYTHIN IS SEARCHED IN ONE GO
 */



 /**
  * For User Challenge notification
   * and challenge requests 
   * @param $_GET['_ser'] = service => _challenge
   * @param $_GET['_tr'] => totalrequest which are present at the client
   * @param $_GET['_tc'] => totalChallenge which are present at the client
   * 
   * return : _cr = challengeRequest     i.e. challenge request for this user from others
   *          _uc = userRequest      i.e request made by the current user
  */

if(isset($_GET['_ser']) && isset($_GET['_tr']) && isset($_GET['_tc']))
{
    if($_GET['_ser'] == "_challenge")
    {
        $data_to_send = array(
            "status" => 1,
            "_cr" => array("status" => 0),
            "_uc" => array("status" => 0)

        );
        /**
         * get current user challenge request 
         */
        $notification = new ChallengeNotification($_SESSION[Config::get('session/session_name')]);
        $datas = $notification->your_request();
        if(true)
        {
            if(count($datas) == 0 )
            {
                $data_to_send["_uc"] = array(
                "status" => 1,
                "data" => array()
                );

            }
            else{
                $req = [];

                foreach ($datas as $data) 
                {
                $req[] = array
                    (
                        'id'=>$data->id,
                        'challenger'=>$data->challenger,
                        'accepter'=>$data->accepter,
                        'challengeName'=>$data->challengeName,
                        'cc'=>$data->cc,
                        't_o_r'=>$data->t_o_r,
                        'status'=>$data->status,
                        'c_status'=>$data->c_status
                    );
                }

                $data_to_send["_uc"] = array(
                    "status" => 1,
                    "data" => $req
                );
            }

        }

    $datas = $notification->getNotification();
    if($_GET['_tr'] != count($datas))
    {
        $req_1 = [];
        foreach ($datas as $data) {
            $req_1[] = array(
                'id'=>$data->id,
                'challenger'=>$data->challenger,
                'accepter'=>$data->accepter,
                'challengeName'=>$data->challengeName,
                'cc'=>$data->cc,
                't_o_r'=>$data->t_o_r,
                'status'=> $data->status,
                'a_status'=>$data->a_status,
		'challenger_img' => $data->c_img
            );
        }

    $data_to_send["_cr"] = array(
        "status" => 1,
        "data" => $req_1
    );

    }

        echo json_encode($data_to_send);

    }
    
}






?>
