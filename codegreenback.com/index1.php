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





  <meta content="width=device-width, initial-scale=1.0" name="viewport">



<!-- frontend   starts   -->



<input type="hidden" id="sid" value="<?php echo $token;?>">



<!--<link rel="stylesheet" type="text/css" href="public/css/dashboard.css?id=001"> -->

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700;900&display=swap" rel="stylesheet"></head>



<script  type="text/javascript" src="https://www.codegreenback.com/assets/plugins/socketjs/004/"></script>







  <!-- Google Fonts -->

  <link

    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700"

    rel="stylesheet">



 


  <!-- Libraries CSS Files -->

  <link href="https://www.codegreenback.com/public/001/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <link href="https://www.codegreenback.com/public/001/lib/animate/animate.min.css" rel="stylesheet">

  <link href="https://www.codegreenback.com/public/001/lib/ionicons/css/ionicons.min.css" rel="stylesheet">





  <!-- Main Stylesheet File -->

  <link href="https://www.codegreenback.com/public/001/css/home.css?id=001" rel="stylesheet">

  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css'>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"

    integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="

    crossorigin="anonymous" />



</head>



<body>





  <!--==========================

    Intro Section

  ============================-->



  <!--<div class="row int" style="display: flex; margin-bottom: -9em; ">

       <div class="col-lg-2 col-11 text-center">

        <p class="info"><h1> WELCOME</h1></p>
        <div class="arrow-container animated fadeInDown">

          <div class="arrow-2">

            <i class="fa fa-angle-down"></i>

          </div>

          <div class="arrow-1 animated hinge infinite zoomIn"></div>

        </div>

        </div>-->

  <!---- <div class="col-lg-6 col-12 slidee">

          <div class="sliderr">



            <a href="#sliderr-1">1</a>

            <a href="#sliderr-2">2</a>

            <a href="#sliderr-3">3</a>

            <a href="#sliderr-4">4</a>





            <div class="slidesr">

              <div id="sliderr-1">

               <img class="img11"src="img/12.png">

              </div>

              <div id="sliderr-2">

                2

              </div>

              <div id="sliderr-3">

                3

              </div>

              <div id="sliderr-4">

                4

              </div>



            </div>

          </div>

        </div>-->



  <!-- </div> -->







  <!----  <svg width="502" height="223" viewBox="0 0 502 223" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom:17.5em">

  <path d="M502 -22.9621L296.534 185.047L424.139 -22.9621H502Z" fill="#577590" fill-opacity="0.5"/>

  <path d="M6.81807 128.684C92.3773 199.857 279.56 299.499 343.817 128.684H6.81807Z" fill="#577590" fill-opacity="0.5"/>

  <path d="M277.669 187.927C187.975 223.313 -92.1493 117.342 -221 59.9336V-85H435.804C420.464 -8.76869 367.362 152.541 277.669 187.927Z" fill="#577590"/>

  </svg>

  <svg width="1528" height="258" viewBox="0 0 1528 258" fill="none" xmlns="http://www.w3.org/2000/svg">

  <path d="M1528 132.545C1409.48 137.808 1340.81 205.999 1321.28 239.436L1528 209.836V132.545Z" fill="#577590" fill-opacity="0.5"/>

  <path d="M559.579 209.033C448.605 78.3984 65.6205 57.2703 -112 63.0356C-84.2565 118.789 374.4 233.451 600.26 283.812V261.938L559.579 209.033Z" fill="#577590" fill-opacity="0.5"/>

  <path d="M798.602 242.593C525.266 243.473 144.18 81.2309 -12.1963 0V257.252H1528V164.538C1398.76 190.19 1071.94 241.714 798.602 242.593Z" fill="#577590"/>

  </svg>-->

  <!--<section class="intro1">

    <h1 class="intro1__title">

      Learn English

    </h1>

    <p class="intro1__subtitle">

      Visiting places in the world, getting a new job.

    </p>

    <a href="#" class="button2">Get Started</a>-->

  <section>

    <div class="cover" style="background-image: linear-gradient(to left, rgba(255, 255, 255, 0), rgb(188, 255, 244));">

      <img class="welcomesvg" src="public/001/public_img/final3.svg" style="margin-left:34.71%;">

      <img class="cornersvg" src="public/001/public_img/Vector (1).svg" style="margin-top: -32%;">

      <!-- <div class="supertext"> -->

       

      <div class="row">

      <div id=containerr class="col-6">

        <div class="txt">Hey! <?php echo $_SESSION['user']; ?>,</div>

        

        <div class="txt1 " id="text1">Are you ready for the thrilling coding journey?</div>

        

        <div class="dynamictxt">

          <!-- <div>YOU CAN</div> 

        <div id=flip>

          <div><div>WIN REAL MONEY</div></div>

          <div><div>COMPETE</div></div>

          <div><div>LEARN</div></div>

        </div>

        <div>HERE!</div> -->

        <div class="row" id="btns">

        <div class="col-sm-4 fig">

        <figure id="fig1">

          <div id="ib1">

            <span class="b1"></span>

            <span class="b1">Practice</span>

          </div>

        </div>

        </figure>

        <div class="col-sm-4">

        <figure id="fig2">

          <div id="ib2">

            <span class="b1"></span>

            <span class="b1">Watch</span>

          </div>

        </figure>

      </div>

    </div>

  </div>

      </div>

      </div>

      <!-- <br><br>

      <br><br> -->





      </div>

    

   </section>

  <!-- partial -->

  <div class="feat bg-gray pt-5 pb-5">

    <div class="container">

      <div class="row">

        <div class="section-head col-sm-12">

          <h4><span>Why</span> CodeGreenBack?</h4>

          <!-- <p>Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's<br>standard dummy text ever since the 1500s, when an unknown book.</p> -->

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_one"><i class="fa fa-clock f"></i></span>

            <h6 class="heads">Real time challenge</class=>

            </h6>

            <p>Challange real-time with your friends anytime

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_two"><i class="fa fa-rupee f"></i></span>

            <h6 class="heads">Earn Real money with Code</h6>

            <p>

              Earn money on winning the challange , yes real money

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_three"><i class="fa fa-users f"></i></span>

            <h6 class="heads">Compete with your friends</h6>

            <p>Compete with your friends here by challanging them

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_four"><i class="fa fa-book f"></i></span>

            <h6 class="heads">Learn</h6>

            <p>Learn while you code , lots of learning resources are available

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_five"> <i class="fa fa-laptop-code"></i></span>

            <h6 class="heads">Practice</h6>

            <p>Practice as much as you can on different  topics.

            </p>

          </div>

        </div>

        <div class="col-lg-4 col-sm-6">

          <div class="item"> <span class="icon feature_box_col_six"><i class="fa fa-bolt"></i></span>

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

                <h4>Lorem ipsum <br>

                  sit amet</h4>

                <p>Lorem ipsum dolor sit amet. Reprehenderit, qui blanditiis quidem rerum <br>

                  necessitatibus praesentium voluptatum deleniti atque corrupti.</p>

                <a href="#">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="public/001/public_img/rounded corners.png" style="padding-top: 2em;"

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

                <h4>Lorem ipsum <br>

                  sit amet</h4>

                <p>Lorem ipsum dolor sit amet. Reprehenderit, qui blanditiis quidem rerum <br>

                  necessitatibus praesentium voluptatum deleniti atque corrupti.</p>

                <a href="#">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="public/001/public_img/rounded corners.png" class="mx-auto"

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

                <h4>Lorem ipsum <br>

                  sit amet</h4>

                <p>Lorem ipsum dolor sit amet. Reprehenderit, qui blanditiis quidem rerum <br>

                  necessitatibus praesentium voluptatum deleniti atque corrupti.</p>

                <a href="#">GET STARTED</a>

              </div>

              <div class="col-md-5 col-12 order-md-2 order-1"><img src="rounded corners.png" class="mx-auto"

                  alt="slide"></div>

            </div>

          </div>

        </div>

      </div>

    </div>

    <script>

      ('#myCarousel').carousel({

        interval: 2000,

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

        <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus.</p>

      </header>



      <div class="row">



        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178);;"><i class="ion-ios-paper-outline" style="color: #0a0a0a;"></i>

            </div>

            <h4 class="title"><a href="">TURN YOUR TURN</a></h4>

            <p class="description">Challenge with a single question available for 24 hours,the one who finishes the

              question in less time wins !!</p>

            <br>

            <div class="col-lg-12 cta-btn-container text-center">

              <a class="cta-btn align-middle" href="https://www.codegreenback.com/Compete">CHALLENGE</a>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178);;"><i class="ion-ios-paper-outline" style="color: #5a5a5a;"></i>

            </div>

            <h4 class="title"><a href="">RUSHOUR</a></h4>

            <p class="description">Challenge Your Friend and solve all the problems in a time limit of<br> 30 minutes to

              win !!</p>

            <br>

            <div class="col-lg-12 cta-btn-container text-center">

              <a class="cta-btn align-middle" href="#">CHALLENGE</a>

            </div>

          </div>

        </div>



        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178)"><i class="ion-ios-paper-outline" style="color: #3fcdc7;"></i>

            </div>

            <h4 class="title"><a href="">FACE/OFF</a></h4>

            <p class="description">Challenge to enjoy the experience of realtime face/off. The users who completes the

              question first wins.<br><b> The Opponent must be online.</b></p>

            <div class="col-lg-12 cta-btn-container text-center">

              <a class="cta-btn align-middle" href="#">CHALLENGE</a>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178);"><i class="ion-ios-speedometer-outline"

                style="color:#3c4e45;"></i></div>

            <h4 class="title"><a href="">Try{code} We will catch</a></h4>

            <p class="description text-center">20-07-2020 &nbsp;&nbsp;&nbsp;&nbsp; 05:00PM</p>

            <p style="font-weight: bold;">REGISTRATION FEE: FREE</p>

            <div class="col-lg-12 cta-btn-container text-center">

              <a class="cta-btn align-middle" href="#">REGISTER</a>

            </div>

          </div>

        </div>



        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178);;"><i class="ion-ios-world-outline" style="color: #2282ff;"></i>

            </div>

            <h4 class="title"><a href="">Compile & Practice</a></h4>

            <p class="description">Online IDE is available to clear your doubts anytime in any programming language.</p>

            <div class="col-lg-12 cta-btn-container text-center">

              <a class="cta-btn align-middle" href="#">OPEN IDE</a>

            </div>

          </div>

        </div>

        <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">

          <div class="box">

            <div class="icon" style="background: rgba(23, 246, 209, 0.178);;"><i class="ion-ios-clock-outline" style="color: #8660fe;"></i>

            </div>

            <h4 class="title"><a href="">Global Online Users</a></h4>

            <div class="row scrollbar onlineUsers" style="overflow-y:scroll; height: 8em; ">

              <!-- <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044"></i>&nbsp;mansi123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044"></i>&nbsp;

                ayu123</div>

              <div class="col-lg-6 col-md-12 "><i class="fa fa-wifi" aria-hidden="true"

                  style="color:#002044;"></i>&nbsp;ayu123</div>

              <div class="col-lg-6 col-md-12 "><i class="fa fa-wifi" aria-hidden="true"

                  style="color:#002044;"></i>&nbsp;ayu123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp; rid123</div>

              <div class="col-lg-6 col-md-12"><i class="fa fa-wifi" aria-hidden="true"

                  style="color: #002044;"></i>&nbsp;amit123</div> -->

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

    <h3>Self Analysis</h3>

  </header>

  <div class="container">

    <div class="row counters">



      <div class="col-lg-4 col-4 text-center sa">

        <span data-toggle="counter-up"><?php echo $_SESSION['league']; ?></span>

        <p><b>League</b></p>

      </div>



      <div class="col-lg-4 col-4 text-center sa">

        <span data-toggle="counter-up"><?php echo $_SESSION['cc']; ?></span>

        <p><b>CodeCoins</b></p>

      </div>



      <div class="col-lg-4 col-4 text-center sa">

        <span data-toggle="counter-up"><?php echo $_SESSION['user_type']; ?></span>

        <p><b>Member Type</b></p>

      </div>



      <!--==========================

      Prepare Section

    ============================-->

      <section id="why-us" class="wow fadeIn">

        <div class="container-fluid">



          <!-- <header class=" container section-header">

          <h3>Prepare Yourself</h3>

        </header>



        <div class="row">

          <div class="col-lg-12 col-md-6 text-center">

          <h1 class="ml14">

            <span class="text-wrapper">

            <span class="letters" style="text-align: center;"> LANGUAGE PROFICIENCY KIT</span>

            <span class="line"></span>

            </span>

          </h1>

          </div>





            <div class="row" style="width:100%;display: flex; justify-content: space-between; padding: 0;" >

              <div class="progresscard1 col-lg-6 col-12">



                <div class="c"> <img class="titleimg" src="img/python1.png">

                  <div class="row">

                  <div class="BoxTitle col-lg-12 col-12">PYTHON PROFICIENCY KIT</div>

                  <div class="progress col-lg-7 col-8" >

                      <div class="actualProgress" style="width:40%;">

                        <center>40%</center>

                      </div>

                  </div>

                </div>

                </div>

              </div>

              <div class="progresscard2 col-lg-6 col-12">

                <a href="/FetchQuestion.php?type=java">

                <div class="java"><img class="titleimg" src="img/c++new.png">

                  <div class="row">

                  <div class="BoxTitle col-lg-12 col-12">CPP PROFICIENCY KIT</div>

                  <div class="progress col-lg-7 col-8 ">

                      <div class="actualProgress" style="width:10%;">

                        <center>10%</center>

                      </div>

                  </div>

                  </div>



                </div>

              <!-- </a> -->

          <!-- </div>

            </div>





          <div class="row" style="width:100%;display: flex; justify-content: space-between; padding: 0;" >

            <div class="progresscard3 col-lg-6 col-12">

              <!-- <a href="/FetchQuestion.php?type=c"> -->

          <!-- <div class="c"><img class="titleimg" src="img/cnew.png">

                <div class="row">

                <div class="BoxTitle col-lg-12 col-12 ">C PROFICIENCY KIT</div>

                <div class="progress col-lg-7 col-8">

                    <div class="actualProgress" style="width:40%;">



                      <center>15%</center>

                    </div>

                  </div> -->

          <!--<div class="medals">

                    <img src="../data/medal4.png" class="medal">

                  </div>-->

          <!-- </div>

            </div> -->

          <!-- </a> -->

          <!-- </div>

            <div class="progresscard4 col-lg-6 col-12"> -->

          <!-- <a href="/FetchQuestion.php?type=java"> -->

          <!-- <div class="java"><img class="titleimg" src="img/java1.png">

                <div class="BoxTitle col-lg-12 col-12 ">JAVA PROFICIENCY KIT</div>

                 <div class="row">

                <div class="progress col-lg-7 col-8">

                    <div class="actualProgress" style="width:75%;">

                      <center>75%</center>

                    </div>

                  </div>

                  <div class="medals">

                    <div class="medaltext"></div>

                  </div>

              </div> -->

          <!-- </a> -->

          <!-- </div>

          </div>

          </div>

          <br>

          <br>

          <br> -->



          <!-- <div class="row" style="justify-content: center;margin-top: 3em;">

              <h1 class="ml14 col-lg-12 col-12">

                <span class="text-wrapper">

                  <span class="letters" style="text-align: center;"> INTERVIEW PREPARATION KIT</span>

                  <span class="line"></span>

                </span>

                </h1>

              <div class="container12 row" style="margin-top: 1em;">

                <div class="card col-lg-3 col-md-12 col-sm-12  col-xs-12">

                    <div class="box">

                        <div class="percent">

                            <svg class="svg-one">

                                <circle cx="70" cy="70" r="70"></circle>

                                <circle cx="70" cy="70" r="70"></circle>

                             </svg >

                             <div class="number">

                                 <h2>90%</h2>

                             </div>

                        </div>

                        <h2 class="text">Microsoft kit</h2>

                    </div>

                </div>

                <div class="card col-lg-3 col-md-12 col-sm-12  col-xs-12">

                    <div class="box">

                        <div class="percent">

                            <svg class="svg-one">

                                <circle cx="70" cy="70" r="70"></circle>

                                <circle cx="70" cy="70" r="70"></circle>

                             </svg>

                             <div class="number">

                                 <h2>45%<h2>

                             </div>

                        </div>

                        <h2 class="text">Google kit</h2>

                    </div>

                </div>

                <div class="card col-lg-3 col-md-12 col-sm-12  col-xs-12">

                  <div class="box">

                      <div class="percent">

                          <svg class="svg-one">

                              <circle cx="70" cy="70" r="70"></circle>

                              <circle cx="70" cy="70" r="70"></circle>

                           </svg>

                           <div class="number">

                               <h2>20%</h2>

                           </div>

                      </div>

                      <h2 class="text">Amazon kit</h2>

                  </div>

              </div>



            </div> -->





        </div>

    </div>

    <br>

    <br>

    <br>

    <br>

    <!--Dynamic nav bar -->

    <!-- <div class="row">

          <h1 class="ml14 col-lg-12 col-12">

            <span class="text-wrapper">

              <span class="letters" style="text-align: center;"> COMPETITIVE PACKAGE</span>

              <span class="line"></span>

            </span>

          </h1>

        </div> -->

    <!-- <br>

          <br>

          <div class="row" style="justify-content: center;">

            <div style="display: flex; justify-content: center;">

            <div id="progressbar" class="progress11" style="--progress11: var(--mouse-x, 10)" >

              <div class="wrapper1">

                <div class="tooltips-container">

                  <div class="tooltips">

                    <span class="number3" id="number3" data-number="10%"></span>

                    Progress

                    <div class="tooltips-indicator"></div>

                  </div>



                </div>

                <div class="bar">



                </div>



              </div>

            </div>

            </div>

          </div> -->

    <!-- 

        </div>



      </div>

      <br>

      <br> -->

    <!-- <div style="display: flex; justify-content: center;">

      <button class="btn btn--beta"><span>Package</span></button>

      </div> -->

    <!-- <br>

      <br> -->











  </div>



  </div>

  </section>





  <!--==========================

      Search Section

    ============================-->

  <section id="call-to-action" class="wow fadeInUp">

    <div class="container">

      <div class="row">

        <div class="col-lg-9 text-center text-lg-left">

          <h3 class="cta-title">Wanna challenge your friends? </h3>

          <p class="cta-text"> Search your friends across the globe, compete them in the challenges and win code coins.

          </p>

        </div>

        <div class="col-lg-3 cta-btn-container text-center">

          <a class="cta-btn align-middle" href="#">Search Friends</a>

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


  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- Uncomment below i you want to use a preloader -->

  <!-- <div id="preloader"></div> -->



  <!-- Template Main Javascript File -->

  <script src="https://www.codegreenback.com/public/001/js/main.js"></script>

<script>
  localStorage.setItem('sid',$("#sid").val());
</script>



<div style="width: 100%;">
 <?php require_once 'footer.php';  ?>
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

</script>


</body>



</html>
