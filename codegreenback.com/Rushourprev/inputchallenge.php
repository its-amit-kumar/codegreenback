<?php

require_once dirname(__DIR__,1).'/core/init.php';

if (Input::exists()){
	if(!Token::check(Input::get("coderun"),'coderun')){
		die();
	}
}

$challengeId = $_POST['challengeid'];
$runType = $_POST['runType'];
$CustomInput = $_POST['CustomInput'];
$code = $_POST["code_obj1"];
$language = $_POST["language"];
$QuestionId = $_POST['question_id'];
$input = $_POST['input'];


$challenge = new Challenge2(Session::get(Config::get('session/session_name')),$challengeId);

if($challenge->check()){
                        if(!$challenge->checkChallengeStatus()){
                                Redirect::to('../index');
                        }
                }
                else{
                        Redirect::to('../index');
                }



$problem = new app($QuestionId, 'challenge2');

$toBeSend = array();

$NoOfTestCases = $problem->getNoOfTestCases();
$NoOfPublicTestCases = $NoOfTestCases - $problem->getNoOfPrivateTestCases();
$solvedTestCasesInfo = array();
$testCasesInfo = json_decode($challenge->getQuestionsSolved(),TRUE);

if($runType == "submitCode" and $CustomInput == "false"){
		$passed = 0;

	$toBeSend["runtype"]="submitrun";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;
	$toBeSend["NoOfTestCases"] = $NoOfTestCases;

	for ($i = 1;$i<=$NoOfTestCases;$i++){
		$testcase = array();
		if($i<=$NoOfPublicTestCases){

			$input = $problem->getInput($i);
			
			//$problem->getInput($i);
			//echo $input;
			$result = $problem->MakeAPICall($language,$code,$input);
			//echo "Expected Output: <br> ";
			$expect = $problem->getExpectedOutput($i);
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
			//$problem->getInput($i);
			//echo $input;
			$result = $problem->MakeAPICall($language,$code,$input);
			//echo "Expected Output: <br> ";
			$expect = $problem->getExpectedOutput($i);
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
				$testcase["output"]="Private TestCase";
				$testcase["success"]="error";
				$testcase["errorType"]="compilationErr";
			}
			$toBeSend[$i]=$testcase;
		
		}

	}
	$toBeSend["NoOfPassedTestCases"]=$passed;
	echo json_encode($toBeSend);

}

$challenge->updateSolvedTestCases($testCasesInfo);

if($runType == "runcode" and $CustomInput == "false"){
	$passed = 0;

	$toBeSend["runtype"]="runcode";
	$toBeSend["NoOfPublicTestCases"] = $NoOfPublicTestCases;

	for ($i = 1;$i<=$NoOfPublicTestCases;$i++){
		$input = $problem->getInput($i);
		$testcase = array();
		//$problem->getInput($i);
		//echo $input;
		$result = $problem->MakeAPICall($language,$code,$input);
		//echo "Expected Output: <br> ";
		$expect = $problem->getExpectedOutput($i);
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




?>
