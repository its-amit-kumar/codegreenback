<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

/*
gets the questions for the present event
*/

$id = $_POST['task_id'];

$qNo = $_POST['qNo'];

$mdb = new mongo("Events");

$res = $mdb->get($id,['type' => 'details'])[0];

$event = new event($id);

$endTime = $event->getEndTime($id);

$endTime = $endTime - time();


$value = $res['questions'][$qNo-1];
	$question = array(
		'title' => $value['title'],
		'statement' => $value['statement'],
		'if' => $value['if'],
		'of' => $value['of'],
		'constrain' => $value['constrain'],
		'sampleI' => $value['testcases'][0]['inputgiven'],
		'sampleO' => $value['testcases'][0]['expectedOutput'],
		'endTime' => $endTime

	);
echo json_encode($question);

?>
