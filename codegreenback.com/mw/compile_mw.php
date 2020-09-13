<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';

/*   Middleware for running the code        */


$header = getBearerToken();

if($header)
{
    //echo $header;
    if(Token::jwtVerify($header) == -1)
    {
        echo json_encode(-1);
        exit;
    }
}


if(isset($_POST['code']) && isset($_POST['service']) ){
    if($_POST['service'] == 'Compile_and_run')
    {
        $result = Challenge1::codeCompile($_POST);
        echo $result;
    }
 

}

?>