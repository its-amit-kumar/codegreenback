<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
// middleware to send notification to the user and feed in  to the db

/* 

* Check for both user type
* Check for CC amount of challenger
* Check for total challenges if non elite user is challenging

NOTE: APPLY GOOGLE CAPTCHA VARIFICATION AND CSRF VERIFICATION HERE 


*/


if(Session::exists(Config::get('session/session_name')))
{
    $user   =  Session::get(Config::get('session/session_name'));
    if(isset($_POST['_cs_to']) && Token::check($_POST['_cs_to'],'csrf'))
    {
        if(isset($_POST['user']) && isset($_POST['challengedUser']) && isset($_POST['challenge']) && isset($_POST['cc']))
        {
            $verification_status = StaticFunc::verify_new_challenge($_POST['challengedUser'], $_POST['cc']);
            if($verification_status['status'] == 1)
            {
                // perform further processing
                $notification = new ChallengeNotification($user);
                if($notification->sendNotification($user, $_POST['challengedUser'], $_POST['challenge'], $_POST['cc']))
                {
                    /**
                     *  echo out that challenge has been successfully made
                     */
                    echo json_encode(array(
                        'status' => 1, 
                        'msg'   => "You Have Successfully Challenged ".$_POST['challengedUser']." In ".$_POST['challenge'],
                        'challengeStatus' => array(
                                                'status'=> true,'challengeName'=> $_POST['challenge'] , 'time'=> date("Y-m-d h:i:sa", time())
                                            )
                    ));
                }

            }
            else
            {
                echo json_encode($verification_status);
            }
            
                
        }
        else
        {
            echo 0;
                
        }
            
    }
    else{
        echo "Token Not Verified";
    }
}



// $user = new User();
// if($user->isLoggedIn()){
    
   
//     if(isset($_POST['user']))
//     {
//         $userStats = $user->statsData();                                //stats table data of user
//         $userCC = $user->cc();                                          //cc table data of user
//         if(isset($_POST['challengedUser']))
//         {
//             $challengedUser = new User($_POST['challengedUser']);
//             if($challengedUser)                                          //if challenged user exists
//             {
//                 $challengedUserStats = $challengedUser->statsData();    
//                 //$challengedUserCc = $challengedUser->cc();
//                 if($userCC->cc < $_POST['cc'])
//                 {
//                     echo 0;
                    
//                 }
//                 else
//                 {   
                    
//                     $notification = new ChallengeNotification($challengedUserStats->username);

//                     if($notification->sendNotification($userStats->username,$challengedUserStats->username,$_POST['challenge'],$_POST['cc'])){
//                        echo '1';

//                        $newCC = $userCC->cc - $_POST['cc'];                              //update user code coins
//                        $user->update_user_cc($newCC);
//                     }
//                     else{
//                         echo 0;
                        
//                     }
//                 }
                
//             }
//             else
//             {
//                 echo 0;
                
//             }
//         }
//     }
//     else {
//         Redirect::to("../index");
//     }
 

// }
// else
// {                                          //mostly the user will be logged in 
//     Redirect::to("../index");
// }


?>