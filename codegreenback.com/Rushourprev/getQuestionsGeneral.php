<?php

 require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


$qid = $_POST["quesid"];
$typeOfQuestion = $_POST["typeOfQuestion"];


//echo $qid;
//echo $typeOfQuestion;

$ques = new Ques("challenge2",$qid);

$quesSend = $ques->get_question();

echo json_encode($quesSend,true);

//echo $quesSend;




?>