<?php

/*
The middleware used to regsiter a user in an event
*/

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$eid = $_GET['id'];
 $event = new event($eid);

 $user = new User();
$user->isLoggedIn()?true:Redirect::to('');
$token = Token::jwtGenerate(Session::get('user'));                            //jwt token
$stats = $user->statsData();
$cc  = $user->cc();
Cookie::put('cc',$cc->cc,86400);
//print_r($cc);
Cookie::makeCookieUser($stats);                                               //to store user stats on client computer for easier access and saving time
(Session::exists(Config::get('session/session_name')))?true:Redirect::to('index');
if(Session::exists('prompt')){

$msg =  escape(Session::flash('prompt'));}

$event = new event($eid);

if(!$event->isValid($eid)){
	echo "<h1>No such event exists!</h1>";
	exit();
}


if (!$event->started()) {
    if($event->ended()){
        echo "<h1>The event has ended</h1>";
        exit();
    }

}
else{
  echo "<h1>The event has started</h1>";
  exit();
}

if($event->isUserRegistered()){
  header("Location: /home.php");
}
else{
	$event->insertUser($eid);
	header("Location: /events/register.php?id=".$eid);
}




?>
