<?php

require_once dirname(__DIR__).'/core/init.php';
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



if(isset($_POST['_ser']) && isset($_POST['_t']))
{
    if($_POST['_ser'] == '_gD' && Token::check($_POST['_t'], 'csrf'))
    {

        $obj = new WithdrawCC();
        echo $obj->getData();
    }
}

if(isset($_POST['_ser']) && isset($_POST['_t']) && isset($_POST['data']))
{
    if($_POST['_ser'] == "_process" && Token::check($_POST['_t'], 'csrf'))
    {
        $obj = new WithdrawCC();
        echo $obj->makeTempReq($_POST['data']);
    }
}


if(isset($_POST['_ser']) && isset($_POST['_t']) && isset($_POST['data']))
{
    if($_POST['_ser'] == "_pin" && Token::check($_POST['_t'], 'csrf'))
    {
        // echo json_encode(array('status' => -2));
        $obj = new WithdrawCC();
        
        echo $obj->verifyTempReq($_POST['data']);
    }
    // echo json_encode(array('msg'=>"error"));
}



?>
