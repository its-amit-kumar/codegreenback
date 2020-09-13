<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

Session::exists(Config::get('session/session_name'))?Redirect::to('Home'):false;
  $msg ='';
  if(Session::exists('signUp')){
    $msg = Session::flash('signUp');
  }

$token1 = Token::generate('signup');
$token2 = Token::generate('login');
$tokeng = Token::generate('csrf');
?>



<!DOCTYPE html>
<html lang="en">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-166333071-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-166333071-1');
</script>

<meta charset="UTF-8">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CodeGreenBack</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab%7CRoboto:400,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://www.codegreenback.com/public/css/001/style.css?id=006">
  <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/3b74ec65a6.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Merienda+One" />

  <script type="text/javascript">
    $(document).ready(function () {
      $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
          breakpoint: 768,
          settings: {
            slidesToShow: 4
          }
        }, {
          breakpoint: 520,
          settings: {
            slidesToShow: 3
          }
        }]
      });
    });
  </script>  


<meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <meta name="description" content="CodeGreenback is the new era of competitive coding. A place where you can code and earn money, make friends and challenge them to a 1v1 battle Arena. We're loaded with ton of new feature. A social coding platform for coders to code and chill, because that's what coders do."/> 

   <meta name="keywords" content="earn money, earn & code, cgb, programming competition, programming contest, online programming, online computer programming, best competitive coding">

   

</head>

<body>



  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- partial:index.partial.html -->
  <div id="header" class="header">
   <a><img src="https://www.codegreenback.com/public/img/1.png" class="logo" alt="CodeGreenBack" id="header-img" ></img><pre style="margin-top: -3.7em;"><span1 style="font-size: 23px;">         <B>CodeGreenBack</B> <sub style="font-size: 10px;">worth your while</sub> </span1></pre></a>
    <nav id="nav-bar" class="nav">
      <ul class="nav-list">
        
        <li class="nav-item">
          <a class="nav-link"  id="show_login_but">Sign In</a>
        </li>
        <li class="nav-item">
          <a class="nav-link1 nav-link-cta" id="show_signup_but">Sign Up</a>
        </li>
      </ul>
    </nav>
    <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
      <span class="visuallyhidden">Menu</span>
      <span class="hamburger"></span>
    </button>
    
  </div>
  <main>
    <section class="intro">
      <h1 class="intro__title" style="margin-left: -.2em;font-size:2.7em">
        Start your Coding Journey
      </h1>
      <p class="intro__subtitle">
        CodeGreenBack is a social competitive coding platform with exciting challenges giving you opportunities to<br> win real money. 
      </p>
     
       <a  class="button intro__button" id='get_started_but'>Get Started</a>
      <img class="intro__illustration" src="https://www.codegreenback.com/public/img/001/welcome.gif" alt="" />
    </section>

    <section>
      <div class="rt-container">
        <div class="col-rt-12">

          <h2 style="margin-top: 2em;font-size: 2em;">Languages We Support</h2>
          <div class="customer-logos slider-icon" style="margin-top: 3em;">
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/python.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/c.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/java.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/c++.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/ruby.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/js.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/bash.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/golang.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/swift.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/erlang.png"></div>
            <div class="slide"><img class="slide-logo-img" src="/public/img/001/haskell.png"></div>
          </div>

        </div>
      </div>
    </section>

    <section class="get-feedback">
      <div class="container">
        <div class="row" style="margin-top: 1em;">
          <div class="col-xl-5 col-sm-5 "><img src="https://www.codegreenback.com/public/img/001/challenge.svg" class="challengeimg"
              style="width: 120%;border-radius: 20px;margin-left:-9em;margin-top:13em;"></div>
          <div class="col-xl-7">
            <div>
            </div>
          </div>
        </div>
      </div>
      <h2 class="section__title get-feedback__title challengeheading " style="margin-right:-2em; margin-top:-10em;">
        CHALLENGE
      </h2>
      <p class="challengepara" style="margin-left:7em; margin-top: 21em;">
        We are here for you. Participate in the weekly live events, get the tools and resources you need, and find
        friendships with people that have the same goal as you and challenge!
      </p>


    </section>

    <div class="arrow-1" style="margin-top: 3em;"></div>

    <section class="learning">
      <div class="col-xl-3">
        <h2 class="section__title grow__title coinsheading" style="margin-top:4em;">EARN WITH CODE</h2>
        <p style="margin-left: 3em; margin-top: -1em;" class="coinspara">
          Challenge your friends using codecoins, win and earn real money. Yay! So, let's code for coins.
        </p>
      </div>
      <img src="https://www.codegreenback.com/public/img/001/Earn.svg" class="earnimg" style="width: 100%;margin-left: 10em; margin-top: 3em;">


    </section>

    <div class="arrow-2" style="margin-bottom: -3em; margin-top:5em;"></div>

    <section id="how-it-works" class="grow">
      <div class="container">
        <div class="row" style="margin-top:-9em">
          <div class="col-xl-5 col-xs-2">
            <h2 class="section__title grow__title learnheading">LEARN</h2>
            <p class="learnpara">
              No matter what is your experience level, you will be able to write real, working code in minutes! 
            </p>
          </div>
          <div class="col-xl-7  col-xl-1">
            <div>
            </div>
          </div>
        </div>
      </div>
      <img src="https://www.codegreenback.com/public/img/001/Learn.svg" style="width: 120%;border-radius: 20px; margin-top: -1em;margin-left: 3em;"
        class="learn-svg">
    </section>
    <div class="arrow-3" style="margin-top:4em;"></div>
    <section class="get-feedback">
      <div class="container">
        <div class="row" style="margin-top: 1em;">
          <div class="col-xl-5 col-sm-5 "><img src="https://www.codegreenback.com/public/img/001/practice.svg" class="practiceimg"
              style="width: 120%;border-radius: 20px;margin-left:-9em;margin-top:-3em;"></div>
          <div class="col-xl-7">
            <div>
            </div>
          </div>
        </div>
      </div>
      <h2 class="section__title get-feedback__title practiceheading " style="margin-right:-2em; margin-top:-11em;">
        PRACTICE
      </h2>
      <p class="practicepara" style="margin-left:7em; margin-top: 8em;">
      Practice with Patience is the only rule to exceed in programming. We bring you a bank of questions to practice and excel in writing code.
      </p>


    </section>




    <!-------CARDS FOR CHALLANGE----------
<div class="containerr">
  <div class="card">
    <div class="imgBx">
      <img src="https://www.codegreenback.com/public/img/001/fn (1).jpg">
    </div>
    <div class="content">
      <h2>TURIN</h2>
      <p>
        In this challenge, you and your friend will get a question that will be available for 24 hours. The contestent in the duo who passes the code successfully in minimum time will win.
      </p>
    </div>
  </div>
  <div class="card">
    <div class="imgBx">
      <img src="https://www.codegreenback.com/public/img/001/rushour.jpg">
    </div>
    <div class="content">
      <h2>RUSHOUR</h2>
      <p>
        In this challenge, you and your friend will get a question that will be available for 24 hours. The contestent in the duo who passes the code successfully in minimum time will win.
      </p>
    </div>
  </div>
  <div class="card">
    <div class="imgBx">
      <img src="https://www.codegreenback.com/public/img/001/fn (1).jpg">
    </div>
    <div class="content">
      <h2>FACEOFF</h2>
      <p>
        In this challenge, you and your friend will be given unlimited questions. The one  who will solve more number of questions in 30 mins wins.
      </p>
    </div>
  </div>
</div>-->
    <h5 style="text-align: center;font-size: 2em; margin-bottom: 1em;"> WHAT'S NEW IN CHALLANGE </h5>
    <section class="wrapper1">
      <div class="news-card">
        
        <img src="https://www.codegreenback.com/public/img/001/fn.jpg" alt="" class="news-card__image turnyourturn">
        <div class="news-card__text-wrapper">

          <div class="news-card__details-wrapper">
            <p class="news-card__excerpt">In this challenge, you and your friend will get a question that will be
              available for 24 hours. The contestent in the duo who passes the code successfully in minimum time will
              win.</p>
            <a href="https://www.codegreenback.com/docs/challenges/TurnYourTurn" target='_blank' class="news-card__read-more">    Explore<i class="fas fa-long-arrow-alt-right"></i></a>
          </div>
        </div>
      </div>

      <div class="news-card">
        
        <img src="https://www.codegreenback.com/public/img/001/2.jpg" alt="" class="news-card__image rushour">
        <div class="news-card__text-wrapper">

          <div class="news-card__details-wrapper">
            <p class="news-card__excerpt">
		Challenge Your Opponent in Rushour and solve all the problems in a time limit of 30 minutes to win the challenge Learn More 
	    </p>
            <a href="https://www.codegreenback.com/docs/challenges/Rushour" target='_blank' class="news-card__read-more">Explore <i class="fas fa-long-arrow-alt-right"></i></a>
          </div>
        </div>
      </div>

      <div class="news-card">
        
        <img src="https://www.codegreenback.com/public/img/001/faceoff.png" alt="" class="news-card__image faceoff">
        <div class="news-card__text-wrapper">
          <div class="news-card__details-wrapper">
            <p class="news-card__excerpt">Challenge Your Opponent in Face/Off and enjoy the experience of realtime challenge. The users who completes the question first wins. *The Opponent must be online to have a Face/Off </p>
            <a href="https://www.codegreenback.com/docs/challenges/faceoff" target='_blank' class="news-card__read-more">Explore <i class="fas fa-long-arrow-alt-right"></i></a>
          </div>
        </div>
        </div>
    </section>


    <!--  FOOTER START -->

    <div class="footer">
      <div class="inner-footer">

        <!--  for company name and description -->
        <div class="footer-items">
          <img src="https://www.codegreenback.com/public/img/1.png" style="width: 30%;"><br>
          <h3>Code Green Back</h3>
          <p class="one-one">Worth Your While</p>
        </div>

        <!--  for quick links  -->
        <div class="footer-items" style='display:flex;justify-content:center;flex-direction:column; align-items:center'>
          <h3>Contact Us</h3>
          <div class="border1"></div>
          <ul>
            <a>
              <li><i class="far fa-envelope">support@codegreenback.com</i></li>
            </a>
          </ul>
        </div>

        <div class="footer-items" style='display:flex;justify-content:center;flex-direction:column; align-items:center'>
          <h3>Follow Us On</h3>
          <div class="border1"></div>
          <div class="social-media">
            <a href="https://www.instagram.com/codegreenback/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.facebook.com/codegreenback.worthyourwhile"><i class="fab fa-facebook"></i></a>
            <a href="https://twitter.com/codegreenback"><i class="fab fa-twitter"></i></a>
	    <a href="https://www.linkedin.com/company/codegreenback"><i class="fab fa-linkedin"></i></a>
	    <a href="https://www.youtube.com/channel/UCs5G0RhtruzOsW5uQokuZlA"><i class="fab fa-youtube"></i></a>
	    <a href="https://discord.gg/meHBzJ3"><i class="fab fa-discord"></i></a>

          </div>
        </div>
      </div>
    </div>

    <!--   Footer Bottom start  -->
    <div class="footer-bottom">
      Copyright &copy;CodeGreenBack 2020.
    </div>


    <!--   Footer Bottom end -->


    <!-- partial -->
   <input type="hidden" id="tokeng" value="<?php echo $tokeng?>">

   <!-- custom alert -->


<div id="customAlert" class="customModal">

  <!-- Modal content -->
  <div class="customModal-content">
    <span class="customClose">&times;</span>
    <p id="customAlertMsg"></p>
  </div>

</div>


<!-- signup login model -->
        <div class="container_login_signup" id="container_model" style="display: none;">
          <button type="button" class="close close_signup_login" style="color:black !important;left:90%;font-weight:bolder !important;opacity:0.8">X</button>

            <div class="form-container sign-up-container">
                
                <form action="#">
                  
                    <h1 style="margin-top: .5em;">Create Account</h1><br>
                    
                    <div class="form-group">
                      
                          <input id="name" type="text" placeholder="Name"> </input>
                          <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                          <i class="fa fa-check-circle" aria-hidden="true"></i>
                      
                          <small></small>
                    </div>
                    
                    <div class="form-group">
                      <input id="email" type="email" placeholder="Email" style="padding:10px"></input>
                      <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                      <small></small>
                    </div>
                    
                    <div class="form-group">
                      <input id="username" type="text" placeholder="Username" required>
                      <i class="fa fa-info-circle fa-sm genaralinfo" aria-hidden="true" data-toggle="tooltip" title="Username Must AlphaNumeric
			   i.e only Alphabets and Numbers are 
			   Allowed. Min: 5 and Max : 30 Characters"></i></input>
                      <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                      <i class="fa fa-check-circle" aria-hidden="true"></i>
                      <small></small>
                    </div>
                    
                    <div class="form-group">
                    <input id="password" type="password" placeholder="Password" required>
                    <i class="fa fa-info-circle fa-sm genaralinfo" aria-hidden="true" data-toggle="tooltip" title="Set a Strong Password 
			   Min: 8 characters , contains atleast 1 number and a special Character like @,$,#"></i></input>
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <small></small>
                    </div>
                    
                    <div class="form-group">
                    <input id="password_Again" type="password" placeholder="Re-Enter Password" ></input>
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <small></small>
                    </div>
		    <div class='form-group' style='margin:0;display:flex;flex-direction:column;position:absolute;bottom:5%;align-items:center;height:60px'>
			<div style='display:flex;height:15px;padding:5px;margin-bottom:3px;justify-content:center;align-items:center'>
			<input type='checkbox' id='accept_tnc' require style='width:20px;margin:0;'>
			<p style='font-size:12px;padding:5px;margin:0;'>By signing-up you agree to our <a href='https://www.codegreenback.com/docs/tnc/'>
			<strong>terms and conditions</strong></a> of use</p>
			</div>
			<div style='display:flex;height:15px;padding:5px;margin-bottom:3px;justify-content:center;align-items:center'>
                        <input type='checkbox' id='accept_pp' require style='width:20px;margin:0;'>
                        <p style='font-size:12px;padding:5px;margin:0;'>By signing-up you agree to our <a href='https://www.codegreenback.com/docs/tnc/'>Privacy Policy</a></p>
                        </div>

		    </div>

		   


                    <div class="loader signup-loader" style="display:none">
                      <img src="/public/img/cgbLoader.gif" alt="loader">
                    </div>

                    <button type="submit" id="signupButton">Sign Up</button>
                    <input type="hidden" id="token1" value="<?php echo $token1 ;?>">
                    <input type='hidden' id="g-token-signup" value=''>
                </form>
            </div>
            <div class="form-container sign-in-container">
              
                <form action="#">
                    <h1>Sign In</h1>
                    <!-- <div class="social-container">
                        
                        <a href="#" class="social"><i class="fa fa-google fa-2x "></i></a>
                        <a href="#" class="social"><i class="fa fa-github fa-2x " aria-hidden="true"></i></a>
                    </div>
                    <span3 style="margin-top: -0.7em;">or use your account</span3> -->
                <div class="form-group">
                    <input id="usrname" type="text" placeholder="Username" required/>
                    <small style="display: block;"></small>
                
                </div>
                <div class="form-group">
		    <input id="psw" type="password" placeholder="Password" required/>
		    <i class = "far fa-eye" id="togglePassword"></i></input>
                    <small style="display: block;"></small>
                </div>
                    <label class="checkbox" style="margin:0 auto">
                    
                    <input type="checkbox" value="remember-me" id="remember" name="rememberMe" style="margin-left: -9.5em;position: static;width:20px"><p style="font-size: 0.8em;margin-top: -3em;">Remember me</p>
                    </label>
                    <a href="https://www.codegreenback.com/accounts/forgetpassword/" style="margin-top:-1em; text-decoration-line: underline;">Forgot your password?</a>
                    <button type="submit" id="loginButton" style="margin-top: 1em;">Sign In</button>
                    <input type="hidden" id="token2" value="<?php echo $token2 ;?>">
                    <input type='hidden' id="g-token-login" value=''>
                    <div class="loader login-loader" style="display: none;">
                      <img src="/public/img/cgbLoader.gif" alt="loader">
                    </div>
                    
                    <span1 id="login_errorMsg"></span1>
                </form>
                
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <img src="/public/img/login.svg" style="margin-top: -4.5em; margin-left: 0;">
                        <div class="welcome-text" style="margin-top: -3.5em;">
                        <h4>Welcome Back!</h4>
                        Already an existing user?
                        <button class="ghost" id="signIn" >Sign In</button>
                    </div>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <img src="/public/img/signup.svg" style='margin:0;'>
                        <div style="margin-top:2em;">
                        <h1>Hello,</h1><br>
                        Enter your details and start journey with us
                        <br>
                        <button class="ghost" id="signUp" style="margin-top: 1em;">Sign Up</button>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script src="https://www.google.com/recaptcha/api.js?render=6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK"></script>

<script type="text/javascript" src="/public/js/header.js?axt=011"></script>


    <!-- signup msg -->
    <input type="hidden" name="signup-status" value="<?php echo $msg; ?>" id="signup-status">


<!-- cookie consent -->
  <style>
        /* 

#30e3ca

#11999e

#40514e

*/

        #cookie_popup {
            position: fixed;
            width: 450px;
            -webkit-box-shadow: -2px 11px 30px 4px rgba(0,0,0,0.75);
            -moz-box-shadow: -2px 11px 30px 4px rgba(0,0,0,0.75);
            box-shadow: -2px 11px 30px 4px rgba(0,0,0,0.75);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px;
            bottom: 10%;
            left: 2%;
            z-index: 1000;
	   background-color:white;
        }

        .disclaimer {
            padding: 10px;
            font-size: 22px;
        }

        #cookie_accept {
            border: none;
            padding: 5px;
            border-radius: 40%;
            background-color: #11999e;
            color: white;
            width: 70px;
            height: 40px;
            cursor: pointer;
        }

        #cookie_accept:hover {
            background-color: #13bcc2;
        }

	#cookie_accept:focus {
            border: none;
        }

        #link_to_cookie {
            text-decoration: none;
            color: rgb(0, 0, 0);
        }

        #link_to_cookie:hover {
            color: grey;
	}

	#togglePassword{
	margin-left : -30px;
	cursor : pointer;
	position : absolute;
padding-top : 20px;
	}
    </style>

 <!-- cookie accept html -->
    <div id="cookie_popup" style="display: none;">
        <div class="disclaimer">
            <p>We use cookies to personalise user experience. By continuing to visit this website you agree to our use of cookies</p>
        </div>
        <div>
            <button id="cookie_accept">Accept</button>
            <a href="https://www.codegreenback.com/docs/cookiePolicy/" id="link_to_cookie">Read our cookie policy</a>
        </div>
    </div>

<script>


const storageType = localStorage;
const consentPropertyname = "_ca";

const shouldShowPopup = () => !storageType.getItem(consentPropertyname);
const saveToStorage = () => storageType.setItem(consentPropertyname,true);

window.onload = () => {
    setTimeout(function(){
        if(shouldShowPopup()){
        $("#cookie_popup").fadeIn();
        $("#cookie_accept").click(function(){
            saveToStorage();
            $("#cookie_popup").fadeOut();

        });
    }
    },1000)
}


const togglePass = document.getElementById("togglePassword");
const pass = document.getElementById("psw");
togglePass.addEventListener("click", function(e) {
	const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
	pass.setAttribute('type', type);
	this.classList.toggle('fa-eye-slash');
});
</script>

</div>
</body>
</html>

