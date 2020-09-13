<?php

/*  Middleware to handle user friend request i.e. Get requests , accept or decline  and get General notifications       */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(isset($_GET['service']) && $_GET['service'] == 'getReq')
{
    //fetch user friend requests
    if($data = StaticFunc::getFriendReq())
    {
        echo $data;
    }
    else{
        echo json_encode(array());
    }
}


if(isset($_POST['service']) && $_POST['service'] == 'accept' && isset($_POST['user']))
{
    //accept user request
    $user = htmlspecialchars(trim($_POST['user']));
    if(StaticFunc::acceptFriendReq($user)){
        echo json_encode(array('status'=>'1'));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}


if(isset($_POST['service']) && $_POST['service'] == 'decline' && isset($_POST['user']))
{
    //delete request
    $user = htmlspecialchars(trim($_POST['user']));
    if(StaticFunc::declineFriendReq($user)){
        echo json_encode(array('status'=>'1'));
    }
    else{
        echo json_encode(array('status'=>'0'));
    }
}


if(isset($_GET['service']) && $_GET['service'] == 'getGenNotification')
{
    //
      if($data = StaticFunc::getGeneralNotification())
    {
        echo $data;
    }
    else{
        echo json_encode(array());
    }
}



if(isset($_GET['service']) && $_GET['service'] == 'deleteGenNotification')
{
    //
      StaticFunc::deleteGeneralNotification();

}




?>