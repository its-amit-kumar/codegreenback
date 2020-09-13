<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(Session::exists(Config::get('session/session_name')))
        {
                $user = Session::get(Config::get('session/session_name'));
                $obj = new Challenge2($_SESSION[Config::get('session/session_name')] , $_POST['cid']);
                if($obj->check()){
                        if(!$obj->checkChallengeStatus()){
                                exit();
                        }
                }
                else{
                        Redirect::to('../index');
                }
        }


$challengeid = $_POST["cid"];
$questionNo = $_POST['qno'];

if($obj->isChallenger()){
  $typeOfUser = "challenger";
}
else{
  $typeOfUser = "opponent";
}

$endTime=$obj->getTime($typeOfUser,$challengeid) or die("could not get time");

$timeLeft = $endTime - time();

$qn= $_POST['qno'];

if($questionNo>3 || $questionNo<1){
    exit();
}

$db = DB::getInstance();

$result = $db->get("challenge2",array("id","=",strval($challengeid)))->results()[0]->common_questions;

$questions = json_decode($result);

$question1 = new Ques("challenge2",$questions->{$questionNo});
$ques1 = $question1->get_question();

$info = $db->get('challenge2',array("id","=",$challengeid))->results()[0];

$ques1["time"] = $timeLeft;
$ques1['challenger'] = $info->challenger;
$ques1['opponent'] = $info->opponent;

//$question2 = new Ques($challengeType,$questions->{2});
//$ques2 = $question2->get_question();

//$question3 = new Ques($challengeType,$questions->{3});
//$ques3 = $question3->get_question();

//$sendData = array('1' => $ques1, '2' => $ques2, '3' => $ques3);
$ques1['solved'] = $obj->getSolvedQuestions();

echo json_encode($ques1);




?>
