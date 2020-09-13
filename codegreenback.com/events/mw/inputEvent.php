<?php

/*
The middleware to do all the processing when a code is submitted or ran.
*/



require_once $_SERVER["DOCUMENT_ROOT"].'/core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
  exit();
}


$id = $_POST['eventId'];

$mdb = new mongo("Events");
$res = $mdb->get($id,array("type" => "details"))[0];
$title = $res['title'];
$instructions = $res['instruction'];
$event = new event($id);



if (!$event->started()) {
    if($event->ended()){
        exit();
    }
  exit();
}

if(!$event->isUserRegistered()){
  exit();
}





/*if (Input::exists()){
	if(!Token::check(Input::get("coderun"),'coderun')){
		echo "string";
		die();
	}
}
*/

$runType = $_POST['runType'];
$CustomInput = $_POST['CustomInput'];
$code = $_POST["code_obj1"];
$language = $_POST["language"];
$QuestionId = $_POST['question_id'];
$input = $_POST['input'];
$eventId = $_POST['eventId'];

$toBeSend = array();

$problem = new event($eventId);

if(!$problem->isUserRegistered()){
	echo json_encode(array("status" => "notRegistered"));
	exit();
}

if(!$problem->started()){
	echo json_encode(array("status" => "notStarted"));
	exit();
}

if($problem->ended()){
	echo json_encode(array("status" => "ended"));
	exit();
}

$NoOfTestCases = $problem->getNoOfTestCases($QuestionId);
$NoOfPublicTestCases = $NoOfTestCases - $problem->getNoofPrivateTestCases($QuestionId);

if($runType == "submitCode" and $CustomInput == "false"){
	$problem->updateCompile();
	//$problem->updateQuestionAttempt($QuestionId);
		$passed = 0;
	$passedTestCaseArr = array();

	$toBeSend["runtype"]="submitrun";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;
	$toBeSend["NoOfTestCases"] = $NoOfTestCases;

	for ($i = 1;$i<=$NoOfTestCases;$i++){
		$testcase = array();
		if($i<=$NoOfPublicTestCases){

			$input = $problem->getInput($QuestionId,$i);
			
			//$problem->getInput($i);
			//echo $input;
			$result = $problem->MakeAPICall($language,$code,$input);
			//echo "Expected Output: <br> ";
			$expect = $problem->getExpectedOutput($QuestionId,$i);
			//echo $expect."<br>";
			//echo "Your Output: <br> ";
			$testcase["input"]=$input;
			$testcase["ExpectedOutput"]=$expect;
			if($problem->IsCompile($result->stderr)){

				//echo $result->stdout;
				if($result->stdout=="killed\n"){
					$testcase["output"]="Time Limit exceeded";
					$testcase["success"]="error";
					$testcase["errorType"]="timeLimit";

				}
				$testcase["output"] = $result->stdout;
				$a = $problem->ispassed($i,$result->stdout,$QuestionId);
				if($a == "true"){
					$testcase['success']="true";
					array_push($passedTestCaseArr, $i);
					$passed+=1;
				}
				else{
					$testcase["success"]="false";
				}

			}
			else{
				$testcase["output"]=$result->stderr;
				$testcase["success"]="error";
				$testcase["errorType"]="compilationErr";
			}
			$toBeSend[$i]=$testcase;
			
		}
		else{
			$input = $problem->getInput($QuestionId,$i);
			//$problem->getInput($i);
			//echo $input;
			$result = $problem->MakeAPICall($language,$code,$input);
			//echo "Expected Output: <br> ";
			$expect = $problem->getExpectedOutput($QuestionId,$i);
			//echo $expect."<br>";
			//echo "Your Output: <br> ";
			$testcase["input"]="Private TestCase";
			$testcase["ExpectedOutput"]="Private TestCase";
			if($problem->IsCompile($result->stderr)){

				//echo $result->stdout;
				if($result->stdout=="killed\n"){
					$testcase["output"]="Private TestCase";
					$testcase["success"]="error";
					$testcase["errorType"]="timeLimit";

				}
				$testcase["output"] = "Private TestCase";
				$a = $problem->ispassed($i,$result->stdout,$QuestionId);
				if($a == "true"){
					$testcase['success']="true";
					array_push($passedTestCaseArr, $i);

					$passed+=1;
				}
				else{
					$testcase["success"]="false";
				}

			}
			else{
				$testcase["output"]="Private TestCase";
				$testcase["success"]="error";
				$testcase["errorType"]="compilationErr";
			}
			$toBeSend[$i]=$testcase;
		
		}

	}
	$toBeSend["NoOfPassedTestCases"]=$passed;
	if($passed == $NoOfTestCases){
		$event->updateSuccessfulCompile();
		//$event->updateSuccessfulQuestionAttempt($QuestionId);
	}

}

if($runType == "runcode" and $CustomInput == "false"){
	$passed = 0;

	$toBeSend["runtype"]="runcode";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;

	for ($i = 1;$i<=$toBeSend["NoOfPublicTestCases"];$i++){
		$input = $problem->getInput($QuestionId,$i);
		$testcase = array();
		//$problem->getInput($i);
		//echo $input;
		$result = $problem->MakeAPICall($language,$code,$input);
		//echo "Expected Output: <br> ";
		$expect = $problem->getExpectedOutput($QuestionId,$i);
		//echo $expect."<br>";
		//echo "Your Output: <br> ";
		$testcase["input"]=$input;
		$testcase["ExpectedOutput"]=$expect;
		if($problem->IsCompile($result->stderr)){

			//echo $result->stdout;
			if($result->stdout=="killed\n"){
				$testcase["output"]="Time Limit exceeded";
				$testcase["success"]="error";
				$testcase["errorType"]="timeLimit";

			}
			$testcase["output"] = $result->stdout;
			$a = $problem->ispassed($i,$result->stdout,$QuestionId);
			if($a == "true"){
				$testcase['success']="true";
				$passed+=1;
			}
			else{
				$testcase["success"]="false";
			}

		}
		else{
			$testcase["output"]=$result->stderr;
			$testcase["success"]="error";
			$testcase["errorType"]="compilationErr";
		}
		$toBeSend[$i]=$testcase;
		
	}
	$toBeSend["NoOfPassedTestCases"]=$passed;
	echo json_encode($toBeSend);

}

if($CustomInput == "true"){
	
	$result = $problem->MakeAPICall($language,$code,$input);

	if($problem->IsCompile($result->stderr) && $result->stdout!="killed"){

		echo json_encode(array('runtype'=>'custom','1' =>array("success"=>"true","input"=>$input,"result"=>$result->stdout)));
	}
	else{
		if($result->stdout!="killed"){
			echo json_encode(array('runtype'=>'custom','1' =>array("success"=>"false","input"=>$input,"result"=>$result->stderr,"errorType"=>"compilationErr")));
		}
		if($result->stdout=="killed"){
			echo json_encode(array('runtype'=>'custom','1' =>array("success"=>"false","input"=>$input,"result"=>$result->stderr,"errorType"=>"timeLimit")));


		}
	}
}


if($runType == "submitCode" and $CustomInput == "false"){
	$res = $problem->getSolved();
	$res[$QuestionId] = $passedTestCaseArr;
	$problem->updateSolved($res);
	if($problem->isCompletelySolved()){
		//echo "string";
		if($problem->insertEnded()){
			$toBeSend['ended'] = true;
		}
	}
	else{
		$toBeSend['ended'] = false;
	}

	echo json_encode($toBeSend);
} 
?>
