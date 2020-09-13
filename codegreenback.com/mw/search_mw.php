<?php
/*    Middleware for getting search user data          */

require_once $_SERVER['DOCUMENT_ROOT']."/core/init.php";
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';
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
else
{
    echo -1;
    exit;
}


if(isset($_GET['query'])){
    $query = HTMLSPECIALCHARS(stripslashes(trim($_GET['query'])));
    if(strlen($query) == 0 || strlen($query) > 30 || !preg_match('/^[A-Za-z][A-Za-z0-9]{0,30}$/', $query)){
        echo json_encode(array());
    }
    else{
        echo json_encode(StaticFunc::SearchUserData($query));
    }
    
}


if(isset($_GET['service']))
{
    if($_GET['service'] == '_recents')
    {
        $data = StaticFunc::getRecents();
        if($data != null)
        {
            echo $data;
        }
        else
        {
            echo 0;
        }
        
    }
}



?>
