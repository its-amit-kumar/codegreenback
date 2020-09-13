<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


class event
{
	private $_totalInformation,
			$_mdb,
			$_fileName,
			$_JSON,
			$_eventId;
	
	/*
	the constructor takes in the event id and gets the entire detail of the event.
	*/


	function __construct($eventId)
	{
		$this->_mdb = new mongo('Events');

		$this->_eventId = $eventId;

		if($this->isValid($eventId)){
			$this->_totalInformation = $this->_mdb->get($eventId,array("type" => "details"))[0] or die("error2");
		}

	}

	/*
		Sets the file name
	*/

	private function setFileName($language){
			if ($language == "python"){
				$this->_fileName = "main.py";
			}
			if ($language == "c"){
				$this->_fileName = "main.c";
			}
			if ($language == "cpp"){
				$this->_fileName = "main.cpp";
			}
			if ($language == "java"){
				$this->_fileName = "main.java";
			}
			if ($language == "bash"){
				$this->_fileName = "main.sh";
			}
			if ($language == "erlang"){
				$this->_fileName = "main.erl";
			}
			if ($language == "golang"){
				$this->_fileName = "main.go";
			}
			if ($language == "haskell"){
				$this->_fileName = "main.hs";
			}
			if ($language == "javascript"){
				$this->_fileName = "main.js";
			}
			if ($language == "ruby"){
				$this->_fileName = "main.rb";
			}
			if ($language == "swift"){
				$this->_fileName = "main.swift";
			}
		}

		/*
		make the JSON to be inserted while docker run
		*/

	private function PrepareJSON($language,$content,$input){
			$this->setFileName($language);
			$dataJSON = array(
				"language" => $language,
				"files" => [array(
					"name"=>$this->_fileName,
					"content"=>$content
				)],
				"stdin"=>$input,
				"command"=> ""
			);


			$this->_JSON = json_encode($dataJSON);
		}

		/*
		gets the expected output of the question number and the iinput number specified
		*/

	public function getExpectedOutput($quesId,$TestCaseNumber){

			$OutputCheck = $this->_totalInformation['questions'][$quesId]['testcases'][$TestCaseNumber-1]['expectedOutput'];

			return $OutputCheck."\n";


		}

		/*
		Make the api call to the code runner using curl and returns the resultant json
		*/

	public function MakeAPICall($language,$content,$input){
			$this->PrepareJSON($language,$content,$input);
			$this->setFileName($language);
			$curl = curl_init();
			curl_setopt($curl , CURLOPT_URL , "http://13.233.153.141/APIbuilddocker.php");
    		curl_setopt($curl , CURLOPT_POST,TRUE);
    		curl_setopt($curl , CURLOPT_RETURNTRANSFER , TRUE);
    		curl_setopt($curl , CURLOPT_POSTFIELDS , $this->_JSON);
    		$result = json_decode(curl_exec($curl));
    		return $result;
		}

		/*
		Checks if the compilation was true or not
		*/


	public function IsCompile($result_arr){
			if ($result_arr==""){
				return true;
			}
			else{
				return false;
			}
		}

		/*
		This is the function that compares the expected output to the guven output,
		The following funtions are used:
			1. trim - Remove the white spaces at the end of each line
			2. rtrim - Removes all the new lines from the end of the output
		*/

	private function CheckCode($TestCaseNumber,$output,$quesId){
			$OutputCheck = $this->_totalInformation['questions'][$quesId]['testcases'][$TestCaseNumber-1]['expectedOutput'];
			$OutputCheck2 = join("\n", array_map("trim", explode("\n", $OutputCheck)));
			$OutputCheck = $OutputCheck2."\n";
			$output =  join("\n", array_map("trim", explode("\n", $output)));
			

			if (json_encode(rtrim(trim($OutputCheck))) == json_encode(rtrim(trim($output)))){
				$this->_Success = "true";
			}
			else{
				$this->_Success = "false";
			}
		}

		/*
		This is a public funtion which returns true or false base on wheater the given tset case number passed or not 
		by calling the above CheckCode function
		*/

	public function ispassed($InputTestCaseNumber,$output,$quesId){

			$this->CheckCode($InputTestCaseNumber,$output,$quesId);

			return $this->_Success;
		}

		/*
		Returns the total number of test cases
		*/

	public function getNoOfTestCases($QuestionId){
			return $this->_totalInformation['questions'][$QuestionId]['noOfTestCases'];
		}

		/*
		returns the number of private test cases
		*/

	public function getNoofPrivateTestCases($QuestionId){
			return $this->_totalInformation['questions'][$QuestionId]['testcases'][$this->getNoOfTestCases($QuestionId)-1]['privateNo'];
		}

		/*
		returns the test case using the test case number,
		usually used when runcode is called to send the public test case to the user
		*/

	public function getInput($quesId,$TestCaseNumber){
			$a = $this->_totalInformation['questions'][$quesId]['testcases'][$TestCaseNumber-1]['inputgiven'];
			return $a;
		}

		/*
		checks if the event is started
		*/

	public function started(){
		if($this->_totalInformation['status'] == 'running'){
			return true;
		}
		else{
			return false;
		}
	}

	/*
	checks if the event is ended, returns true or false accordingly
	*/

	public function ended(){
		if($this->_totalInformation['status'] == 'ended'){
			return true;
		}

		else{
			return false;
		}
	}

	/*
	Checks if the registerd user has started the event or not
	*/

	public function isStartedByUser(){

		if($this->_mdb->get($this->_eventId,array("user" => $_SESSION['user']))[0]['startTime']==""){
			return false;
		}
		else{
			return true;
		}
	}

	/*
	Checks if the user is register or not and returns true and false accordingly
	*/

	public function isUserRegistered(){
		$res = $this->_mdb->get($this->_eventId,array("user" => $_SESSION['user']));
		if(count($res)==0){
			return false;
		}
		else{
			return true;
		}
	}

	/*
	gets the array of the currently solved question with their test cases
	*/

	public function getSolved(){
		$res = $this->_mdb->get($this->_eventId,array("user" => $_SESSION['user']))[0]['solvedQuestions'];
		return $res;
	}

	/*
	if the user has solved a new question or test case, use this function to insert the new array
	*/

	public function updateSolved($updatedSolved){
		if($this->_mdb->update($this->_eventId,array("user" => $_SESSION['user']),array("solvedQuestions" => $updatedSolved))){
			return true;
		}
		else{
			return false;
		}
	}

	/*
	inserts that the user has started the event
	*/

	public function insertStarted(){
		if($this->_mdb->update($this->_eventId,array("user" => $_SESSION['user']),array("startTime" => time()))){
			return true;
		}
		else{
			return false;
		}
	}

	/*
	After each run, checks if the event has been entirely solved by a user
	*/

	public function isCompletelySolved(){
		$res = $this->getSolved();
		if(count($res)!=$this->_totalInformation['numberOfQuestions']){
			return false;
		}
		else{
			$flag = 0;

			for($j = 0;$j<$this->_totalInformation['numberOfQuestions'];$j++){

				if($this->_totalInformation['questions'][$j]['noOfTestCases'] == count($this->getSolved()[$j])){
					$flag+=1;
				}
				else{
					break;
				}

			}

			if($flag==$this->_totalInformation['numberOfQuestions']){
				return true;
			}
			else{
				return false;
			}
		}
	}

	/*
	inserts the time at which the user has solved the entire event
	*/

	public function insertEnded(){

		if($this->_mdb->update($this->_eventId,array("user" => $_SESSION['user']),array("endTime" => time()))){
			return true;
		}
		else{
			return false;
		}		

	}

	/*
	checks if the event id is valid
	*/

	public function isValid($eid){
		$events = $this->_mdb->getAllCollection();
		if(array_search($eid, $events)!=-1){
			return True;
		}
		else{
			return false;
		}
	}

	/*
	inserts a new participant in the event id
	*/

	public function insertUser($eventId){
		$this->_mdb->insert($eventId,array("type" => "participant","user" => $_SESSION['user'], 'startTime' => "", "endTime" => "", "solvedQuestions" => (object)array()));
	}

	/*
	gets the end time of the event in UNIX epooch
	*/

	public function getEndTime(){
		$date = $this->_totalInformation['startDate'];
		$time = $this->_totalInformation['startTime'];
		$start = strtotime($date." ".$time) + $this->_totalInformation["duration"]*60;
		return $start;

	}

	/*
	gets the card information to be send o the card on the home page
	*/

	public function getCardInformation($eid){
		$this->_totalInformation = $this->_mdb->get($eid,array("type" => "details"))[0];
		if ($this->isUserRegistered()) {
			$isUserRegistered = "true";
		}
		else{
			$isUserRegistered = "false";
		}
		return array(
			"eid" => $eid,
			"title" => $this->_totalInformation['title'],
			"duration" => $this->_totalInformation['duration'],
			"startDate" => $this->_totalInformation['startDate'],
			"startTime" => $this->_totalInformation['startTime'],
			"typeOfEvent" => $this->_totalInformation['typeOfEvent'],
			"cc" => $this->_totalInformation['cc'],
			"details" => $this->_totalInformation['details'],
			"status" => $this->_totalInformation['status'],
			"isUserRegistered" => $isUserRegistered
		);
	}

	/*
	Returns the points scored by a user in a question
	*/

	private function totalScoredPoints($dist){
		$points = 0;
		foreach ($dist as $ques => $testcase) {
			$quesInfo = $this->_totalInformation['questions'];
			foreach ($testcase as $index => $testCaseNumber) {
				$points+=$quesInfo[$ques]['testcases'][$testCaseNumber-1]["pointsAwarded"];
			}
			
		}

		return $points;
	}

	/*

	1. First the users who've completed all the questions in the given time are considers. These are type1 participants.
	2. The uppen\r users will be compared on the basis that whosoever completes first (lesser time) will have better (lesser Ranking).
	3. In case of timing class, the tie will be resolved on the basis of the following rules:
		a. The user who registerd first is given a higher consideration
	4. Next comes the people who have not completed all the questions in the given time, these are type2 participants
	5. The upper users will be evaluated on the basis that whosoever gets more points will be given better rank (lesser Ranking).
	6. In case of tie, the tie will be resolved on the basis:
		a. The user who registerd first is given a higher consideration
	returns the following data structure,
	array(type1, type2)
	type1 = array(time => array(users))             sorted from less time to more time
	type2 = array(points => array(users))           sorted from less score to more score

	*/

	public function getResult(){
		$participants = $this->_mdb->get($this->_eventId,array("type" => "participant"));
		$participantT1 = array();
		$timeT1 = array();
		$participantT2 = array();
		$pointsT2 = array();
		for($i = 0;$i<count($participants);$i++){
			if($participants[$i]['endTime']!=""){

				if(!isset($participantT1[$participants[$i]['endTime']])){
					$participantT1[$participants[$i]['endTime']] = array($participants[$i]['user']);
					array_push($timeT1, $participants[$i]['endTime']);
				}
				else{
					array_push($participantT1[$participants[$i]['endTime']], $participants[$i]['user']);

				}

			}
			else{
				$dist = $participants[$i]['solvedQuestions'];
				$points = $this->totalScoredPoints($dist);
				
				if(!isset($participantT2[$points])){
					$participantT2[$points] = array($participants[$i]['user']);
					array_push($pointsT2, $points);
				}
				else{
					array_push($participantT2[$points], $participants[$i]['user']);

				}
			}
		}

		ksort($participantT1);
		krsort($participantT2);
		return array($participantT1,$participantT2);

	}

	/*
	gets the live details such as present rank, number of compiles, number of successful compiles
	*/

	public function getLiveDetails(){
		$rank = $this->getResult();
		$participantT1 = $rank[0];
		$participantT2 = $rank[1];
		$rank = array();
		foreach ($participantT1 as $key => $value) {
			for($i = 0;$i<sizeof($value);$i++){
				array_push($rank, $value[$i]);
			}
		}

		foreach ($participantT2 as $key => $value) {
			for($i = 0;$i<sizeof($value);$i++){
				array_push($rank, $value[$i]);
			}
		}

		$details = $this->_mdb->get($this->_eventId, array("type" => "liveDetails"))[0];
		$toBeSend = array("rank" => $rank, "totalSubmissions" => $details['totalSubmissions'], "successfulSubmission" => $details['successfulSubmission']);
		return $toBeSend;

	}

	/*
	adds 1 to thenumber of submissions
	*/

	public function updateCompile(){
		$presentStatus = $this->_mdb->get($this->_eventId, array("type" => "liveDetails"))[0]['totalSubmissions'];
			if($this->_mdb->update($this->_eventId, array("type" => "liveDetails"),array("totalSubmissions" => $presentStatus + 1))){
				return true;
			}
			else{
				return false;
			}
	}

	/*
	adds 1 to the number of successful compiles
	*/

	public function updateSuccessfulCompile(){
		$presentStatus = $this->_mdb->get($this->_eventId, array("type" => "liveDetails"))[0]['successfulSubmission'];
			if($this->_mdb->update($this->_eventId, array("type" => "liveDetails"),array("successfulSubmission" => $presentStatus + 1))){
				return true;
			}
			else{
				return false;
			}
	}

	/*
	gets the present status of the event
		1. pending - The event has been created but the questions have not been posted, at this point no promotion starts, no user can register
		2. Scheduled  -The event has been scheduled, promotions and registrations take place at this point.
		3. running - The event is currently running
		4. ended - The event has ended
	*/

	public function getStatus(){
		return $this->_totalInformation['status'];
	}
/*
	public function updateQuestionAttempt($qid){
				$ps = $this->_mdb->get($this->_eventId, array("type" => "liveDetails"));
		$presentStatus = $ps[$qid][0];
		//print_r($ps);
		//		print_r($presentStatus);
		$total = $presentStatus['total'];
		$presentStatus['total'] = $total+1;
			if($this->_mdb->update($this->_eventId, array("type" => "liveDetails"),array($qid => (object)$presentStatus))){
				return true;
			}
			else{
				return false;
			}
		}


	public function updateSuccessfulQuestionAttempt($qid){
		$ps = $this->_mdb->get($this->_eventId, array("type" => "liveDetails"))[0];
		$presentStatus = $ps[$qid];
		$total = $presentStatus['success'];
		$presentStatus['success'] = $total+1;
		//if($qid==0){
		//	$qid="0";
		//}
		if($this->_mdb->update($this->_eventId, array("type" => "liveDetails"),array($qid => (object)$presentStatus))){
			return true;
		}
		else{
			return false;
		}
	}
 */



	public function getTotalInfo(){
		return $this->_totalInformation;
	}




}



?>
