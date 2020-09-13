<?php
/*          MiddleWaRE FOR Your challenges data 
                people who challenged this user 
                                                                */



require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';

$header = getBearerToken();
if($header)
{
    if(Token::jwtVerify($header) == -1)
    {
        echo "not verified";
    }
}
else{
    echo "jwt not verified";
}


if(isset($_SESSION[Config::get("session/session_name")]))
{
    //echo "session exists";
    $req = array();
   $notification = new ChallengeNotification($_SESSION['user']);              //getting notification of current logged in user
    $datas = $notification->getNotification();
    if($datas){
        foreach ($datas as $data) {
 	    if($data->c_img == null)
            {
                $data->c_img = 'public/img/avatar.png';
            }
            $req[] = array(
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
    }
    echo json_encode($req);

}
else {
    echo "not fair";
}

?>
