<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

//print_r($_SESSION);

$msg = '';

//$user = new User();
//$user->isLoggedIn()?true:Redirect::to('index');
$token = Token::jwtGenerate(Session::get('user'));                            //jwt token
//$stats = $user->statsData();

if(!Session::exists(Config::get('session/session_name'))){Redirect::to('');exit();}

if(Session::exists('prompt')){

$msg =  Session::flash('prompt');}
include_once 'after_login_header.php';

?>



<!-- frontend   starts   -->

<input type="hidden" id="sid" value="<?php echo $token;?>">

<!--<link rel="stylesheet" type="text/css" href="public/css/dashboard.css?id=001"> -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700;900&display=swap" rel="stylesheet"></head>

<script  type="text/javascript" src="https://www.codegreenback.com/assets/plugins/socketjs/004/"></script>

<!-- Favicons -->
  <link href="https://www.codegreenback.com/public/001/img/gcb.png" rel="icon">
  

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->


  <!-- Libraries CSS Files -->
  <link href="https://www.codegreenback.com/public/001/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://www.codegreenback.com/public/001/lib/animate/animate.min.css" rel="stylesheet">
  <link href="https://www.codegreenback.com/public/001/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
 

  <!-- Main Stylesheet File -->
  <link href="https://www.codegreenback.com/public/001/css/style.css?id=006" rel="stylesheet">
  <link href="https://www.codegreenback.com/public/001/css/style1.css?id=001" rel="stylesheet">

  <body>


  <!--==========================
    Intro Section
  ============================-->
  <section id="intro">
    <div class="row int" style="display: flex; ">
       <div class="col-lg-6 col-12 text-center">
        <p class="info"><h1> WELCOME</h1></p> 
       	 <p style="font-size: 1.5vw;"> <?php echo $_SESSION['user']; ?></p>
	<input type='hidden' id='username_client' value='<?php  echo $_SESSION['user']; ?>'>
        <div class="arrow-container animated fadeInDown">
          <a class="arrow-2" href='#self-analysis'>
            <i class="fa fa-angle-down"></i>
          </a>
          <div class="arrow-1 animated hinge infinite zoomIn"></div>
        </div>
        </div>
        <div class="col-lg-6 col-12 slidee">
          <div class="sliderr">
  
            <a href="#sliderr-1">1</a>
            <a href="#sliderr-2">2</a>
            <a href="#sliderr-3">3</a>
            <a href="#sliderr-4">4</a>
            
          
            <div class="slidesr">
	      <div id="sliderr-1">
		<a href="https://www.codegreenback.com/events/attemptEvent.php?eid=5f54fdbb1c549">
	       <img class="img11"src="https://www.codegreenback.com/public/001/img/10.png">
		</a>
              </div>
              <div id="sliderr-2">
                <img class="img11"src="https://www.codegreenback.com/public/img/001/faceoff.png">
              </div>
              <div id="sliderr-3">
                <img class="img11"src="https://www.codegreenback.com/public/img/001/2.jpg">
              </div>
              <div id="sliderr-4">
                <img class="img11"src="https://www.codegreenback.com/public/img/001/fn.jpg">
              </div>
             
            </div>
          </div>
        </div>
     
      </div>
  </section>


  <!-- partial -->

  <div class="feat bg-gray pt-5 pb-5">

    <div class="container">

      <div class="row">

        <div class="section-header col-sm-12" style='text-align:center'>

          <h3><span>Why</span> CodeGreenBack?</h3>

          <!-- <p>Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's<br>standard dummy text ever since the 1500s, when an unknown book.</p> -->

        </div>
	
        <div class="col-lg-4 col-sm-6">
	<a href='https://www.codegreenback.com/Compete'>
          <div class="item"> <span class="icon feature_box_col_one"><i class="fa fa-clock f"></i></span>

            <h6 class="heads">Real time challenge</class=>

            </h6>

            <p>Challenge real-time with your friends anytime

            </p>

          </div>
	</a>
        </div>
        <div class="col-lg-4 col-sm-6">
	<a href='https://www.codegreenback.com/accounts/redeem/'>
          <div class="item"> <span class="icon feature_box_col_two"><i class="fa fa-rupee f"></i></span>

            <h6 class="heads">Earn Real money with Code</h6>

            <p>

              Earn money on winning the challange , yes real money

            </p>

          </div>
	</a>
        </div>

        <div class="col-lg-4 col-sm-6">
	 <a href='https://www.codegreenback.com/Compete'>

          <div class="item"> <span class="icon feature_box_col_three"><i class="fa fa-users f"></i></span>

            <h6 class="heads">Compete with your friends</h6>

            <p>Compete with your friends here by challenging them

            </p>

          </div>
	</a>
        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_four"><i class="fa fa-book f"></i></span>

            <h6 class="heads">Learn</h6>

            <p>Learn while you code , lots of learning resources are available

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_five"> <i class="fas fa-laptop-code f"></i></span>

            <h6 class="heads">Practice</h6>

            <p>Practice as much as you can on different  topics.

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_six"><i class="fa fa-bolt f"></i></span>

            <h6 class="heads">1 vs1 challanges</h6>

            <p>Our unique speciality is 1vs 1 challenge with your friend, real time

            </p>

          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- --------------------------carouseaul--------------------->



  <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">

    <div class="carousel-inner">

      <div class="carousel-item active">

        <div class="mask flex-center">

          <div class="container">

            <div class="row align-items-center">

              <div class="col-md-7 col-12 order-md-1 order-2">

                <h4>Compete with your best opponent</h4>
                <p>Top 25% winners will be awarded with elite membership or 50 CodeCoins.</p>

                <a href="https://www.codegreenback.com/events/attemptEvent.php?eid=5f54fdbb1c549">Start Now</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="https://www.codegreenback.com/public/001/public_img/rounded corners.png" style="padding-top: 2em;"

                  class="mx-auto" alt="slide"></div>

            </div>

          </div>

        </div>

      </div>

      <div class="carousel-item">

        <div class="mask flex-center">

          <div class="container">

            <div class="row align-items-center">

              <div class="col-md-7 col-12 order-md-1 order-2">

                <h4>Get Real time Experience</h4>


                <p>The opponent must be online for Face/Off</p>

                <a href="https://www.codegreenback.com/Compete">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="https://www.codegreenback.com/public/img/001/faceoff.png" class="mx-auto"

                  alt="slide"></div>

            </div>

          </div>

        </div>

      </div>

      <div class="carousel-item">

        <div class="mask flex-center">

          <div class="container">

            <div class="row align-items-center">

              <div class="col-md-7 col-12 order-md-1 order-2">

                <h4>Challenge opponent anytime</h4>

                <p>Turnyourturn is the offline version of Face/Off</p>

                <a href="https://www.codegreenback.com/Compete">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="https://www.codegreenback.com/public/img/001/fn.jpg" class="mx-auto"

                  alt="slide"></div>

            </div>

          </div>

        </div>

      </div>

      <div class="carousel-item">

        <div class="mask flex-center">

          <div class="container">

            <div class="row align-items-center">

              <div class="col-md-7 col-12 order-md-1 order-2">

                <h4>Rush in 30 minutes</h4>

                <p>Train yourself to compete in time</p>

                <a href="https://www.codegreenback.com/Compete">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="https://www.codegreenback.com/public/img/001/2.jpg" class="mx-auto"

                  alt="slide"></div>

            </div>

          </div>

        </div>

      </div>

    </div>

    <script>

      ('#myCarousel').carousel({

        interval: 500,

      })

    </script>

    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev"> <span

        class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a

      class="carousel-control-next" href="#myCarousel" role="button" data-slide="next"> <span

        class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>

  </div>

  <!--slide end-->


    <!--==========================
      Overview Section
    ============================-->
    <section id="services" class="section-bg">
      <div class="container">

        <header class="section-header">
          <h3>Overview</h3>
        </header>

        <div class="row">

          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #fceef3;"><i class="ion-ios-paper-outline" style="color: #0a0a0a;"></i></div>
              <h4 class="title"><a>TURN YOUR TURN</a></h4>
              <p class="description">Challenge with a single question available for 24 hours,the one who finishes the question in less time wins !!</p>
              <br>
              <div class="col-lg-12 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="https://www.codegreenback.com/Compete/">CHALLENGE</a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #fff0da;"><i class="ion-ios-paper-outline" style="color: #5a5a5a;"></i></div>
              <h4 class="title"><a>RUSHOUR</a></h4>
              <p class="description">Challenge Your Friend and solve all the problems in a time limit of<br> 30 minutes to win !!</p>
              <br>
              <div class="col-lg-12 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="https://www.codegreenback.com/Compete/">CHALLENGE</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #e6fdfc;"><i class="ion-ios-paper-outline" style="color: #3fcdc7;"></i></div>
              <h4 class="title"><a>FACE/OFF</a></h4>
              <p class="description">Challenge to enjoy the experience of realtime face/off. The users who completes the question first wins.<br><b> The Opponent must be online.</b></p>
              <div class="col-lg-12 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="https://www.codegreenback.com/Compete/">CHALLENGE</a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #c8fcf5;"><i class="ion-ios-speedometer-outline" style="color:#3c4e45;"></i></div>
              <h4 class="title"><a>Try{code} We will catch</a></h4>
              <p class="description text-center">12-09-2020 &nbsp;&nbsp;&nbsp;&nbsp; 07:00PM</p>
              <p style="font-weight: bold;">REGISTRATION FEE: FREE</p>
              <div class="col-lg-12 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="https://www.codegreenback.com/events/attemptEvent.php?eid=5f54fdbb1c549">Attempt</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #e1eeff;"><i class="ion-ios-world-outline" style="color: #2282ff;"></i></div>
              <h4 class="title"><a>Compile & Practice</a></h4>
              <p class="description">Online IDE is available to clear your doubts anytime in any programming language.</p>
              <div class="col-lg-12 cta-btn-container text-center">
                <a class="cta-btn align-middle" href="http://quespost.codegreenback.in/coderunner.php" target="_blank">OPEN IDE</a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s" >
            <div class="box">
              <div class="icon" style="background: #ecebff;"><i class="ion-ios-clock-outline" style="color: #8660fe;"></i></div>
              <h4 class="title"><a href="">Global Online Users</a></h4>
              <div class="row scrollbar onlineUsers" style="overflow-y:scroll; height: 8em; ">
                <!-- <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044"></i>&nbsp;mansi123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044"></i>&nbsp; ayu123</div>
                <div class="col-lg-6 col-md-12 "><i class="fa fa-wifi" aria-hidden="true" style="color:#002044;"></i>&nbsp;ayu123</div>
                <div class="col-lg-6 col-md-12 "><i class="fa fa-wifi" aria-hidden="true" style="color:#002044;"></i>&nbsp;ayu123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp; rid123</div>
                <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044;"></i>&nbsp;amit123</div> -->
              </div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- #services -->

<!-- recommended users -->
    <section style="width: 100%;background-color:#11999e;display:flex;justify-content:center;align-items:center;padding:20px" id='rec_section'>
    <div style="padding:10px;color:white">
      <h1>Recommended Users</h1>
      <h3>Based on your current league</h3>
    </div>

    

     <!-- .................................Recommended User Div. Dummy and Format for each card ............................ -->

      <div style="display: flex; position:relative; height:200px; width:50vw; margin-left:0 auto">
        
        
          <i class="fa fa-angle-left" id="left-button"></i>
          
        <div id="recommended-Users-div">

         
      </div>
    
        <i class="fa fa-angle-right" id="right-button"></i>
      

      </div>

      </section>


<!-- ====================================== -->
    <!--Self Analysis-->
 <!-- =========================================    -->
 <br>
 <br>
    <header class="section-header">
      <h3 id='self-analysis'>Self Analysis</h3>
    </header>
    <div class="container">
      <div class="row counters">

        <div class="col-lg-4 col-4 text-center sa text-uppercase">
          <span data-toggle="counter-up"><?php echo $_SESSION['league']; ?></span>
          <p><b>League</b></p>
        </div>

        <div class="col-lg-4 col-4 text-center sa">
          <span data-toggle="counter-up"><?php echo $_SESSION['cc']; ?></span>
          <p><b>CodeCoins</b></p>
        </div>

        <div class="col-lg-4 col-4 text-center sa text-uppercase">
          <span data-toggle="counter-up"><?php echo $_SESSION['user_type'];  ?></span>
          <p><b>Member Type</b></p>
        </div>
      </div>
    </div>

    <!--==========================
      Prepare Section
    ============================-->
   

    
    <!--==========================
      Search Section
    ============================-->
    <section id="call-to-action" class="wow fadeInUp" style="width: 100%;">
      <div class="container">
        <div class="row">
          <div class="col-lg-9 text-center text-lg-left">
            <h3 class="cta-title">Wanna challenge your friends? </h3>
            <p class="cta-text"> Search your friends across the globe, compete them in the challenges and win code coins.</p>
          </div>
          <div class="col-lg-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="https://www.codegreenback.com/Compete/">Search Friends</a>
          </div>
        </div>

      </div>
    </section><!-- #call-to-action -->


    
      <!-- Frequently Asked Questions Section -->
   
    <section id="faq" style="width: 100%;">
      <div class="container">
        <header class="section-header">
          <h3>Frequently Asked Questions</h3>
          
        </header>

        <ul id="faq-list" class="wow fadeInUp">
<li>
            <a data-toggle="collapse" class="collapsed" href="#faq1"> What is the use of codecoins ? Can it be converted to real money ? <i class="ion-android-remove"></i></a>
            <div id="faq1" class="collapse" data-parent="#faq-list">
              <p>
                You can use codecoins to register in events and challenges(TurnYourTurn, Rushour, Face/Off) where you can win more codecoins and later you can redeem them into actual money directly to your bank account. <span><a href="https://www.codegreenback.com/docs/codecoins.html">Learn more</a></span>
              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq2" class="collapsed">What is Elite membership ? Why should I become an elite member ? <i class="ion-android-remove"></i></a>
            <div id="faq2" class="collapse" data-parent="#faq-list">
              <p>
                Become an Elite Member of codegreenback to unlock interesting features for lifetime.
                    Elite member have greater privileges over non-elite members in the community.<span><a href="https://www.codegreenback.com/docs/elite.html">Learn more</a></span>

              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq3" class="collapsed">How can I earn money on CodeGreenBack ? <i class="ion-android-remove"></i></a>
            <div id="faq3" class="collapse" data-parent="#faq-list">
              <p>
                Challenge other coders in the community by using codecoins. Withdraw the codecoins and convert them to money and transfer directly into your bank account.
                Note: You have to provide a bank account number and IFSC code to tranfer money.
              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq4" class="collapsed">How to withdraw my codecoins ?<i class="ion-android-remove"></i></a>
            <div id="faq4" class="collapse" data-parent="#faq-list">
              <p>
                Request codecoin withdrawal by providing your bank account number and IFSC code in the  <a href="https://www.codegreenback.com/accounts/redeem" style="color:black; font-style:italic;display:inline;padding:0">account</a> section . 
                  Note : Only Elite members can request codecoins withdrawal.
              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq5" class="collapsed">What is 1 vs 1 challenge ?<i class="ion-android-remove"></i></a>
            <div id="faq5" class="collapse" data-parent="#faq-list">
              <p>
              CodeGreenBack provides a platform where you can challenge your friends and other users in the community and earn codecoins.
              You can challenge them in one of the three 1v1 challenges 'Face/Off', 'TurnYourTurn' and 'Rushour'. 
              <span><a href="https://www.codegreenback.com/docs/challenges/faceoff.html">Learn more</a></span>
              </p>
            </div>
          </li>
        </ul>

      </div>
    </section><!-- #faq -->

  </main>

      
 
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
<!-- 
<script src="CGBHomePage/lib/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<script src="https://www.codegreenback.com/public/001/lib/jquery/jquery-migrate.min.js"></script>
<!-- <script src="https://www.codegreenback.com/public/001/js/main.js?id=001" type="text/javascript"></script> -->



<!-- The Modal -->
<div id="c3-model" class="c3-modal">

  <!-- Modal content -->
  <div class="c3-modal-content">

    <div class='c3-header'>
      <h3>Challenge Request !</h3>
    </div>

    <div class='c3-body'>
      <p id="c3-body-msg">ayush123 has challenged you in Face/Off </p> 
      <span class='c3-cc-img'><img src="https://www.codegreenback.com/public/img/cclogo.png" alt="cc-log" ></span>
      <span id="c3-cc-bet"></span>
    </div>

    <div class='c3-timer-div'>
      <p id="c3-timer">10</p>
    </div>

    <div class='c3-buttons-div'>
      <div class='c3-accept-button-div'>
        <button id="c3-accept-but">Accept</button>
      </div>
      <div class='c3-reject-button-div'>
        <button id="c3-reject-but">Reject</button>
      </div>
    </div>
    
  </div>

</div>


<!-- ..................loader................ -->
<div class="overlay-loader-c3" style="display: none;">
    <div class="overlay__inner-loader">
        <div class="overlay__content-loader">
          <div>
            <span class="spinner-loader"></span>
          </div>
          
          <div>
            <p class='process-msg-c3'></p>
          </div>
        </div>
    </div>
</div>

<script>
  localStorage.setItem('sid',$("#sid").val());
</script>

      
<div style="width: 100%;">
 <?php require_once 'footer.php';  ?>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
	<a href="/accounts/pay/buycc.php">
      <img src="/public/001/img/elitePop.jpeg" height="100%" width="100%">
</a>
  </div>
</div>
<script>

$(document).ready(function(){

   $("#right-button").click(function () {
               event.preventDefault();
               $("#recommended-Users-div").animate(
                 {
                   scrollLeft: "+=500px",
                 },
                 "slow"
               );
             });

             $("#left-button").click(function () {
               event.preventDefault();
               $("#recommended-Users-div").animate(
                 {
                   scrollLeft: "-=500px",
                 },
                 "slow"
               );
             });
    // recomended users

  
  function getRecommendedUser() {
    $.ajax({
      url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
      type: "get",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      data: {
        service: "_rec",
      },
      dataType: "json",
      success: function (data) {
        // console.log(data);
	if(data.length == 0){$("#rec_section").hide()}
        makeRecommendedUserDiv(data);
      },
    });
  }

  function makeRecommendedUserDiv(data) {
    let len = data.length;
    let txt = ``;
//	console.log(data);
    if(len == 0)
    {
      $("#rec_section").hide();
	return 0;
    }
    for (let i = 0; i < len; i++) {
      if (data[i].username != $("#username_client").val()) {
        txt += `
        <div class="recommended-user-card">
          <a href='https://www.codegreenback.com/user/${data[i].username}/'>
              <div class="recommended-user-img">
                <div>
                  <img src="https://www.codegreenback.com/${data[i].img}" style="height: 80px; width:80px; border-radius:50%; margin:0 auto;" alt="">
                </div>
              </div>

              <div class="recommended-user-info">
                <div  class="recommended-user-username">
                  <p style="font-size: 1.2vw;">${data[i].username}</p>
                </div>
                <div  class="recommended-user-name">
                  <p style="font-size: 1vw;">${data[i].name}</p>
                </div>

              </div>
          </a>
          </div>`;
      }
    }
    $("#recommended-Users-div").html(txt);
  }

  getRecommendedUser();
});


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
      begin = dc.indexOf(prefix);
      if (begin != 0) return null;
  }
  else
  {
      begin += 2;
      var end = document.cookie.indexOf(";", begin);
      if (end == -1) {
      end = dc.length;
      }
  }
  // because unescape has been deprecated, replaced with decodeURI
  //return unescape(dc.substring(begin + prefix.length, end));
  return decodeURI(dc.substring(begin + prefix.length, end));
} 

function displayElite(){
  cookie = getCookie("popUp");
  if(cookie == null){
    setCookie("popUp", "popUp", 1)
    var modal = document.getElementById("myModal");
      modal.style.display = "block";

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  }
}
function elite (){
  displayElite();
  setInterval(displayElite, 10000);
}
$(document).ready(function(){
	if(document.getElementById("user_type").value == "non-elite"){
		elite();
}

});








</script>


</body>
</html>
