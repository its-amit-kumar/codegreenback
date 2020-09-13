<?php
/*    middleware for handling user response to 
        challenge requests ---> 0->declined     1->accepted        */

include_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';


$header = getBearerToken();

if($header)
{
    if(Token::jwtVerify($header) == -1)
    {
        echo -1;
        exit;
    }
}
else{
    echo -1;
    exit;
}


if(isset($_POST['id']))
{
    foreach ($_POST['data'] as $key) {
        # code...
        if($key['id'] == $_POST['id'])
        {
            $data = $key;
            break;
        }
    }

    switch ($data['challengeName']) {
        case 'TurnYourTurn':
                            challenge1($data);
                        break;
        case 'Rushour':
                            challenge2($data);
                        break;
        case 'Face/Off':
                            challenge3($data);
                        break;
    }

    // print_r($data);
}



function challenge1($data)
{
    //   challenge1 class functions will be called
    $obj = new Challenge1($data);

    if($_POST['request'] == 'decline')
    {
        if($obj->delete($data)){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    elseif($_POST['request'] == 'accept')
    {
        if(Session::get(Config::get('session/user_type')) == "non-elite")
        {
            $db = DB::getInstance();
            $totalChallenges = $db->get('user_challenge_stats',array('username','=',Session::get('user')))->results();
            if($totalChallenges[0]->totalChallenges > 5)
            {
                echo json_encode(array('status'=>0,'msg'=>"User Challenge Limit Exceeded. Become an Elite Member To accept challenges !!!"));
                return 0;
            }
        }
        if($obj->accept($data))
        {
            $url = "https://www.codegreenback.com/texteditor_c1.php?id=".$data['id'];
            echo json_encode(array('status'=>1, 'url'=>$url));
        }
        else{
            echo json_encode(array('status'=>0));
        }
    }


}


function challenge2($data)
{
    //   challenge2 class functions will be called
    //   challenge1 class functions will be called
    $user = Session::get(Config::get('session/session_name'));
    $obj = new Challenge2($user,$data['id']);

    if($_POST['request'] == 'decline')
    {
        if($obj->delete($data)){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    elseif($_POST['request'] == 'accept')
    {

        if(Session::get(Config::get('session/user_type')) == "non-elite")
        {
            $db = DB::getInstance();
            $totalChallenges = $db->get('user_challenge_stats',array('username','=',Session::get('user')))->results();
            if($totalChallenges[0]->totalChallenges > 5)
            {
                echo json_encode(array('status'=>0,'msg'=>"User Challenge Limit Exceeded. Become an Elite Member To accept challenges !!!"));
                return 0;
            }
        }

        if($obj->accept($data))
        {
            $url = 'https://www.codegreenback.com/Rushour/challenge.php?cid='.$data['id']."&qNo=1";
            echo json_encode(array('status'=>1,'url'=>$url));
        }
        else{
            echo json_encode(array('status'=>0, 'msg'=>"An Error Ouccured !!! "));
        }
    }
}



function challenge3($data)
{
        //   challenge3 class functions will be called
    $url = "https://www.codegreenback.com/Faceoff/challenge.php?cid=".$data['id'];
    echo json_encode(array('status'=>1, 'url'=>$url));
}






/* ...........................Challenger Starts The Challenge 
          get Challenge Id and CHallenge name for processing of the challenge                              .............................*/

if(isset($_GET['request']) && isset($_GET['user']) && isset($_GET['id']) && isset($_GET['challenge']))
{
    if($_GET['user'] == 'challenger')
    {
        if($_GET['challenge'] == 'TurnYourTurn')
        {   
            if(Challenge1::setChallengerStartTime($_GET['id'], "challenge1"))
            {
            $url = "https://www.codegreenback.com/texteditor_c1.php?id=".$_GET['id'];
            echo json_encode(array('status'=>1,'url'=>$url));
            }
            else{
                echo 0;
            }

        }
        elseif ($_GET['challenge'] == 'Rushour') {
            
            $obj = new Challenge2(Session::get(Config::get('session/session_name')) , $_GET['id']);
            if($obj->check())
            {
                if($obj->setChallengerStartTime())
                {
                    $url = "https://www.codegreenback.com/Rushour/challenge.php?cid=". $_GET['id']."&qNo=1";
                    echo json_encode(array('status'=>1,'url'=>$url));
                }
                else{
                    echo json_encode(array('status'=>0, 'msg'=>"An Error Ouccured !!! "));
                }
            }
            else{
                echo 2;
            }
        }
        elseif($_GET['challenge'] == 'Face/Off'){
            echo json_encode(array('status' => 1, 'url' => "https://www.codegreenback.com/Faceoff/challenge.php?cid=".$_GET['id'] ));
        }
        
    }
}


?>
