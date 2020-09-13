<?php

/* middleware to Handle Searched user Friend Status with Current user */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';




$header = getBearerToken();


if(isset($_GET['searched']) && $_GET['service'] == 'friendstatus')
{
    $status = '';

    $user = Session::get(Config::get('session/session_name'));
    $userFriends = json_decode((StaticFunc::searchedUser_Friends($user))[0]->friendDetails);

    $searchedUser =  htmlspecialchars(trim($_GET['searched']));
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
    

   // print_r($data);
    if($data)
    {
        $friendDetails = json_decode($data[0]->friendDetails);
       // print_r($friendDetails);
        if(!empty($friendDetails->friendDetails)){
            for($i = 0 ; $i< count($friendDetails->blocked) ; $i++)
            {
                if(($friendDetails->blocked)[$i] == $user)
                {
                    $status = array('status'=>'Blocked','this_user_has_blocked'=>$blocked);
                    echo json_encode($status);
                    return 0;
                    
                }
            }
        }

        if(!empty($friendDetails->friends) )
        {
            for($i = 0; $i<count($friendDetails->friends) ; $i++ )
            {
                if(($friendDetails->friends)[$i] == $user)
                {
                    $status = array('status'=>"friend",'this_user_has_blocked'=>$blocked);
                    echo json_encode($status);
                    return 0;
                }
            }
        }

        if(!empty($friendDetails->requests) )
        {
            for($i = 0 ;$i<count($friendDetails->requests) ; $i++)
            {
                if(($friendDetails->requests)[0] == $user){
                $status = array('status' => "requested",'this_user_has_blocked'=>$blocked);
                echo json_encode($status);
                return 0;
                }
            }
        }
        else{
            echo json_encode(array('status'=>"Not Friends",'this_user_has_blocked'=>$blocked));
            return 0;
        }


    }

}



if(isset($_POST['searched']) && $_POST['service'] == 'sendRequest')
{
    $searched = htmlspecialchars(trim($_POST['searched']));
    if($data = StaticFunc::sendFriendRequest($searched)){
        echo json_encode(array('status'=>'1', 'data'=>$data));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}


if(isset($_GET['searched']) && $_GET['service'] == 'deleteRequest'){
    $searched = htmlspecialchars(trim($_GET['searched']));
    if($data = StaticFunc::deleteFriendRequest($searched)){
        echo json_encode(array('status'=>"1",'data'=>$data));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}


if(isset($_GET['searched']) && $_GET['service'] == 'blockUser')
{
    $searched = htmlspecialchars(trim($_GET['searched']));
    if($data = StaticFunc::BlockUser($searched)){
        echo json_encode(array('status'=>'1','data'=>$data));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}

if(isset($_GET['searched']) && $_GET['service'] == 'unblockUser')
{
    $searched = htmlspecialchars(trim($_GET['searched']));
    if($data = StaticFunc::unBlockUser($searched)){
        echo json_encode(array('status'=>'1', 'data'=>$data));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}


if(isset($_GET['user']) && $_GET['service'] == "getData" )
{
    $searched = htmlspecialchars(trim($_GET['user']));
    if($data = StaticFunc::getSearchedUserInfo($searched))
    {
        echo $data;
    }
    else{
        return json_encode(array(0));
    }
    
}


if(isset($_GET['service']) && $_GET['service'] == '_rec')
{
    if($_GET['service'] == '_rec')
    {
        $league = Session::get(Config::get('session/user_league'));
        if($data = StaticFunc::getRecommendedUsers($league))
        {
            echo json_encode($data);
        }
        
    }
    else{
        echo json_encode(array('status' => 0));
    }
}

?>

