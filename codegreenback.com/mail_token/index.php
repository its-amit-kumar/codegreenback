<?php

require_once "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';
$mail = new PHPMailer(true);

try{
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'codegreenback19@gmail.com';
    $mail->Password = 'amit19kshitij19ayush19';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('codegreenback19@gmail.com','Team CGB');
    $mail->addAddress('drmansi2000@gmail.com');
    $mail->addReplyTo('ayushsrivastava170@gmail.com');
    $mail->isHTML(true);
  
    $mail->addEmbeddedImage('1.png',"1.png");
    $mail->Subject = "Verify Your Email";
    $mail->Body = '   

    <body style="align-content: center;margin:0 auto;width:550px;">
    <div style=" margin:0 auto;width:550px;background-color: #1f1f2e; border-bottom-color: #00cc99;border-bottom-width: 15px;border-bottom-style:solid;">
    <div style="float:left;padding-top: 20px;"> <img src="cid:1.png" style="width:70px; height:70px; margin: 5px 5px 0px 5px;"></div>&nbsp;&nbsp;&nbsp;
    <div style="color:white;font-family: \'Josefin Sans\', sans-serif;height:70px;padding-top: 30px;font-weight:bolder; font-size: 50px;"><span style="padding-top: 50px;">Code GreenBack</span></div>
</div>

<div style="margin-left: 20px;">
     <span style="font-family: \'Candara\';font-size: 25px;"> Hi <span></span>,<br>
     <p style="font-family: \'Candara\';font-size: 17px;">Your email was provided for registration on Code GreenBack and you were successfully registered.</p>
  <span style="font-family: Candara;font-size: 17px;font-weight: bolder;"> To confirm your email please follow the link <link></span>
    
    
        <br>
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









    $mail->send();
    echo "message has been sent ";
}catch(Exception $e)
{
    echo $e;
}
 


?>