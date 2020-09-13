<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$mdb = new mongo("Events");

$events = $mdb->getAllCollection();

//print_r($events);

//echo json_encode($events);

$toBeSend = array();

for($i=0;$i<count($events);$i++) {
	$event = new event($events[$i]) or die("error");
	$info = $event->getCardInformation($events[$i]);
	if($info['status']=="scheduled" || $info['status']=="running"){
		array_push($toBeSend, $info);
	}

}
//if(count($toBeSend)>0){
	echo json_encode($toBeSend);
//}




?>