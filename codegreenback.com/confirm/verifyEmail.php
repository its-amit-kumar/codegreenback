<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

//echo "verify email page ";
if(isset($_GET['name'])&& isset($_GET['vid'])&& isset($_GET['email'])&& isset($_GET['token']))
{
    //echo $_GET['token'];
    if($data = Token::jwtEmailTokenVerify($_GET['token'])){
        $user = $_GET['name'];
        $email = $_GET['email'];
       // print_r($data);
        if($data['user'] == $_GET['name']){
            if(StaticFunc::verifyTempUser($user,$email,$_GET['vid'])){
                Session::flash('signUp',"Sign Up SuccessFull ! Please Login ");
                Redirect::to('');
            }
            else{
                Session::flash('signUp',"There Was A Problem In Signing Up. Please Try Again . ");
                Redirect::to('');
            }
        }
    }
    else{
        echo " TimeOut";
    }
}


?>
