<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

//if (Input::exists()){
$id = $_POST["id"] or die("id");
$userType = $_POST["userType"] or die("ut");
$timeLeft = $_POST["timeLeft"] or die("tl");




$db = DB::getInstance();

if($userType == "challenger"){
			$endTime = strtotime($db->get('challenge2',array("id","=",$id))->results()[0]->challenger_end_time) or die("Unable to get time");
			// $this->_db->get("challenge2",array("id","=",$this->_challengeId))->results();
		}
		else{
			$endTime = strtotime($db->get('challenge2',array("id","=",$id))->results()[0]->opponent_end_time);
		}
if($timeLeft<=($endTime-time()) && $timeLeft>$endTime-time()-10){
	echo "True";
}
else{
	echo $endTime-time();
}
