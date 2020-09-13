<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
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


if(isset($_GET['service']) && $_GET['service'] == 'GeneralStats')
{
    if($data = StaticFunc::profileGeneralStats())
    {
        echo $data;
    }
    else{
        echo json_encode(array(0));
    }
}

if(isset($_GET['service']) && $_GET['service'] == 'Challenge1')
{
    $obj = new Profile();
    echo $obj->getChallenge1_Data();
}

if(isset($_GET['service']) && $_GET['service'] == 'Challenge2')
{
    $obj = new Profile();
    echo $obj->getChallenge2_Data();
}

if(isset($_GET['service']) && $_GET['service'] == 'Challenge3')
{
    $obj = new Profile();
    echo $obj->getChallenge3_Data();
}


if(isset($_GET['service']) && $_GET['service'] == 'UserFriends'){
    //
    if($friends = StaticFunc::profileUserFriends())
    {
        echo $friends;
    }
}

if(isset($_POST['service']) && $_POST['service'] == 'RC')
{

    if(isset($_POST['cid']) && isset($_POST['challenge']) && $_POST['token'])
    {
        $token = htmlspecialchars($_POST['token']);
        if(Token::check($token, 'csrf'))
        {
            switch ($_POST['challenge']) {
                case 'c1':
                        $challengeName = 'challenge1';
                    break;
                case 'c2':
                        $challengeName = "challenge2";
                    break;
                case 'c3':
                        $challengeName = "challenge3";
                    break;
                default:
                        $challengeName = 'null';
                    break;
            }
            
            if($challengeName != "null")
            {
                $obj = new Profile();
                if($obj->redeemCodeCoins($_POST['cid'] , $challengeName)){
                    echo json_encode(array('status'=>1,'msg'=>"Code Coin Redeemed Successfully"));
                }
            }
        }
    }
}

?>
