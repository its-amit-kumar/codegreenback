<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';

/*   Middleware for running the code        */


$header = getBearerToken();

if($header)
{
    //echo $header;
    $tokendata = Token::jwt_challenge_verify($header);
    if($tokendata == 0)
    {
        echo json_encode(-1);
        exit;
    }
}

if(isset($_POST['code'])){
    $arr = array(
        'code' => $_POST['code'],
        'id' => $tokendata['cid'],
        'quesid' => $tokendata['ques'],
        'lang' =>$_POST['lang']
    );
    if(isset($_POST['custominput']))
    {
        $arr['custominput'] = $_POST['custominput'];
    }
    $result = Challenge1::runCode($arr);
    echo $result;
    if($result == 1)
    {
        Challenge1::challengecompleted($arr['id']);
    }
    //print_r(Challenge1::runCode($_POST));
}

  /*  middleware for fetching question   */

if(isset($_GET['fetchques']))
{
    print_r(Challenge1::FetchQues($tokendata['cid']));
}


if(isset($_GET['next_ques']))
{
    if(isset($_GET['id']))
    {
        if(Challenge1::update_user_que($_GET['id'],$_GET['flag'])){
            echo 1;
        }
    }
}

if(isset($_GET['endchallenge']))
{
    Challenge1::endChallenge($tokendata['cid']);
}


if(isset($_GET['service'])){
    if($_GET['service'] == 'challengeTimeDetails'){
        
        echo Challenge1::getChallengeTimeDetails($tokendata['cid']);
    }
}

?>
