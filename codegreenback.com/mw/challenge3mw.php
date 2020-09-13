<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

// $user = $_SESSION["user"];
// $reciever = $_POST['opponent'];
// $challengeName = $_POST['challenge'];
// $cc = $_POST['cc'];

// $notif = new ChallengeNotification($user);


// $id = $notif->sendNotification3($user,$reciever,$challengeName,$cc);

// echo(json_encode(array('cid' => $id)));



/**
 * Verify the user if he can challenge or not for challenge 3
 * Refer sendNotification_mw.php for the workflow
 * 
 * Face/0ff workflow:
 * 1. verify the challenger if the challenger is eligible to challenge 
 * 2. if elegible then send a socket message to opponent
 * 3. if the opponent is not online send msg to challenger that opponent is not online
 * 4. if the opponent is online request opponent for accepting the challenge 
 * 5. if accepted first verify the opponent for elegibiblty
 * 6. if elegible send msg to both challenger and opponent the challenge is processing 
 * 7. in challenge processing : Insert data in challengeNotification , Challenge3 table through a approprate challenge3 class
 * 8. when processed send a msg with the required url to start challenge
 */

 /**
  * POST VARIABLES 
  * _cs_to : csrf token
  * user : current user
  * challengedUser : user to be challenged
  * challenge : challenge name
  * cc : cc bet
  */


if(Session::exists(Config::get('session/session_name')))
{
    $user   =  Session::get(Config::get('session/session_name'));
    if(isset($_POST['_cs_to']) && Token::check($_POST['_cs_to'],'csrf'))
    {
        if(isset($_POST['user']) && isset($_POST['challengedUser']) && isset($_POST['challenge']) && isset($_POST['cc']))
        {

            /**
             * Step 1 > Verifying current user for challenge Eligibility
             */
            $result = StaticFunc::verify_new_challenge($_POST['challengedUser'], $_POST['cc']);
            if($result['status'] == 1)
            {
                /**
                 * Challenger verified ! now create a temporary 
                 * object in redis for verification ;
                 */
                $redis_obj = new RedisDB();
                $token = $redis_obj->make_new_challenge3($_POST['challengedUser'] , (int)$_POST['cc']);
                if($token != false)
                {
                    $result['_vid'] = $token;
                    echo json_encode($result);
                }
                else
                {
                    echo json_encode(array('status' => 0, "msg" => "An Error Occurred ! Please Try Again"));
                }
            }
            else{
                echo json_encode($result);
            }

            
        }
    }
    else{
    echo "sorry";
    }
}
else{
    echo "sorry";
}







?>

