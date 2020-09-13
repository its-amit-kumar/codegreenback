<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

require_once $_SERVER["DOCUMENT_ROOT"]."/after_login_header.php";


$mdb = new mongo("Events");
$events = $mdb->getAllCollection();
//echo count($events);
?>

<link rel="stylesheet" type="text/css" href="public/css/eventDashboard.css">

<div class="content">
	<h1>Make Event</h1><hr>
	<br>
	<a href="makeEvent.php"><button type="button" class="btn btn-primary btn-lg btn-block">MAKE A NEW EVENT</button></a>
	<br>
	<br>
	<h1>Events</h1><hr>
	<br>
	<div class="events">
		<div class='event'><div class='line1'><h1>TITLE</h1><h1>Skjhdf89eruhdc</h1></div><div class='line2'><h1>STATUS</h1></div></div>
		<?php
	foreach ($events as $key => $value) {
	$res = $mdb->get($value,array("type" => "details"))[0];
		if($res['status']=='running'){
			echo "<a href='eventDetails.php?id=".$value."'><div class='event'><div class='line1'><h1>".$res['title']."</h1><h1>".$value."</h1></div><div class='line2'><h5 style='color : green'>RUNNING</h5><h5>START TIME:".$res['startTime']."</h5><h5>START DATE: ".$res['startDate']."</h5><h5>DURATION : ".$res['duration']."</h5></div></div></a>";
		}
	}
	foreach ($events as $key => $value) {
	$res = $mdb->get($value,array("type" => "details"))[0];
		if($res['status']=='pending'){
			echo "<a href='eventDetails.php?id=".$value."'><div class='event'><div class='line1'><h1>".$res['title']."</h1><h1>".$value."</h1></div><div class='line2'><h5 style='color : #ebac00'>PENDING</h5><h5>START TIME:".$res['startTime']."</h5><h5>START DATE: ".$res['startDate']."</h5><h5>DURATION : ".$res['duration']."</h5></div></div></a>";
		}
	}
	foreach ($events as $key => $value) {
	$res = $mdb->get($value,array("type" => "details"))[0];
		if($res['status']=='scheduled'){
			echo "<a href='eventDetails.php?id=".$value."'><div class='event'><div class='line1'><h1>".$res['title']."</h1><h1>".$value."</h1></div><div class='line2'><h5 style='color : blue'>SCHEDULED</h5><h5>START TIME:".$res['startTime']."</h5><h5>START DATE: ".$res['startDate']."</h5><h5>DURATION : ".$res['duration']."</h5></div></div></a>";
		}
	}
	foreach ($events as $key => $value) {
	$res = $mdb->get($value,array("type" => "details"))[0];
		if($res['status']=='ended'){
			echo "<a href='eventDetails.php?id=".$value."'><div class='event'><div class='line1'><h1>".$res['title']."</h1><h1>".$value."</h1></div><div class='line2'><h5 style='color : red'>ENDED</h5><h5>START TIME:".$res['startTime']."</h5><h5>START DATE: ".$res['startDate']."</h5><h5>DURATION : ".$res['duration']."</h5></div></div></a>";
		}
	}
?>
	</div>
</div>

