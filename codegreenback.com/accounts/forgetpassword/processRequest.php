<?php

/*  MIDDLEWARE TO PROCESS FORGET PASSWORD REQUEST */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(isset($_POST['g-token']) && isset($_POST['service']) && isset($_POST['token']))
{
    if($_POST['service'] === 'F_P')
    {
        $token = htmlspecialchars(trim($_POST['token']));
        if(Token::check($token , 'csrf'))
        {
            if(StaticFunc::VerifyCaptcha($_POST['g-token']))
            {
                $email = filter_var($_POST['email'] , FILTER_VALIDATE_EMAIL);
                if($email == $_POST['email'])
                {
                    
                    echo StaticFunc::forgetPassword($email);
                
                }
                
            } 
        }
       
    }
   
}


if(isset($_POST['g-token']) && isset($_POST['service']) && isset($_POST['token']) && isset($_POST['vid']) && isset($_POST['new_pass']) && isset($_POST['user']) && isset($_POST['email']))
{
    if($_POST['service'] === 'R_P')
    {
        $token = htmlspecialchars(trim($_POST['token']));
        if(Token::check($token , 'csrf'))
        {
            if(StaticFunc::VerifyCaptcha($_POST['g-token']))
            {
                //process forget password
                $user = htmlspecialchars(trim($_POST['user']));
                $pass = $_POST['new_pass'];
                $email = $_POST['email'];
                $vid = $_POST['vid'];
                if(StaticFunc::updateResetPassword($user , $pass, $email,$vid))
                {
                    echo  json_encode(array('status'=>1 ,'msg'=>"Password Reset Successfull"));
                }
                else{
                    echo json_encode(array('status'=>0 , 'msg'=>"An Error Ocurred"));
                }
                
            }
            else{
                echo  json_encode(array('status'=>0 ,'error'=>"Automated Bot Detected"));
            } 
        }
        else{
            echo json_encode(array('status'=>0, 'error'=>"Invalid Token"));
        }
       
       
    }
    else {
        echo json_encode(array('status'=>0 , 'error'=>"Invalid Service"));
    }
   
}




?>