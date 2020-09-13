<?php

require('config.php');
require('razorpay-php/Razorpay.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(Session::exists(Config::get('session/session_name')))
{
    $user = Session::get(Config::get('session/session_name'));
}
else{
    header("Location: https://www.codegreenback.com");                            
    exit();

}




use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;




if(isset($_SESSION['razorpay_order_id']))
{
    if (!empty($_POST['razorpay_payment_id']) && !empty($_POST['razorpay_signature']))
    {
        $api = new Api($keyId, $keySecret);

        $success = true;

        $error = "Payment Failed";


    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);

    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }

    $orderID = Session::get('order_id');
    $r_orderID = Session::get('razorpay_order_id');
    $pack = Session::get('package_to_buy');

    if ($success === true)
    {
        $db = DB::getInstance();


        $cc = Session::get('cc_to_transfer');
        $r_payment_id = $_POST['razorpay_payment_id'];
        $r_signature = $_POST['razorpay_signature'];
        $amount = Session::get('amout_to_paid');

        Session::removeOrder();

        if($db->processPackageBuy($orderID, $pack, $cc, $r_orderID, $r_payment_id, $r_signature, $amount))
        {       
                $date = date("Y-m-d H:i:s", time());
                $msg = "
                <div class='msg-box'>
                    <div class='msg-header'>
                        <h1 style='font-size:3vw'>Payment Completed Successfull</h1>
                    </div>                
                    <div class='msg-body'>
                        <h3>{$date}</h3>
                        <h3>Order No. <b>{$orderID}</b> </h3>
                        <h3>Pack : {$pack}</h3>
                    </div>
                  
                </div>
                ";
                Session::flash("payment",$msg);
                Email::sendOrderPurchasedEmail($user , Session::get(Config::get('session/user_email')) , $pack, $orderID, $r_orderID);
                header("Location: https://www.codegreenback.com/accounts/pay/status.php");
    
        }
        else{
            // echo "could not process";
            $date = date("Y-m-d H:i:s", time());
                $msg = "
                <div class='msg-box'>
                    <div class='msg-header'>
                        <h1 style='font-size:3vw'>Payment Could Not Be Completed !! :( </h1>
                    </div>                
                    <div class='msg-body'>
                        <h3>{$date}</h3>
                        <h3>Order No. <b>{$orderID}</b> </h3>
                        <h3>Reference ID : <b>{$r_orderID}</b></h3>
                        <h3>Pack : {$pack}</h3>
                    </div>

                    <div><p>Keep The Order ID and Reference ID safely if the amount is deducted from your bank !!</p></div>
                  
                </div>
                ";
                Session::flash("payment",$msg);
                Email::sendOrderPurchasedEmail($user , Session::get(Config::get('session/user_email')) , $pack, $orderID, $r_orderID);
                        
                
                        header("Location: https://www.codegreenback.com/accounts/pay/status.php");
                
            
        }

        
     
    }
    else
    {
        Session::removeOrder();
        
                $msg = "
                <div class='msg-box'>
                    <div class='msg-header'>
                        <h1 style='font-size:3vw'>Payment Could Not Be Completed !! :( </h1>
                    </div>                
                    <div class='msg-body'>
                        <h3>{$date}</h3>
                        <h3>Order No. <b>{$orderID}</b> </h3>
                        <h3>Reference ID : <b>{$r_orderID}</b></h3>
                        <h3>Pack : {$pack}</h3>
                    </div>

                    <div><p>Keep The Order ID and Reference ID safely if the amount is deducted from your bank !!</p></div>
                  
                </div>
                ";
                Email::sendOrderPurchasedErrorEmail($user , Session::get('email') , $pack, $orderID, $r_orderID);

                Session::flash("payment",$msg);
                header("Location: https://www.codegreenback.com/accounts/pay/status.php");
    }

    

}

}
require_once "../../after_login_header.php";
?>

