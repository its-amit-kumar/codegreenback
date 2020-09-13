<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$token = Token::generate('csrf');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?_i=<?php echo random_int(1,10000); ?>">
    <title>Forgot Password</title>
</head>
<body>
    
<div class="f-header">
    <div class="f-header-img-div">
        <img src="../../ok.png" alt="" style="width: 5vw; ">
    </div>

    <div class="f-header-body">
        <div class="f-header-title">
            <p style="font-size: 3vw">CodeGreenBack <sub style="font-size:1.5vw">...worth your while</sub></p>
            <!-- <p>...worth your while</p> -->
        </div>


        <div style="padding: 15px; height:100%">
            <a href="#" style="font-size:1.5vw; color:whitesmoke; text-decoration:none">About</a>
        </div>
    </div>
</div>


<!-- forgot password starts -->
    <div class="main-container">
        
        <div class="f-main-title">
            <p>Forgot Password ?</p>
        </div>


        <div class="f-main-body"> 
            <div style="width:40%; height:80%;  text-align:center; margin-top:5vh;border-right:0.4px solid black; padding:10px">
                <h3 style="font-size: 2vw;text-transform:capitalize">Don't worry ! enter your email address and we'll email you with the instructions to reset your password. </h3>
            </div>

            <div class="f-form">

                <div class="f-username-input-div">
                    <input type="hidden" name="f-token" id="f-token" value="<?php echo escape($token); ?>">
                    <div style="width: 100%; font-size:2.1vw;text-align:center; color:white;padding:25px">
                        <p>Email</p>
                    </div>

                    <div style="width: 70%">
                        <input type="email" name="f-email" id="f-email" class="f-inputs" placeholder="Enter Your Email" required>

                        <input type="hidden" name="g-token" id="g-token" value=''>
                    </div>
                </div>

                <div class="f-send-but-div">
                    <div style="width: 35%;">
                        <button type="submit" id="f-send-but">Send Email</button>
                        
                    </div>
                    
                </div>
                <div class="loader"></div>
                <div style="margin-top:8vh; padding-left:10px; display:flex; justify-content:space-between">
                    <div style="padding: 10px">
                        <p>Already Have An Account <a href="#">Login</a></p>
                    </div>

                    <div style="padding: 10px">
                        <a href="http://">SignUp</a>
                        <a href="http://">Git-Hub</a>
                    </div>
                </div>
                
            </div>

        </div>

        
    </div>

    <div class="f-footer-msg">
        <h3 id="f-msg"></h3>
    </div>

    


</body> 
</html>
<script src="https://www.google.com/recaptcha/api.js?render=6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="index.js?id=<?php echo random_int(1, 10000); ?>"></script>