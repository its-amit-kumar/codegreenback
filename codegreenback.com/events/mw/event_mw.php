<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
$mdb = new mongo('Events');

/*
Make a new event, make a new collection in db for each event,
by default 3 entries are made, 
1. The general details of the event whccih includes all the question
2. The document for live details
4. The document foer details
*/

if($_POST['service'] == "makeEvent"){
	$title = $_POST['name'];
	$duration = $_POST['duration'];
	$startDate = $_POST["startDate"];
	$startTime = $_POST['startTime'];
	$typeOfEvent = $_POST['typeOfEvent'];
	$cc = $_POST["cc"];
	$typeOfPromotion = $_POST['typeOfPromotion'];
	$numberOfQuestions = $_POST['numberOfQuestions'];
	$details = $_POST['details'];
	$instruction = $_POST['instruction'];

	$id = uniqid();

	if ($mdb->makeCollection($id)) {
		$detail = array(
			"type" => "details",
			"title" => $title,
			"duration" => $duration,
			"startDate" => $startDate,
			"startTime" => $startTime,
			"typeOfEvent" => $typeOfEvent,
			"cc" => $cc,
			"typeOfPromotion" => $typeOfPromotion,
			"numberOfQuestions" => $numberOfQuestions,
			"details" => $details,
			"instruction" => $instruction,
			"status" => "pending",
			"questions" => array()

		);

		$liveDetails = array(
			"type" => "liveDetails",
			"totalSubmissions" => "0",
			"successfulSubmission" => "0",
		);
		for($i = 0;$i<$numberOfQuestions;$i++){
			$liveDetails[$i] = array("total" => "0", "success" => "0");
		}

		$resultDetails = array(
			"type" => "result",
			"status" => "0",
			"rewardStatus" => "0"
		);

		if($mdb->insert($id,$detail)){
			if($mdb->insert($id,$liveDetails)){
				if($mdb->insert($id,$resultDetails)){
					echo "1";
				}
			}
		}

	}
}

/*
Service to manually launch the event
*/


if ($_POST['service'] == "launch") {
	$eid = $_POST['eventId'];
	$res = $mdb->get($eid, array("type" => "details"));
	if(sizeof($res) == 0){
		echo -1;
	}
	elseif($res[0]['status']!='scheduled'){
		echo -2;
	}
	elseif($res[0]['status']=='scheduled'){
		if($mdb->update($eid,array("type" => "details"),array("status" => "running"))){
			echo 1;
		}
		else{
			echo 0;
		}

	}
}

/*
Service to manually stop the event
*/

if ($_POST['service'] == "end") {
	$eid = $_POST['eventId'];
	$res = $mdb->get($eid, array("type" => "details"));
	if(sizeof($res) == 0){
		echo -1;
	}
	elseif($res[0]['status']!='running'){
		echo -2;
	}
	elseif($res[0]['status']=='running'){
		if($mdb->update($eid,array("type" => "details"),array("status" => "ended"))){
			echo 1;
		}
		else{
			echo 0;
		}

	}
}

/*
Make a new question
*/


if($_POST['service'] == "makeQuestion"){
	$eventId = $_POST['eventId'];
	$questionTitle = $_POST['title'];
	$statement = $_POST['statement'];
	$lang = $_POST['language'];
	$difficulty = $_POST['difficulty'];
	$domain = $_POST['domain'];
	$inputFormat = $_POST["inputFormat"];
	$outputFormat = $_POST["outputFormat"];
	$constrain = $_POST["constrain"];
	$noOfTestCases = $_POST["numberOfTestCases"];
	$testcases = $_POST["testcases"];
	$solution = $_POST['solution'];
	$tags = array('lang' => $lang,'domain' => $domain);
	$mdb = new mongo('Events');
	$res = $mdb->get($eventId,array("type" => "details"));
	$presentQuestions = $res[0]['questions'];
	$presentQuestions = json_decode(json_encode($presentQuestions),true);
	$testcasesInsert = array();
	foreach ($testcases as $key => $value) {
		if($value["typeOfTestCase"] == "True"){
			array_push($testcasesInsert, $value);
		}
	}
	foreach ($testcases as $key => $value) {
		if($value["typeOfTestCase"] == "False"){
			array_push($testcasesInsert, $value);
		}
	}
	$newQues = array(
		"title" => $questionTitle,
		"statement" => $statement,
		"difficulty" => $difficulty,
		"if" => $inputFormat,
		"of" => $outputFormat,
		"constrain" => $constrain,
		"noOfTestCases" => $noOfTestCases,
		"testcases" => $testcasesInsert,
		"solution" => $solution,
		"tags" => $tags
	);
	if(count($presentQuestions)==0){
		$presentQuestions = array("0" => $newQues);
		//print_r($presentQuestions);
	}
	else{
		array_push($presentQuestions, $newQues);
	}
	 $ress = $mdb->update($eventId,array("type" => "details"),array("questions" => $presentQuestions));

	 if($res[0]['numberOfQuestions']==sizeof($presentQuestions)){
	 	//echo "string";
	 	$mdb->update($eventId,array("type" => "details"),array("status" => "scheduled"));
	 }
	 echo "1";
	
}

/*
Returns an array of live details
*/

if($_POST['service'] == "runningDetails"){
	$id = $_POST['eventId'];
	$event = new event($id);
	$rank = $event->getLiveDetails();
	echo json_encode($rank);
}

/*
make the csv file puts it in result folder
and the user downloads it,
also sets the result in the collection with all the setails as in csv file
*/

if($_POST['service']=="generateResult"){
	$eid = $_POST['eventId'];
	$event = new event($eid);
	If($event->getStatus()!="ended"){
		echo "0";
		exit();
	}
	$result = $event->getResult();
	$participantT1 = $result[0];
	$participantT2 = $result[1];
	$resultFile = fopen("results/".$eid.".csv","w");
	$rank = 1;
	$toBeInserted = array();
	fputcsv($resultFile, ['rank', 'name', 'Type of winner', 'criteria']); 
	foreach ($participantT1 as $key => $value) {
		for($i = 0;$i<sizeof($value);$i++){
			fputcsv($resultFile, array($rank, $value[$i], "type1", $key));
			$mdb->update($eid, array("type" => "result"),array($rank => array("name" => $value[$i], "typeOfUser" => 'type1', "criteria" => $key)));
			$rank+=1;
		}
	}

	foreach ($participantT2 as $key => $value) {
		for($i = 0;$i<sizeof($value);$i++){
			fputcsv($resultFile, array($rank, $value[$i], "type2", $key));
			$mdb->update($eid, array("type" => "result"),array($rank => array("name" => $value[$i], "typeOfUser" => 'type2', "criteria" => $key)));
			$rank+=1;
		}
	}
	fclose($resultFile);
	$mdb->update($eid,array("type" => "result"), array("status" => "1"));
	echo 1;
}



?>