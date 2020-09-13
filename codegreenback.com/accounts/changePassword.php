<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';


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

// print_r($_POST);

if(Input::exists()){
    if(Token::check(Input::get('token'),'update_token'))
    {
        
        $validate = new Validate();
        if($status = $validate->update_pass_check($_POST)){
            if($status['status'] == 0)
            {
                echo json_encode($status);
            }
            elseif($status['status'] == 1)
            {
                
                $user = new User();
                if($user->update_user_pass($_POST['new-pass'])){

                    
                    echo json_encode(array("status"=>1, "msg"=>"Password Updated Successfully !!"));
               
                }
                else{
                    echo json_encode(array('status'=>0,'error'=>"An Error Occurred :("));
                }
            }
         
        }
    }
}

?>