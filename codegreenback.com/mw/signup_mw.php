<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


if(isset($_POST['g-token']) && isset($_POST['service'])){
    
    
    if($_POST['service'] == "signup"){

        if(StaticFunc::VerifyCaptcha($_POST['g-token']))
        {
            $validate = new Validate();
            if(!$validate->signup_check($_POST)->passed()){
                echo json_encode($validate->errors());
            }
            else{

                StaticFunc::sendEmailVerification($_POST);
                echo json_encode(array("status"=> "1"));
            }
        }
        else{
            echo json_encode(array('msg'=>"google captcha failed"));
        }
	//echo "site is not launched";
   
    }
   
    
   
}




if(isset($_POST['g-token']) &&  isset($_POST['service'])){
    if($_POST['service'] == 'login'){

        if(StaticFunc::VerifyCaptcha($_POST['g-token']))
        {
            $user = new User();
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            if($_POST['remember']){
                if($user->login($username,$pass,true)){
                    echo 1;
                }
                else{
                    echo 0;
                }
            }
        else{
                if($user->login($username,$pass)){
                    echo 1;
                }
                else{
                    echo 0;
                }
            }
        }
 
        
    }
}

if(isset($_POST['token']) &&  Token::check($_POST['token'],'csrf')){

    
    if($_POST['service'] == "google"){
        if(!isset($_POST['username'])){
            $id_token = $_POST['id_token'];
            $userData = json_decode(file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$id_token),true);
            $db = DB::getInstance();
            if ($userData['email_verified']) {
                if($db->sql_007($userData['email'])){
                    $user = new User($db->sql_007($userData['email']));
                    if($user->login($db->sql_007($userData['email']))){
                        echo json_encode(array("status"=>"1"));
                    }
                    else{
                        echo json_encode(array("status"=>"0"));
                    }
                }
                else{
                echo json_encode(array("status"=>"getUsername"));
            }
            }

        }
        else{
            $id_token = $_POST['id_token'];
            $userData = json_decode(file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$id_token),true);
            $pass = "NOPASSWORD".time();
            $POST = array("username"=>$_POST['username'], 'email'=>$userData['email'], 'password'=>$pass, 'password_again'=>$pass, 'name'=>$userData['name']);
            $validate = new Validate();
            if(!$validate->signup_check($POST)->passed()){
            echo json_encode($validate->errors());
            }
            else{

                //StaticFunc::sendEmailVerification($_POST);
                //echo json_encode(array("status"=> "1"));
                $makeUser = array(
                    'username' => $_POST['username'],
                    'password' => $pass,
                    'email' => $userData['email'],
                    'name' => $userData['name'],
                );

                $user = new User();
                if($user->create_newuser($makeUser)){
                    $db = DB::getInstance();
                    if($user->login($_POST['username'],$pass,true)){
                        $db->updateImg('users',Session::get(Config::get('session/session_name')),array('user_image'=>$userData['picture']));
                        echo 1;
                    }
                    else{
                        echo 0;
                    }
                }
                else{
                    echo 0;
                }
            }
        }
    }
}




//if(isset($_POST['token']) &&  Token::check($_POST['token'],$_POST['service'])){

    
    if($_POST['service'] == "github"){
            $access_token = $_POST['access_token'];

            $curl = curl_init();
            curl_setopt($curl , CURLOPT_URL , "https://api.github.com/user/emails");
            //curl_setopt($curl , CURLOPT_POST,TRUE);
            curl_setopt($curl , CURLOPT_RETURNTRANSFER , TRUE);
            curl_setopt($curl , CURLOPT_HTTPHEADER,array('Accept: application/json','Authorization: token '.$access_token,"User-Agent: Demo"));
            $userData = json_decode(curl_exec($curl), True);
            foreach ($userData as $key => $value) {
                if($userData[$key]['primary']==1){
                    $emailNo = $key;
                    break;
                }

            }
            $pass = "NOPASSWORD".time();
            $POST = array("username"=>$_POST['username'], 'email'=>$userData[$emailNo]['email'], 'password'=>$pass, 'password_again'=>$pass, 'name'=>$_POST['name']);
            $validate = new Validate();
            if(!$validate->signup_check($POST)->passed()){
            echo json_encode($validate->errors());
            }
            else{

                //StaticFunc::sendEmailVerification($_POST);
                //echo json_encode(array("status"=> "1"));
                $makeUser = array(
                    'username' => $_POST['username'],
                    'password' => $pass,
                    'email' => $userData[$emailNo]['email'],
                    'name' => $_POST['name'],
                );

                $user = new User();
                if($user->create_newuser($makeUser)){
                    $db = DB::getInstance();
                    if($user->login($_POST['username'],$pass,true)){
                        //$db->updateImg('users',Session::get(Config::get('session/session_name')),array('user_image'=>$userData['picture']));
                        echo 1;
                    }
                    else{
                        echo 0;
                    }
                }
                else{
                    echo 0;
                }
            }
        }
    //}


?>
