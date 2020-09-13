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


    if(isset($_GET['service']))
    {
        if($_GET['service'] == 'get_offers')
        {
            if(isset($_GET['token']))
            {
                if(Token::check($_GET['token'] , 'csrf'))
                {
                    // echo "everything verified successfully";
                    $obj = new CodeCoins();
                    print_r($obj->getOffers());
                }
                else{
                    echo "token not validate";
                }
            }
         
        }
     
    }


?>