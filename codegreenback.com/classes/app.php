<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

	class app
	{
		private $_db,
				$_code,
				$_output,
				$_UserId,
				$_QuestionId,
				$_language,
				$_isLoggedIn,
				$_fileName,
				$_points,
				$_Success,
				$_CustomInputName,
				$_CodeFile,
				$_OutputName,
				$_NoOfTestCases,
				$_PythonCode,
				$_CppCode,
				$_JavaCode,
				$_terminalCode,
				$_PathOfQuestion,
				$_QuestionInfoArr,
				$_APIUrl,
				$_JSON,
				$_NoOfPrivateTestCases,
				$_TestCasePointInfo,
				$_TypeOfQuestion;


		//sets the file name based on the language

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
			if ($language == "ruby"){
                                $this->_fileName = "main.rb";
                        }
			if ($language == "swift"){
                                $this->_fileName = "main.swift";
                        }
			if ($language == "javascript"){
                                $this->_fileName = "main.js";
                        }
			if ($language == "bash"){
                                $this->_fileName = "main.sh";
                        }
			if ($language == "haskell"){
                                $this->_fileName = "main.hs";
                        }
			if ($language == "erlang"){
                                $this->_fileName = "main.erl";
                        }


		}

		////prepares json to be send to APIbuidDocker

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

		public function getExpectedOutput($TestCaseNumber){

			$OutputCheck = file_get_contents(dirname(__DIR__,1).'/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');// or die('/var/www/html/php/data/questions/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');

			return $OutputCheck."\n";


		}

		////Makes the actual API call

		public function MakeAPICall($language,$content,$input){
			$this->PrepareJSON($language,$content,$input);
			$this->setFileName($language);
			$curl = curl_init();
			curl_setopt($curl , CURLOPT_URL , "http://13.233.153.141/APIbuilddocker.php");
    		curl_setopt($curl , CURLOPT_POST,TRUE);
    		curl_setopt($curl , CURLOPT_RETURNTRANSFER , TRUE);
    		curl_setopt($curl , CURLOPT_POSTFIELDS , $this->_JSON);
		$result = json_decode(curl_exec($curl));
		//echo $this->_JSON;
    		return $result;
		}

		//Checks if the code has successfully compiled or not

		public function IsCompile($result_arr){
			if ($result_arr==""){
				return true;
			}
			else{
				return false;
			}
		}



		 public function __construct($id,$type=null)							//Constructor gets the path of the question, initializes $_QuestionInfoArr and initializes $_PathOfQuestion
		{
			$this->_QuestionId = $id;
			$this->_db = DB::getInstance();
			if(isset($type)){
				$this->_QuestionInfoArr = $this->_db->get($type.'_que',array(
				'ques_id',
				'=',
				$this->_QuestionId

			))->results();
				$this->_TypeOfQuestion = $type;
			}
			else{
				$this->_QuestionInfoArr = $this->_db->get('questions',array(
				'ques_id',
				'=',
				$this->_QuestionId

			))->results();
				$this->_TypeOfQuestion = $this->_QuestionInfoArr[0]->type_of_question;
			}
			//$this->_TestCases = $this->_QuestionInfoArr['test_case'];
			$this->_PathOfQuestion = $this->_QuestionInfoArr[0]->path;
			$this->_NoOfTestCases = $this->_QuestionInfoArr[0]->total_test_cases;
			$this->_NoOfPrivateTestCases = $this->_QuestionInfoArr[0]->private_test_cases;
			$this->_TestCasePointInfo = json_decode($this->_QuestionInfoArr[0]->points_distribution);



		}

		//has been recents optimised, check the output against expected ouput to return true or false
		private function CheckCode($TestCaseNumber,$output){
			$OutputCheck = file_get_contents(dirname(__DIR__,1).'/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');// or die('/var/www/html/php/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');
			
			$OutputCheck2 = join("\n", array_map("trim", explode("\n", $OutputCheck)));
			//$OutputCheck = $OutputCheck2."\n";
                        $OutputCheck = $OutputCheck2;
			//$output =  join("\n", array_map("trim", explode("\n", $output)));
			

			if (json_encode(rtrim(trim($OutputCheck))) == json_encode(rtrim(trim($output)))){
				$this->_Success = "true";
			}
			else{
				$this->_Success = "false";
			}
		}



		//calls the checkCode function to check the ccode and set _Success variable to true or false and return _Success
		public function ispassed($InputTestCaseNumber,$output){

			$this->CheckCode($InputTestCaseNumber,$output);

			return $this->_Success;
		}

		public function getNoOfTestCases(){
			return $this->_NoOfTestCases;
		}
		public function getNoofPrivateTestCases(){
			return $this->_NoOfPrivateTestCases;
		}

		public function getInput($inputNumber){
			$a = file_get_contents(dirname(__DIR__,1).'/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/input-'.$this->_QuestionId.'-'.$inputNumber.'.txt');// or die('/var/www/html/php/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/input-'.$this->_QuestionId.'-'.$inputNumber.'.txt');
			return $a;
		}

		public function getPointsDistribution(){
			return json_decode($this->_QuestionInfoArr[0]->points_distribution);
		}

	}	

?>

<?php
// <!-- .............Made by amit .............. -->
// require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

// 	class app
// 	{
// 		private $_db,
// 				$_code,
// 				$_output,
// 				$_UserId,
// 				$_QuestionId,
// 				$_language,
// 				$_isLoggedIn,
// 				$_fileName,
// 				$_points,
// 				$_Success,
// 				$_CustomInputName,
// 				$_CodeFile,
// 				$_OutputName,
// 				$_NoOfTestCases,
// 				$_PythonCode,
// 				$_CppCode,
// 				$_JavaCode,
// 				$_terminalCode,
// 				$_PathOfQuestion,
// 				$_QuestionInfoArr,
// 				$_APIUrl,
// 				$_JSON,
// 				$_NoOfPrivateTestCases,
// 				$_TestCasePointInfo,
// 				$_TypeOfQuestion;

// 		//public $_terminalCode;

// 		private function setFileName($language){
// 			if ($language == "python"){
// 				$this->_fileName = "main.py";
// 			}
// 			if ($language == "c"){
// 				$this->_fileName = "main.c";
// 			}
// 			if ($language == "cpp"){
// 				$this->_fileName = "main.cpp";
// 			}
// 			if ($language == "java"){
// 				$this->_fileName = "main.java";
// 			}
// 		}

// 		private function PrepareJSON($language,$content,$input){
// 			$this->setFileName($language);
// 			$dataJSON = array(
// 				"language" => $language,
// 				"files" => [array(
// 					"name"=>$this->_fileName,
// 					"content"=>$content
// 				)],
// 				"stdin"=>$input,
// 				"command"=> ""
// 			);


// 			$this->_JSON = json_encode($dataJSON);
// 		}

// 		public function getExpectedOutput($TestCaseNumber){

// 			$OutputCheck = file_get_contents('data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');// or die('/var/www/html/php/data/questions/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');

// 			return $OutputCheck;


// 		}

// 		public function MakeAPICall($language,$content,$input){
// 			$this->PrepareJSON($language,$content,$input);
// 			$this->setFileName($language);
// 			$curl = curl_init();
// 			curl_setopt($curl , CURLOPT_URL , "http://localhost/api/APIbuilddocker.php");
//     		curl_setopt($curl , CURLOPT_POST,TRUE);
//     		curl_setopt($curl , CURLOPT_RETURNTRANSFER , TRUE);
//     		curl_setopt($curl , CURLOPT_POSTFIELDS , $this->_JSON);
//     		$result = json_decode(curl_exec($curl));
//     		return $result;
// 		}


// 		public function IsCompile($result_arr){
// 			if ($result_arr==""){
// 				return true;
// 			}
// 			else{
// 				return false;
// 			}
// 		}



// 		public function __construct($id)							//Constructor gets the path of the question, initializes $_QuestionInfoArr and initializes $_PathOfQuestion
// 		{
// 			$this->_QuestionId = $id;
// 			$this->_db = DB::getInstance();
// 			$this->_QuestionInfoArr = $this->_db->get('challenge2_que',array(
// 				'ques_id',
// 				'=',
// 				$this->_QuestionId

// 			))->results();
// 			//$this->_TestCases = $this->_QuestionInfoArr['test_case'];
// 			$this->_PathOfQuestion = $this->_QuestionInfoArr[0]->path;
// 			$this->_NoOfTestCases = $this->_QuestionInfoArr[0]->total_test_cases;
// 			$this->_NoOfPrivateTestCases = $this->_QuestionInfoArr[0]->private_test_cases;
// 			$this->_TestCasePointInfo = json_decode($this->_QuestionInfoArr[0]->points_distribution);
// 			$this->_TypeOfQuestion = $this->_QuestionInfoArr[0]->type_of_question;



// 		}


		// private function CheckCode($TestCaseNumber,$output){
		// 	$OutputCheck = file_get_contents('data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');// or die('/var/www/html/php/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/output-'.$this->_QuestionId.'-'.$TestCaseNumber.'.txt');
		// 	//echo "aa";
		// 	//echo $OutputCheck;
		// 	//echo $output;
		// 	$OutputCheck = $OutputCheck."\n";

		// 	if (json_encode($OutputCheck) == json_encode($output)){
		// 		$this->_Success = "true";
		// 	}
		// 	else{
		// 		$this->_Success = "false";
		// 	}
		// }




		// public function ispassed($InputTestCaseNumber,$output){

		// 	$this->CheckCode($InputTestCaseNumber,$output);

		// 	//echo $this->_Success;

		// 	return $this->_Success;
		// }

		// public function getNoOfTestCases(){
		// 	return $this->_NoOfTestCases;
		// }
		// public function getNoofPrivateTestCases(){
		// 	return $this->_NoOfPrivateTestCases;
		// }

		// public function getInput($inputNumber){
		// 	$a = file_get_contents('data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/input-'.$this->_QuestionId.'-'.$inputNumber.'.txt');// or die('/var/www/html/php/data/questions/'.$this->_TypeOfQuestion.'/question'.$this->_QuestionId.'/input-'.$this->_QuestionId.'-'.$inputNumber.'.txt');
		// 	return $a;
		// }

		// public function getPointsDistribution(){
		// 	return json_decode($this->_QuestionInfoArr[0]->points_distribution);
		// }

	//}	

?>
