<?php

/* this class is only for static Helper Functions */

require_once dirname(__DIR__).'/core/init.php';

class StaticFunc 
{
    /*   make helper static functions here                                           */

    public static function sendEmailVerification($source)
    {
        $username = htmlspecialchars(stripslashes(trim($source['username'])));
        $name = htmlspecialchars(stripslashes(trim($source['name'])));
        $email = htmlspecialchars(stripslashes(trim($source['email'])));
        $password = trim($source['password']);
        $token = Hash::unique();
        $jwt = Token::jwtGenerate($username,420);

        $url = "https://www.codegreenback.com/confirm/verifyEmail.php?name=".$username."&vid=".$token."&email=".$email."&token=".$jwt ;
        if(Email::sendAuthEmail($name,$url,$email)){
            StaticFunc::makeTempUser($username,$name,$email,$password,$token);
        }
    }

    public static function makeTempUser($username,$name,$email,$password,$token)
    {
        $db = DB::getInstance();
        if($db->insert('emailVerify',array(
            'username'=>$username,
            'email'=>$email,
            'password'=>$password,
            'name' =>$name,
            'token'=>$token
        ))){
            return true;
        }else{
            return false;
        }
    }

    public static function verifyTempUser($username,$email,$token){
        $db = DB::getInstance();
        $data = $db->get('emailVerify',array('token','=',$token))->results();
        // print_r($data);
        if(!empty($data))
        {
            if(strtotime($data[0]->time)+420 > strtotime(date('Y-m-d H:i:s',time())) && $username == $data[0]->username && $email == $data[0]->email){

                $makeUser = array(
                    'username' => $username,
                    'password' => $data[0]->password,
                    'email' => $email,
                    'name' => $data[0]->name,
                );

                $user = new User();
                if($user->create_newuser($makeUser)){
                    $db->delete('emailVerify',array('token','=',$token));
                    return true;
                }
                
            }
            $db->delete('emailVerify',array('token','=',$token));
            return false;
        }
        return false;
    }


  /*      get All the  User matched with the given string        */

    public static function searchUserdata($str){
	$db = DB::getInstance();
        $data = $db->getUserLike($str);
        $user = Session::get(Config::get('session/session_name'));
        $arr = array();
        foreach ($data as $key => $value) {
            if($value->username != $user)
            {
                $arr[] = $data[$key];
                
            }
        }

        return $arr;
    }


    /* get  friends table  data  */
    
    public static function searchedUser_Friends($name){
        $db = DB::getInstance();
        $data = $db->get('friends',array('username','=',$name))->results();
        if($data){
            return $data;
        }
        else{
            return 0;
        }
      
        
    }



    /*   function to send friend request to other user   */

    public static function sendFriendRequest($reciever)
    {
       if(!Session::exists(Config::get('session/session_name'))){
            return 0;
        }
       $user = Session::get(Config::get('session/session_name'));

        $userFriends = StaticFunc::searchedUser_Friends($user);
        $userFriends = json_decode($userFriends[0]->friendDetails);
        if(!empty($userFriends->blocked))
        {
            if(array_search($reciever,$userFriends->blocked) !== false)
            {
                return 0;
            }
        }

        if($recieverFriends = StaticFunc::searchedUser_Friends($reciever)){
            $recieverFriends = json_decode($recieverFriends[0]->friendDetails);

            if(!empty($recieverFriends->blocked))
            {
                if(array_search($user,$recieverFriends->blocked) !== false){
                    return 0;
                }
            }
            if(!empty($recieverFriends->friends)){
                if(array_search($user,$recieverFriends->friends) !== false){
                    return 0;
                }
            }
    
            if(!empty($recieverFriends->requests)){
               // print_r(array_search($user,$recieverFriends->requests));
                if(array_search($user,$recieverFriends->requests)!== false){
                  //  print_r('found');
                    return 0;
                }
              
            }
            $addreq = $recieverFriends->requests;
            $addreq[] = $user;
            $recieverFriends->requests = $addreq;
            $db = DB::getInstance();
            $db->update('friends',$reciever,array('friendDetails'=>json_encode($recieverFriends))); 
            // return 1;
            return StaticFunc::friendshipStatus($reciever);                                                //if request sended successfully
        }
        else{
            return 0;
        }
        
       // print_r($recieverFriends);

 
    }


    /*   Delete friend Request sent by the current user */

    public static function deleteFriendRequest($searched)
    {
        if(!Session::exists(Config::get('session/session_name'))){
            return 0;
        }
       $user = Session::get(Config::get('session/session_name'));

        $userFriends = StaticFunc::searchedUser_Friends($user);
        $userFriends = json_decode($userFriends[0]->friendDetails);

        if($recieverFriends = StaticFunc::searchedUser_Friends($searched)){
            $recieverFriends = json_decode($recieverFriends[0]->friendDetails);

            if(!empty($recieverFriends->friends)){
                if(array_search($user,$recieverFriends->friends) !== false)
                {
                    return 0;
                }
            }

            if(!empty($recieverFriends->requests)){
                if(array_search($user,$recieverFriends->requests) !== false)
                {
                    $req = array();
                    //delete requests
                    for($i = 0 ;$i < count($recieverFriends->requests) ; $i++)
                    {
                        if($recieverFriends->requests[$i] == $user)
                        {
                            continue;
                        }else{
                            $req[] = $recieverFriends->requests[$i];
                        }
                    }
                    
                    $recieverFriends->requests = $req;
                    $db = DB::getInstance();
                    $db->update('friends',$searched,array('friendDetails'=>json_encode($recieverFriends))); 
                    // return 1;
                    return StaticFunc::friendshipStatus($searched);                                                    //if request deleted successfully


                }
            }
            return 0;

        }
    }



    /* Block user */
    public static function BlockUser($user_to_be_blocked){

        $db = DB::getInstance();
        $flag = false;

        $user = Session::get(Config::get('session/session_name'));
        $userFriends = json_decode((StaticFunc::searchedUser_Friends($user))[0]->friendDetails);
        $blocked = $userFriends->blocked;
        $blocked[] = $user_to_be_blocked;

        $userFriends->blocked = $blocked;
        if(!empty($userFriends->friends))
        {
            if(array_search($user_to_be_blocked,$userFriends->friends) !== false)
            {
                $userFriends->friends = StaticFunc::removeElement($user_to_be_blocked,$userFriends->friends);
            }
        }

        $user_to_be_blocked_Friends = json_decode((StaticFunc::searchedUser_Friends($user_to_be_blocked))[0]->friendDetails);
        if(!empty($user_to_be_blocked_Friends->friends))
        {
            if(array_search($user,$user_to_be_blocked_Friends->friends) !== false)
            {
                $user_to_be_blocked_Friends->friends = StaticFunc::removeElement($user,$user_to_be_blocked_Friends->friends);
                $flag = true;
               
            }
        }
        if(!empty($user_to_be_blocked_Friends->requests))
        {
            if(array_search($user,$user_to_be_blocked_Friends->requests) !== false )
            {
                $user_to_be_blocked_Friends->requests = StaticFunc::removeElement($user,$user_to_be_blocked_Friends->requests);
                $flag = true;
                
            }
        }

        if($flag){$db->update('friends',$user_to_be_blocked,array('friendDetails'=>json_encode($user_to_be_blocked_Friends)));}


        
        if($db->update('friends',$user,array('friendDetails'=>json_encode($userFriends))) ){
            // return 1;
            return StaticFunc::friendshipStatus($user_to_be_blocked);
        } 
        else{
            return 0;
        }
        


    }

    /*  unblock user */
    public static function unblockUser($user)
    {
        if(!Session::exists(Config::get('session/session_name')))
        {
            return 0;
        }

        $currentUser = Session::get(Config::get('session/session_name'));
        if($currentUserFriends =StaticFunc::searchedUser_Friends($currentUser)){
            $currentUserFriends = json_decode($currentUserFriends[0]->friendDetails);
        }
        else{
            return 0;
        }
        


        if(!empty($currentUserFriends->blocked))
        {
            if(array_search($user,$currentUserFriends->blocked) !== false)
            {
                $currentUserFriends->blocked = StaticFunc::removeElement($user,$currentUserFriends->blocked);
                $db = DB::getInstance();
                if($db->update('friends',$currentUser,array('friendDetails'=>json_encode($currentUserFriends))) ){
                    // return 1;
                    return StaticFunc::friendshipStatus($user);
                } 
                else{
                    return 0;
                }
            }
        }
        else{
            return 0;

        }
        
    }


    /* General function to remove an element from an array  */
    public static function removeElement($ele,$arr)
    {
        $tem = array();
        for($i = 0 ;$i<count($arr) ;$i++)
        {
            if($arr[$i] == $ele)
            {
                continue;
            }
            else{
                $tem[] = $arr[$i];
            }
        }
        return $tem;
    }



    /*  .... function to deal with get user request , accept user friend requests and delete user friend requests                       */

    public static function getFriendReq()
    {
        if(!Session::exists(Config::get('session/session_name')))
        {
            return 0;
        }

        $user = Session::get(Config::get('session/session_name'));
        $userFriends = json_decode((StaticFunc::searchedUser_Friends($user))[0]->friendDetails);

        if(!empty($userFriends->requests)){
            return json_encode($userFriends->requests);
        }
        else{
            return 0;
        }
    }


    public static function acceptFriendReq($user)                         // $user is the person whom frnd request is accepted
    {
        /*   this function need optimisation in accepting friends of both the users       */

        if(!Session::exists(Config::get('session/session_name')))
        {
            return 0;
        }

        $currentUser = Session::get(Config::get('session/session_name'));
        if($currentUserFriends = StaticFunc::searchedUser_Friends($currentUser)){
            $currentUserFriends = json_decode($currentUserFriends[0]->friendDetails);
        }
        else{
            return 0;
        }

        if(!empty($currentUserFriends->requests))
        {
            if(array_search($user,$currentUserFriends->requests) !== false)
            {
                $currentUserFriends->requests = StaticFunc::removeElement($user,$currentUserFriends->requests);
                $currentUserFriends->friends[] = $user;
                $currentUserFriends->friends = array_unique($currentUserFriends->friends);

                if($userFriends = StaticFunc::searchedUser_Friends($user)){
                    $userFriends = json_decode($userFriends[0]->friendDetails);

                    $userFriends->friends[] = $currentUser;
                    $userFriends->friends = array_unique($userFriends->friends);
                    if(!empty($userFriends->requests))
                    {
                        if(array_search($currentUser,$userFriends->requests) !== false)
                        {
                            $userFriends->requests = StaticFunc::removeElement($currentUser,$userFriends->requests);
                        }
                    }

                    $db = DB::getInstance();

                    $db->update('friends',$currentUser,array('friendDetails'=>json_encode($currentUserFriends)));
                    $db->update('friends',$user,array('friendDetails'=>json_encode($userFriends)));
		    $db->insert('generalNotifications',array('username'=>$user,'notification'=>$currentUser.' accepted your friend request.'));

                    return 1;
                }
                else{
                    return 0;
                }
            }
        }
        else{
            return 0;
        }
      
    }


    /* decline friend request for current user */
    public static function declineFriendReq($user)
    {
        if(!Session::exists(Config::get('session/session_name')))
        {
            return 0;
        }

        $currentUser = Session::get(Config::get('session/session_name'));
        if($currentUserFriends = StaticFunc::searchedUser_Friends($currentUser))
        {
            $currentUserFriends = json_decode($currentUserFriends[0]->friendDetails);
            if(!empty($currentUserFriends->requests))
            {
                if(array_search($user,$currentUserFriends->requests) !== false)
                {
                    $currentUserFriends->requests = StaticFunc::removeElement($user,$currentUserFriends->requests);
                    $db = DB::getInstance();
                    if($db->update('friends',$currentUser,array('friendDetails'=>json_encode($currentUserFriends)))){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
            }
        }
        else{
            return 0;
        }
    }


    /*  function to get user General notification */
    
    public static function getGeneralNotification()
    {
        if(!Session::exists(Config::get("session/session_name"))){
            return 0;
        }
        $user = Session::get(Config::get('session/session_name'));
        $db = DB::getInstance();
        $data = $db->get("generalNotifications",array("username","=",$user))->results();
        if(!empty($data)){
            return json_encode($data);
        }
        else{
            return 0;
        }
        
    }


    /* delete general notification */
    public static function deleteGeneralNotification()
    {
        if(!Session::exists(Config::get("session/session_name"))){
            return 0;
        }
        $user = Session::get(Config::get('session/session_name'));
        $db = DB::getInstance();
        $db->delete("generalNotifications",array("username","=",$user));
    }

    

    /* profilePage general stats */

    public static function profileGeneralStats(){
        $db = DB::getInstance();

        if(!Session::exists(Config::get("session/session_name"))){
            return 0;
        }

        $user = Session::get(Config::get('session/session_name'));

        $data = $db->getProfileGeneralData($user);
       
        if($data)
        {
            // print_r($data[0]);
             $totalQues = 0;
             $league = '';
             if(!empty($data[0]->challenge1_que))
             {
                 $totalQues += count(json_decode($data[0]->challenge1_que));
             }
              if(!empty($data[0]->challenge2_que))
             {
                 $totalQues += count(json_decode($data[0]->challenge2_que));
             }
              if(!empty($data[0]->challenge3_que))
             {
                 $totalQues += count(json_decode($data[0]->challenge3_que));
             }
             
      
             $arr = array(
                'user' => $data[0]->username,
                'overallPoints'=>$data[0]->overAllPoint,
                'league'=>$data[0]->league,
                'overallRank' => $data[0]->overAllRank,
                'totalQues' => $totalQues,
                'challengesWon'=>$data[0]->challengesWon,
                'totalChallenges'=>$data[0]->totalChallenges,
                'img_url'=>$data[0]->user_image
             );

             return json_encode($arr);
        }
        else{
            return 0;
        }

    }

    public static function getLeague($sym)
    {
        $league = "";
        switch ($sym) {
                case 'b':
                    $league = "Bronze";
                    break;
                
                case 's':
                    $league = "Silver";
                break;

                case 'g':
                    $league = "Gold";
                break;

                case 'd':
                    $league = "Diomand";
                break;

                case 'p':
                    $league = "Platinum";
             }
        return $league;
    }

    public static function friendshipStatus($user)
    {

    $currentUser = Session::get(Config::get('session/session_name'));
    $userFriends = json_decode((StaticFunc::searchedUser_Friends($currentUser))[0]->friendDetails);

    $searchedUser =  trim($user);
    $data = StaticFunc::searchedUser_Friends($searchedUser);

    /* check weather this user has blocked or not  */
    if(!empty($userFriends->blocked)){

        if(array_search($searchedUser,$userFriends->blocked) !== false){
            $blocked = 'true';                                                                   //whether this logged in user has blocked the searched user or not
        }else{
            $blocked = 'false';
        }

    }
    else{
        $blocked = 'false';
    }
    

  //  print_r($data);
    if($data)
    {
        $friendDetails = json_decode($data[0]->friendDetails);
      // print_r($friendDetails);
        if(!empty($friendDetails->friendDetails)){
            for($i = 0 ; $i< count($friendDetails->blocked) ; $i++)
            {
                if(($friendDetails->blocked)[$i] == $currentUser)
                {
                    $status = array('status'=>'Blocked','this_user_has_blocked'=>$blocked);
                    return $status;
                    
                }
            }
        }

        if(!empty($friendDetails->friends) )
        {
            for($i = 0; $i<count($friendDetails->friends) ; $i++ )
            {
                if(($friendDetails->friends)[$i] == $currentUser)
                {
                    $status = array('status'=>"friend",'this_user_has_blocked'=>$blocked);
                    return $status;
                }
            }
        }

        if(!empty($friendDetails->requests) )
        {
            for($i = 0 ;$i<count($friendDetails->requests) ; $i++)
            {
                if(($friendDetails->requests)[$i] == $currentUser){
                $status = array('status' => "requested",'this_user_has_blocked'=>$blocked);
                return $status;
                }
            }
		return array('status'=>"Not Friends",'this_user_has_blocked'=>$blocked);
        }
        else{
            return array('status'=>"Not Friends",'this_user_has_blocked'=>$blocked);

        }


    }

    }

    /* Profile Page User Friends */                              

    /* ..........................
    .............................................
    ..................................................MODIFY THIS FUNCTION FOR USER IMAGE URL.............................
                                                                                          ........................................... */

    public static function profileUserFriends()
    {
        if(!Session::exists(Config::get('session/session_name')))
        {
            return 0;
        }

        $user = Session::get(Config::get('session/session_name'));
        $friends = StaticFunc::searchedUser_Friends($user);
        if($friends)
        {
            $friends = json_decode($friends[0]->friendDetails)->friends;
            if($friends){
                $db = DB::getInstance();
                return json_encode($db->getUserBasicInfo($friends));
            }
            else{
                return 0;
            }
            
        }
        else{
            return 0;
        }
        
    }


    /* ...........funtion for searched user details ..............     */

    public static function getSearchedUserInfo($username)
    {           
        $user = new User($username);
        $data = $user->data();
        if(!empty($data))
        {   
            $challengeStatus = false;
            $challengeName = '';
            $challengeTime = '';

            $data = $user->data();
           // print_r($data);

            // challenge status
            $notification = new ChallengeNotification($_SESSION[Config::get('session/session_name')]);
         if($notifications = $notification->your_request()){
             foreach ($notifications as  $notification) {
                // print_r($notification);
                 if($notification->accepter == $username){
                    $challengeStatus = "true";
                    $challengeName = $notification->challengeName;
                    $challengeTime = $notification->time;
                 break;
                 }
             }
         }

            //friend Status
            $friendStatus = StaticFunc::friendshipStatus($username);
            // print_r($friendStatus);

            $profileData = $user->ProfileData($username);
            // print_r($profileData);

            if(empty($data->user_image))
            {
            $img_url = 'public/img/avatar.png';
            }
            else
            {
            $img_url = $data->user_image;
            }

            $canChallenge = true;
            if($_SESSION[Config::get('session/user_type')] == "non-elite")
            {
                $db = DB::getInstance();
                $totalChallenges = $db->get('user_challenge_stats',array("username",'=',$_SESSION['user']))->results();
                if($totalChallenges[0]->totalChallenges >= 3)
                {
                    $canChallenge = false;
                }
            }

            $info = array(
                'status'          => 1,
                'username'        => $data->username,
                'name'            => $data->name,
                'img'             => $img_url,
                'rank'            => $profileData[0]->overAllRank,
                'league'          => $profileData[0]->league,
                'points'          => $profileData[0]->overAllPoint,
                'totalChallenges' => $profileData[0]->totalChallenges,
                'friendStatus'    => $friendStatus,
                'challenge'       => array('status'=>$challengeStatus,'challengeName'=>$challengeName,'time'=>$challengeTime),
                'canChallenge'    => $canChallenge,
                'user_type'       => $data->memberType  
            );

            return json_encode($info);
        }

        return json_encode(array("status" => 0, "No User Found"));

   
    }




    /*    Verification of google captcha                  */

    public static function VerifyCaptcha($token)
    {
        $url = "https://www.google.com/recaptcha/api/siteverify";

        $data = [
            'secret'=> "6Lf3xfcUAAAAAEWs_zJ6zquND28Bo46evZuz2kg-",
            'response'=> $token,
            'remoteip'=> $_SERVER['REMOTE_ADDR']
        ];

        $options = array(
            'http' => array(
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'method'=> 'POST',
                'content' => http_build_query($data)
            )
            );

        $context = stream_context_create($options);
        $response = file_get_contents($url,false,$context);

        $res = json_decode($response);
        // print_r($res);

        if($res->success === true && $res->score > 0.5)
        {
            return true;
        }
        elseif ($res->success === false) {
            return false;
        }
    }


    /*   Forget Password Processes     */

    public static function forgetPassword($email)
    {
        $db = DB::getInstance();
        $data = $db->get('users', array("email" , '=' ,$email));
        if($data != null)
        {
            $data = $data->results()[0];
            if(!empty($data))
            {
                $username = htmlspecialchars(stripslashes(trim($data->username)));
                $name = htmlspecialchars(stripslashes(trim($data->name)));
                $email = htmlspecialchars(stripslashes(trim($data->email)));
                $token = Hash::unique();
                $jwt = Token::jwtGenerate($username,900);

                $url = "https://www.codegreenback.com/accounts/forgetpassword/resetpassword.php?name=".$username."&vid=".$token."&email=".$email."&token=".$jwt ;
                if(Email::sendPasswordChangeEmail($name,$url,$email)){
                    if(StaticFunc::makeTempUser($username,$name,$email,"Forget_Password",$token)){
                        return json_encode(array('status'=>1, 'msg'=>"Password Reset Link Has Been Sent To The Above Email Address"));
                    }
                    else{
                        return json_encode(array('status'=>0, 'error'=>"An Error Ocurred ! Please Try Again"));
                    }
                }
                else{
                    return json_encode(array('status'=>0, 'error'=>"Email Could Not Be Sent ! "));
                }
            }
            else{
                return json_encode(array('status'=>0, 'error'=>"The Above Email Address Does Not Belong To AnyOne In CGB"));
            }
        }
        else{
            return json_encode(array('status'=>0, 'error'=>"The Above Email Address Does Not Belong To AnyOne In CGB"));
        }

        
        
        
    }

    public static function verifyForgetPassToken($username,$email,$token)
    {
        $db = DB::getInstance();
        $data = $db->get('emailVerify',array('token','=',$token));
        if($data != null)
        {
            $data = $data->results();
            if(strtotime($data[0]->time)+900 > strtotime(date('Y-m-d H:i:s',time())) && $username == $data[0]->username && $email == $data[0]->email)
            {
                return true;
            }
            elseif(strtotime($data[0]->time)+900 < strtotime(date('Y-m-d H:i:s',time())))
            {
                $db->delete('emailVerify',array('token','=',$token));
                return false;
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function updateResetPassword($user , $pass, $email, $vid)
    {
        $db = DB::getInstance();
        $data = $db->get('emailVerify',array('token','=',$vid));
        if($data != null)
        {
            $data = $data->results();
            if(strtotime($data[0]->time)+900 > strtotime(date('Y-m-d H:i:s',time())) && $user == $data[0]->username && $email == $data[0]->email)
            {

                $hash_pass = Hash::make($pass);
                if($db->update('users', $user ,array('password'=>$hash_pass))){
                    $db->delete('emailVerify' , array('username','=',$user));
                    return true;
                }
            }
            $db->delete('emailVerify' , array('username','=',$user));
        }

        return false;
    }



    /*.........................funtion to set league of the user................... */

    public static function League($point)
    {
        if($point <= 1800)
        {
            return "Nova";
        }
        elseif ($point > 1800 && $point <= 2100 ) {
            return "Hercules";
        }
        elseif($point > 2100 && $point <= 2500)
        {
            return "Tyche";
        }
        elseif($point > 2500 && $point <= 2800)
        {
            return "Hermis";
        }
        elseif($point > 2800 && $point <= 3200 )
        {
            return "Ares";
        }
        elseif($point > 3200)
        {
            return "Jeus";
        }
    }

    /* ................get current user code coins ........................... */
    public static function getCodeCoins()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));
            $db = DB::getInstance();
            $cc = $db->get('cc',array('username','=',$user));
            if(!empty($cc = $cc->results()))
            {
                
                return $cc->cc;
            }
            else{
                return 0;
            }
            
        }
        return 0;
    }


    /*
    ***  Static function to verify to make a new challenge
    */

    public static function verify_new_challenge($challenged_user , $cc_bet)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $db     =   DB::getInstance();

            $current_username   =   Session::get(Config::get('session/session_name'));

            if(!empty($challenged_user_data  = $db->sql_001($challenged_user)))                          //refer sql_001 for the data
            {
                /**
                 * here the challenged user is found 
                 * first check for past challenge with this user
                 */
                if(!$db->checkForRunningChallenge($current_username , $challenged_user))
                {
                    /**
                     * no running challenge now get details of current user 
                     * and first validate cc, then membership, then league and friendship status
                     */

                    if(!empty($current_user_data = $db->sql_001($current_username)))
                    {
                        if((int)$current_user_data->cc >= (int)$cc_bet)
                        {
                            if($current_user_data->user_type == $challenged_user_data->user_type)
                            {
                                /**
                                 * validate cc amount for non-elite member and check for max challenges
                                 */

                                if($current_user_data->user_type == 0 && (int)$cc_bet > 10)
                                {
                                    /**
                                     *  malicious activity 
                                     * non-elite member cannot challenge with more than 10 cc
                                     */
                                    return array('status' => 0, 'msg' => "Become An Elite Member To Challenge With More Than 10 CC");
                                }

                                if($current_user_data->user_type == 0 && $current_user_data->totalChallenges > 5)
                                {
                                    /**
                                     * non - elite member cannot have more than 5 challenges
                                     */

                                    return array('status' => 0 , 'msg' => "Challenge Limit Exceeded ! Become An Elite Member To Have Unlimited Challenges");
                                }


                                $friendStatus = StaticFunc::friendshipStatus($challenged_user);
                                if($friendStatus['status'] == "friend")
                                {
                                    /**
                                     *  Verification Successfull !!
                                     */
                                    return array('status' => 1);
                                }
                                else
                                {
                                    /**
                                     * the user are not friends so 
                                     * check league
                                     */
                                    if($current_user_data->league == $challenged_user_data->league)
                                    {
                                        /**
                                         * Verification Successfull !!
                                         */

                                        return array('status'  => 1);

                                    }
                                    else
                                    {
                                        /**
                                         * league is also differnt
                                         * user cannot challenge this user
                                         */
                                        return array('status' => 0, 'msg' => 'You Are Not In The Same League ! Become Friends Otherwise To Challenge ');
                                    }
                                }
                            }
                            else
                            {
                                /**
                                 * user type not matched
                                 */
                                return array("status" => 0 ,'msg' => "An Error Ocurred !!");
                            }
                        }
                        else
                        {
                            /**
                             * Insufficient Code Coins
                             */
                            return array('status'=>0, 'msg'=>"You Don't Have Enough Code Coins To Challenge !!");
                        }
                    }
                    else
                    {
                        /**
                         * current user details not found 
                         * from the funtion sql_001
                         */
                        return array('status'=>0, 'msg'=>"An Error Occurred");
                    }
                }
                else
                {
                    /**
                     * a challenge is already running bw these two players
                     * return status 0 with msg : A challenge is already running
                     */

                    return array('status'=>0, 'msg'=>"A challenge is already running !!");
                }
                
            
                
            }
        }
    }

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

        $db = DB::getInstance();
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
    

    /**
      * SOCKET PROGRAMMING FUNCTIONS END
      */

    
    public static function print_mem()
    {
    /* Currently used memory */
    $mem_usage = memory_get_usage();
    
    /* Peak memory usage */
    $mem_peak = memory_get_peak_usage();

    echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
    echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
    }

	
	 /**
     * this funtion gives the recommended users
     * for a particular league
     * @param $league = league for which you want recommended user
     * @param $limit : default is 10 (how many results you want)
     */
    public static function getRecommendedUsers($league, $limit = 10)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));
            $data = null;
            $leagues = array("Nova", "Hercules", "Tyche", "Hermis", "Ares", "Jeus");
            foreach($leagues as $key)
            {
                // print_r($key);
                if($key == $league)
                {
                    $db = DB::getInstance();
                $data = $db->sql_005($league);
                //print_r($data); 
                }

            }
            if($data != null)
            {
                $recom_user = [];
                foreach ($data as $key => $value) {
                    
                    if(empty($value->img))
                    {
                        $img = 'public/img/defaultimg.png';
                    }
                    else
                    {
                        $img = $value->img;
                    }
                    if($value->username != $user)
                    {
                        $recom_user[] = array(
                        "username"   => $value->username,
                        'img'        => $img,
                        'name'       => $value->name,
                    ); 
                    }
                }

                return $recom_user;
                
            }

                
        }
        return array('status' => 0);
    }




 /**
     * static function to update cc stats of the user
     * for ccStats table
     * @param $cc : the cc to appened ex: -20 or +50
     * @param $referer : the referec for the cc transfer can be challenge id , transaction id
     * @return JSON array(
     *  'cc' => integer,
     *  'referer' => $referer,
     *  'date' => date(),
     *  'balance' => integer                                          // this the updated balance 
     * )|null
     */

    public static function update_cc_stats($cc, $referer, $username = null)
    {
    
        if(Session::exists(Config::get('session/session_name')))
        {
            if($username != null)
            {
                $user = $username;
            }
            else 
            {
                $user = Session::get(Config::get('session/session_name'));
            }


            $db = DB::getInstance();

            /**
             * get current user codecoins and ccstats
             */
            
            $data = $db->sql_008($user);
            if($data != null)
            {
                $newbalance = $data->cc + $cc;
                $arr = array(
                    'cc' => $cc,
                    'date' => date("Y-m-d H:i:s",time()),
                    'balance' => $newbalance,
                    'referer' => $referer
                );

                $ccStats = json_decode($data->ccstats);
                $ccStats[] = $arr;

                return json_encode($ccStats);
            }
        }

        return null;
    }
 /**
     * static function to get recents users on search page
     */

    public static function getRecents()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));

            $db  = DB::getInstance();
            $data = $db->sql_011($user);

            if(!empty($data))
            {
                return json_encode($data);
            }
        }

        return 0;
    }


}


?>
