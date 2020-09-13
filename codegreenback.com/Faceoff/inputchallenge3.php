<?php

require_once dirname(__DIR__,1).'/core/init.php';
require_once dirname(__DIR__,1)."/Faceoff/vendor2/autoload.php";
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;


if(Session::exists(Config::get('session/session_name')))
{
	$username = Session::get(Config::get('session/session_name'));
}
else{
	exit();
}


$runType = $_POST['runType'];
$CustomInput = $_POST['CustomInput'];
$code = $_POST["code_obj1"];
$language = $_POST["language"];
$QuestionId = $_POST['question_id'];
$input = $_POST['input'];
$challengeId = $_POST['challengeid'];
$user = new User();

$problem = new app($QuestionId,'challenge3');

$challenge3 = new Challenge3($challengeId,$user->getPresentUser());

$toBeSend = array();

$NoOfTestCases = $problem->getNoOfTestCases();
$NoOfPublicTestCases = $NoOfTestCases - $problem->getNoofPrivateTestCases();
$solvedTestCasesInfo = array();

if($runType == "submitCode" and $CustomInput == "false"){
		$passed = 0;

	$toBeSend["runtype"]="submitrun";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;
	$toBeSend["NoOfTestCases"] = $NoOfTestCases;

	for ($i = 1;$i<=$NoOfTestCases;$i++){

		$testcase = array();

		if($i<=$NoOfPublicTestCases){

			$input = $problem->getInput($i);

			$result = $problem->MakeAPICall($language,$code,$input);
			$expect = $problem->getExpectedOutput($i);
			$testcase["input"]=$input;
			$testcase["ExpectedOutput"]=$expect;
			if($problem->IsCompile($result->stderr)){
				if($result->stdout=="killed\n"){
					$testcase["output"]="Time Limit exceeded";
					$testcase["success"]="error";
					$testcase["errorType"]="timeLimit";

				}
				$testcase["output"] = $result->stdout;
				$a = $problem->ispassed($i,$result->stdout);
				if($a == "true"){
					$testcase['success']="true";
					$passed+=1;
					if(empty($testCasesInfo)){
						$testCasesInfo[$QuestionId] = array($i);
				
					}
					elseif ($testCasesInfo[$QuestionId] == "") {
						$testCasesInfo[$QuestionId] = array($i);
				
					}
					else{
						if(!in_array($i,$testCasesInfo[$QuestionId])){
							array_push($testCasesInfo[$QuestionId],$i);
					
						}
					}
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
			$input = $problem->getInput($i);
			$result = $problem->MakeAPICall($language,$code,$input);
			$expect = $problem->getExpectedOutput($i);
			$testcase["input"]="Private TestCase";
			$testcase["ExpectedOutput"]="Private TestCase";
			if($problem->IsCompile($result->stderr)){
				if($result->stdout=="killed\n"){
					$testcase["output"]="Private TestCase";
					$testcase["success"]="error";
					$testcase["errorType"]="timeLimit";

				}
				$testcase["output"] = "Private TestCase";
				$a = $problem->ispassed($i,$result->stdout);
				if($a == "true"){
					$testcase['success']="true";
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

	// print_r($toBeSend);

}



if($runType == "runcode" and $CustomInput == "false"){
	$passed = 0;

	$toBeSend["runtype"]="runcode";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;

	for ($i = 1;$i<=$NoOfPublicTestCases;$i++){
		$input = $problem->getInput($i);
		$testcase = array();
		$result = $problem->MakeAPICall($language,$code,$input);
		$expect = $problem->getExpectedOutput($i);
		$testcase["input"]=$input;
		$testcase["ExpectedOutput"]=$expect;
		if($problem->IsCompile($result->stderr)){
			if($result->stdout=="killed\n"){
				$testcase["output"]="Time Limit exceeded";
				$testcase["success"]="error";
				$testcase["errorType"]="timeLimit";

			}
			$testcase["output"] = $result->stdout;
			$a = $problem->ispassed($i,$result->stdout);
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

$version = new Version2X("https://www.codegreenback.com:1528");

$client = new Client($version);

if ($runType == "submitCode") 
{
	if ($passed == $NoOfTestCases) 
	{

		if($challenge3->isSolved($QuestionId))
		{
			$toBeSend['status']="false";
			echo json_encode($toBeSend);

			exit();
		}
		else
		{	
			if($challenge3->setWinner())
			{

				$client->initialize();
				$client->emit("111",["winner"=> $username,'room_id' => $challengeId]);         //111 i.e. challenge over won by current user
				$client->close();
				$toBeSend['status']="true";
				echo json_encode($toBeSend);
			}

			// $challenge3->insertSolvedQuestion($QuestionId);
		}
	}
	else
	{
		$toBeSend['status']="false";
		echo json_encode($toBeSend);
	}
}

?>
