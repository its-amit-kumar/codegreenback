<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
echo "hello";
if(Input::exists()){
    if(Token::check(Input::get('token'),'update_token'))
    {
        echo "token verified";
        $validate = new Validate();
        if($validate->update_pass_check($_POST)){
            echo "form validated";
            $user = new User();
            if($user->update_user_pass($_POST['new_pass'])){

                //echo "pass updated";
                $user->logout();
                Session::flash('pass',"password changed ! Login Again");
                Redirect::to('../index');
            }
            else{
                echo "pass not updated";
            }
        }
    }
}
?>