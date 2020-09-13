<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
    Redirect::to('index');
}
if(Input::exists()){
    if(Token::check(Input::get('image_token'))){
        if(Image::image_upload($_FILES)){
            Session::flash('uploaded',"Profile photo uploaded successfully");
            Redirect::to('../update');
        }
        else {
            Session::flash('uploaded',"Profile photo cannot be uploaded! Sorry try again");
            Redirect::to('../update');
        }
    }
}

?>

