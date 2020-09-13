<?php
/*   class to deal with emails               */


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'].'/mail_token/vendor/autoload.php';
//$mail = new PHPMailer(true);

class Email {

    public static function sendAuthEmail($name,$url,$email){

        $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$name.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;">Your email was provided for registration on Code GreenBack and you were successfully registered.</p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"> To confirm your email please click on Confirm </span>
        
        
            <br>
            <span><a href='.$url.' >Confirm Email</a></span>
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';


        if(Email::sendMail($msg,$email, 'Verify Your Email !')){
            return true;
        }else{
            return false;
        }
        

    }



    public static function sendPasswordChangeEmail($name, $url, $email)
    {
        $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$name.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;">You recently requested to reset your password for your account in codegreenback .</p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"> To Change your Password,  please click on Reset Password </span>
        
        
            <br>
            <span><a href='.$url.' >Reset Password</a></span>

            <p style="font-family: \'Candara\';font-size: 17px;">if you did not request a password reset, please ignore this email or reply to let us know. the password reset is only valid for 15 minutes </p>
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';



        if(Email::sendMail($msg,$email , 'Reset Password !!')){
            return true;
        }else{
            return false;
        }

    }



    public static function SendChallengeRequestEmail($challenger, $challengeName, $cc,  $email_to_sent, $user)
    {
        $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$user.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;"><span style="font-size:25px">'.$challenger.' </span> Has Challenged You In<span style="font-size:25px">'.$challengeName.' </span> </p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"> CodeCoins :<span style="font-size:20px">'.$cc.' </span> </span>
        
 

        
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';

        if(Email::sendMail($msg,$email_to_sent , 'Challenge Request !!')){
            return true;
        }else{
            return false;
        }
    }




    public static function sendChallengeAcceptEmail($user , $opponentUsername , $opponentEmail , $challengeName , $cc)
    {
                $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$opponentUsername.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;"><span style="font-size:25px">'.$user.' </span> Has Accepted Your Challenge "<span style="font-size:25px">'.$challengeName.'" </span> </p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"> CodeCoins :<span style="font-size:20px">'.$cc.' </span> </span>
        
 

        
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';

        if(Email::sendMail($msg,$opponentEmail , 'Challenge Accepted !!')){
            return true;
        }else{
            return false;
        }
    }


    public static function sendOrderPurchasedEmail($user ,$Email , $pack, $order_id, $reference_id)
    {
                $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$user.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;"><span style="font-size:25px">'.$pack.' </span> Has Been Successfully Purchased !! </p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"><span style="font-size:20px"> OrderID :'.$order_id.' </span> </span>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"><span style="font-size:20px"> Reference ID :'.$reference_id.' </span> </span>

        
 

        
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';

        if(Email::sendMail($msg,$Email , 'Order Purchased Successfull !!')){
            return true;
        }else{
            return false;
        }
    }


    public static function sendOrderPurchasedErrorEmail($user ,$Email , $pack, $order_id, $reference_id)
    {
                $msg = '   

        <body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$user.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;"><span style="font-size:25px">Your Order '.$pack.' </span> Could Not Be Processed.</p>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"><span style="font-size:20px"> OrderID :'.$order_id.' </span> </span>
      <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"><span style="font-size:20px"> Reference ID :'.$reference_id.' </span> </span>

        
 

        
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team Code GreenBack</p></p>
    
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';

        if(Email::sendMail($msg,$Email , 'Order Purchase UnSuccessfull !!')){
            return true;
        }else{
            return false;
        }
    }


  public static function sendVerificationPin($user ,$email , $cc, $processing_id, $pin)
    {
        $msg = '<body style="align-content: center;margin:0 auto;width:550px;">
        <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
        <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
        <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">CodeGreenBack</span></div>
    </div>
    
    <div style="margin-left: 20px;">
         <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span>'.$user.'</span>,<br>
         <p style="font-family: \'Candara\';font-size: 17px;"><span style="font-size:25px">Your 6 digit verification code for CodeCoin Withdrawal is : </span><span style="font-style: oblique; font-size:28px; letter-spacing:3px; color:red">'.$pin.'</span></p>
        <div>
          <table>
            <caption style="font-weight: bold;">Request Details</caption>
            <tr>
              <td style="font-weight:bold">Processing ID :</td>
              <td>'.$processing_id.'</td>
            </tr>
            <tr>
              <td style="font-weight:bold">CodeCoins :</td>
              <td>'.$cc.'</td>
            </tr>
            <tr>
              <td style="font-weight:bold">Date :</td>
              <td>'.date("Y-m-d H:i:s",time()).'</td>
            </tr>
          </table>

        </div>

        
    <p style="font-size: 15px;">Please Note : This verification code is valid for only 10 minutes.</p>
    <p style="font-size: 15px;">If you don\'t recognise this activity, we recommend you to change your codegreenback account password. </p>

        
    <p style="font-family: Candara;font-size: 17px;">Thank you for your interest in CGb! &nbsp;
    <p style="font-family: Candara;font-size: 17px; font-weight: bolder;">Happy Coding!
    <br>
    Team CodeGreenBack</p></p>
    </div>  
    
    <div style="padding-top:0px;background-color:#1f1f2e; margin:0 auto;width:550px;text-align: center;"><h4 style="text-align:center;font-family: \'Verdana\'"><span style="color:white;">Connect with us </span> </h4>
     <div class="container" style="font-size: 17px;color:white;">
      <a href="#"><i class="fa fa-envelope" aria-hidden="true" style="color:white;"></i>&nbsp;<span style="color:white;"> Email</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-instagram" style="color:white;"></i>&nbsp;<span style="color:white;">Insta</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-twitter" style="color:white;"></i>&nbsp;<span style="color:white;">Twitter</span></a>&nbsp;&nbsp;
                              <a href="#"><i class="fab fa-facebook" style="color:white;"></i><span style="color:white;" >&nbsp;Facebook</span></a>&nbsp;&nbsp;&nbsp;
                              <a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="color:white;"></i><span style="color:white;">&nbsp;LinkedIn</span></a></div>
    </div> 
    
    </body> ';


        if(Email::sendMail($msg,$email , 'Verification Code !!')){
            return true;
        }else{
            return false;
        }


    }


 public static function sendEventAlert($emails = array())
    {
        $msg = '   <section style="background: rgb(48,227,202);
background: linear-gradient(100deg, rgba(48,227,202,1) 0%, rgba(0,212,255,1) 100%);padding: 15px;">

        <div style="text-align: center;width: 50vw;margin:0 auto">
            <h1>CODING EVENT ALERT</h1>
            <h2>CodeGreenBack comes with its foremost coding event - \'Try {code} We will catch\' with exciting prizes. The event will commence at 19:00 IST, 12th September 2020 till 19:00 IST, 13th September 2020. The registration is free of cost.</h2>
        </div>

        <div style="text-align: center;width: 50vw;margin:0 auto">
            <h3 style="padding: 5px; border-bottom: 1px solid grey;">About the event</h3>
            <h4>
                There will be maximum of 10 coding questions. <br>
                Time Duration: <span style="font-weight: bolder;">24 hours</span>
            </h4>
        </div>

        <div  style="text-align: center;width: 50vw;margin:0 auto">
            <h3 style="padding: 5px; border-bottom: 1px solid grey;">Prizes</h3>
            <ul>
                <li>Elite members will be awarded with 50 CC.</li>
                <li>Non-Elite members will be given Elite membership along with CCs they possess.</li>
            </ul>
        </div>
        
        <div>
            <h4>Website Link to Register : www.codegreenback.com <br>
Stay Tuned to enroll in more such stimulating coding events. <br>

Regards <br>
Team CodeGreenBack</h4>
        </div>

    </section>';
	$count = 0;
	$failed = 0;
        for ($i=0; $i  < count($emails) ; $i++) { 
            if(Email::sendMail($msg,$emails[$i]->email,'Event Alert')){
		$count =  $count  + 1;
		}
	   else{
			$failed = $failed + 1;
		}
        }
	echo "finished";
	echo "passed : ".$count;
	echo "failed : ".$failed;
    }

    public static function sendMail($msg,$email, $subject)
    {
        $mail = new PHPMailer(true);

        try{
           $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.sendgrid.net';    //sg2plcpnl0073.prod.sin2.secureserver.net
            $mail->SMTPAuth = true;
        
            $mail->Username = 'apikey';
            $mail->Password = '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';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('donotreply@codegreenback.com','Team CGB');
            $mail->addAddress($email);
            $mail->addReplyTo('support@codegreenback.com');
            $mail->isHTML(true);
          
            $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/public/img/1.png',"1.png");
            $mail->Subject = $subject;
            $mail->Body = $msg;

            $mail->send();
            return true;
        }catch(Exception $e)
        {
		error_log($e);
            return false;
        }
    }


}




?>
