<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(Session::exists(Config::get('session/session_name')))
{
    $user = Session::get(Config::get('session/session_name'));
}
else{
    header("Location: https://www.codegreenback.com/Home/");                            
    exit;

}

require_once "../../after_login_header.php";

$msg = '';
$msg = Session::flash("payment");
    if($msg != '')
    {
        echo $msg;
    }




?>

<style>
    .msg-box{
        width: 60vw;
        height: 40vh;
        /* text-align: center; */
        margin: 0 auto;
        margin-top: 10vh;
        background-color: white;
    }

    .msg-header{
        padding: 25px;
        background-color: rgb(247, 255, 237);
        border-bottom: 0.5px solid rgba(0, 0, 0, 0.4);

    }

    .msg-body {
        padding: 15px;
    }

    .msg-body h3 {
        padding: 5px;
        font-size: 1.5vw;
    }
</style>

 <!-- <div class='msg-box'>
     <div class='msg-header'>
        <h1 style='font-size:3vw'>Payment Completed Successfully !!!</h1>
     </div>
    <div class='msg-body'>
            <h3>2020-06-12 23:09:05</h3>
            <h3>Order No. <b>ORD_882b234357a</b></h3>
            <h3>Pack : basic Package( 160 + 40 ) CodeCoins</h3>
    </div>

</div> -->
