<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$eid = $_GET['id'];

//print_r($_SESSION);

$msg = '';

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
include_once $_SERVER['DOCUMENT_ROOT'].'/after_login_header.php';

$event = new event($eid);

if(!$event->isValid($eid)){
	echo "<h1>No such event exists!</h1>";
	exit();
}


if ($event->started()) {

  echo "<h1>The event is has started</h1>";
  exit();
}

    if($event->ended()){
        echo "<h1>The event has ended</h1>";
        exit();
    }

if($event->isUserRegistered()){
  $registrationFlag = 1;
}
else{
	$registrationFlag = 0;
}

$totalInfo = $event->getTotalInfo();

?>






<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="public/css/register.css">
</head>
<body>
	<script type="text/javascript" src="public/js/register.js"></script>
	<input type="hidden" value="">
	<br>
	<br>
<br>
<br>
<br>
	<div class="content">
		<div class="contentInside">
			<h1 class="title">
				Try {code} we will catch
			</h1>
			<hr>
			<p>
				<center class="vision">
				<i>"<?php echo $totalInfo['details']  ?>"</i>
				</center>
			</p>
			<br>
			<br>
			<div class="instruction">
				<span>COMMENCEMENT : <?php echo $totalInfo['startTime']  ?>,<?php echo $totalInfo['startDate']  ?></span>
				<span>DURATION : <?php echo ($totalInfo['duration']/60).":00 HOURS"  ?></span>
			</div>
			<br>
			<div class="instruction">
				<span>REGISTRATION COST : <?php echo $totalInfo['typeOfEvent']  ?></span>
			</div>
			<p class="instruction">
				<?php echo $totalInfo['instruction']  ?>
			</p>
			<hr style="color: #333333">
			<br>
			<div class="options">
				<?php
				if($registrationFlag==0){
					echo "<div class='buttonn rounded successs' id='runcodecustom' onclick=\"window.location='mw/registration_mw.php?id=".$eid."';\"><span class='btntext'>REGISTER NOW!</span></div><div class='buttonn rounded failuree' id='runcodecustom' onclick=\"window.location='/index.php';\"><span class='btntext'>MAYBE LATER</span></div>";
				}
				else{
					echo "<center><h1 style = 'color: #a6c64c'>CONGRATULATIONS! YOU'VE SUCCESSFULLY REGISTERED<h1></center>";
				}
				?>				
			</div>
			<br>
		</div>
	</div>



















</body>
</html>
