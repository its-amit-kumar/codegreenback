<?php

require_once dirname(__DIR__,1).'/core/init.php';


// $challengeid = $_POST["challengeid"];

// $challengeType = $_POST["challengeType"];

// $db = DB::getInstance();

// $result = $db->get($challengeType,array("id","=",strval($challengeid)))->results()[0]->common_questions;


// // echo $result;
// $questions = json_decode($result);



// $question1 = new Ques($challengeType,$questions->{1});
// $ques1 = $question1->get_question();

// // $question2 = new Ques($challengeType,$questions->{2});
// // $ques2 = $question2->get_question();

// // $question3 = new Ques($challengeType,$questions->{3});
// // $ques3 = $question3->get_question();

// // $sendData = array('1' => $ques1, '2' => $ques2, '3' => $ques3);
// $sendData = array('1' => $ques1,);

// echo json_encode($sendData);



/**
 * Middleware for handlelling all the 
 * Face/Off Requests
 */

if(isset($_POST['_ser']) && $_POST['_ser'] == '_10')
{
    if(isset($_POST['_cid']))
    {
        $user = Session::get(Config::get('session/session_name'));

        $obj = new Challenge3($_POST['_cid'], $user);

        echo $obj->sendData();


    }
}




?>
