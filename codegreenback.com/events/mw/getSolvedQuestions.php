<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

/*
returns the array of questions already solved by the user
*/

$eventId = $_POST['event_id'];

$mdb = new mongo("Events");

$res = $mdb->get($eventId,array("type" => "details"))[0];

$resUser = $mdb->get($eventId,array("user" => $_SESSION['user']))[0]['solvedQuestions'];

$solvedArray = array();

foreach ($resUser as $key => $value) {
	if (count($value) == $res['questions'][$key]['noOfTestCases']) {
		array_push($solvedArray, $key);
	}
}

echo json_encode($solvedArray);



?>