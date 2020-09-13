<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';


/*   middleware for the status of the requests 
                     which this user has made
      or challenge request made by this user                                                        */




$header = getBearerToken();
if($header)
{
    if(Token::jwtVerify($header) == -1)
    {
        echo -1;
        exit;
    }
}
else{
    echo -1;
    exit;
}


$user = new User();
if($user->isLoggedIn())
{


    $notification = new ChallengeNotification($_SESSION[Config::get('session/session_name')]);
    $datas = $notification->your_request();
    

    if(!empty($datas)){

        $req = array();

        foreach ($datas as $data) {
            $req[] = array(
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

        echo json_encode($req);                                                                     // status of the challenges requested by the user
    }
    else{

        echo 0;                                                                              // 0 past challenge made by the user
    }


}
else
{
    Redirect::to("../includes/errors/404.php");
}



?>