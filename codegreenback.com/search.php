<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
$user = new User();
$user->isLoggedIn()?true:Redirect::to('index');

require_once 'after_login_header.php';
$token = Token::jwtGenerate(Session::get('user'));
?>

	<input type="hidden" id = "sid" value="<?php echo $token ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.7.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="https://www.codegreenback.com/assets/search/">
<script  type="text/javascript" src="https://www.codegreenback.com/assets/plugins/socketjs/003/"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
    <!-- <script src="text.js"></script> -->
</head>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div style="width: 80vw;margin:0 auto; margin-top:70px">
    <div>
            <div id="searchdiv">
                <!-- Full-width slides/quotes -->
                <div class="mySlides">
                    <q>Only those who dare to fail greatly can ever achieve greatly.</q>
                    <p class="author">- Robert F. Kennedy</p>
                </div>

                <div class="mySlides">
                    <q>Being Challenged In Life Is Inevitable, Being Defeated Is Optional.</q>
                    <p class="author">- Roger Crawford</p>
                </div>

                <div class="mySlides">
                    <q>A winner is a dreamer who never gives up.</q>
                    <p class="author">- Nelson Mandela</p>
                </div>

                <!-- Next/prev buttons -->
                <a class="prev" onclick="plusSlides(-1)" >&#10094;</a>
                <a class="next" onclick="plusSlides(1)" >&#10095;</a>

                <div class="container11">
                    <input autocomplete="off"  type="text" placeholder="Search..." id="search">
                    <div class="searchbut"></div>
                </div>

                <div id="mainDiv" style="display: none;">

                </div>



            </div>
    </div>


</div>

<div style="width: 80vw; margin:0 auto; display:flex">

     <div class="divRecents divbody">
 <div style="word-spacing: 3px;  font-size:20px;margin-top:15px; border-bottom:1px solid black; margin-bottom:10px"><h3 >Recents</h3></div>
        <div class='recent-users-div'>
          <div>
            <span class="spinner-loader-1"></span>
          </div>
            <!-- <a href="#">
                <div class="SearchRecentUsers">
                  <p>mansi12 <span style="font-size: 10px;">-Face/Off</span> </p> 
                </div>  
            </a>

                        <a href="#">
                <div class="SearchRecentUsers">
                  <p>mansi12 <span style="font-size: 10px;">-Face/Off</span> </p> 
                </div>  
            </a>

                        <a href="#">
                <div class="SearchRecentUsers">
                  <p>mansi12 <span style="font-size: 10px;">-Face/Off</span> </p> 
                </div>  
            </a> -->
        </div>
    </div>


    <div class="divmain divbody" style="overflow-y:hidden;">
            <!-- <h3>hello2</h3> -->
            <!-- Tab links -->
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'challenge1')">TurnYourTurn</button>
                <button class="tablinks" onclick="openCity(event, 'challenge2')">Rushour</button>
                <button class="tablinks" onclick="openCity(event, 'challenge3')">Face/Off</button>
            </div>

            <!-- Tab content -->
            <div id="challenge1" class="tabcontent active" style="display: block">
                <div>
                   <img src="https://www.codegreenback.com/public/img/003/tyt.jpg" id="challenge1Header">
                    
                </div>
              
                <div id="chalname">
                    <h1 class="ml14">
                        <span class="text-wrapper">
                          <span class="letters" style="text-align: center;"> What is TurnYourTurn?</span>
                          <span class="line"></span>
                        </span>
                      </h1>      
                </div>
                <div style="font-size: 23px;padding:20px;font-size: 1.7vw;">
			<h4 style="word-spacing: 3px;line-height: 40px;letter-spacing: 2px;text-align-last: auto;color: rgb(102, 102, 102);">
			Challenge Your Opponent in TurnYourTurn with a single question available for 24 hours. The coder who finishes the question in less time wins !! <span> <a href="/docs/challenges/TurnYourTurn.html" style="text-decoration: none; font-size:16px; color:grey; ">Learn More</a></span> 
			</h4>
                </div>
            </div>


            <div id="challenge2" class="tabcontent">
                <div>
                   <img src="https://www.codegreenback.com/public/img/003/rushour.jpg" id="challenge1Header">
                    
                </div>
              
                <div id="chalname">
                    <h1 class="ml14">
                        <span class="text-wrapper">
                          <span class="letters" style="text-align: center;"> What is Rushour?</span>
                          <span class="line"></span>
                        </span>
                      </h1>      
                </div>
                <div style="font-size: 23px;padding:30px">
			<h4 style="word-spacing: 3px;line-height: 40px;letter-spacing: 2px;text-align-last: auto;color: rgb(102, 102, 102);">
			Challenge Your Opponent in Rushour and solve all the problems in a time limit of 30 minutes to win the challenge <span> <a href="/docs/challenges/Rushour.html" style="text-decoration: none; font-size:16px; color:grey; ">Learn More</a></span>
			</h4>
                </div>
            </div> 
            

            <div id="challenge3" class="tabcontent" >
                <div>
                   <img src="https://www.codegreenback.com/public/img/003/faceoff.jpg" id="challenge1Header">
                    
                </div>
              
                <div id="chalname">
                    <h1 class="ml14">
                        <span class="text-wrapper">
                          <span class="letters" style="text-align: center;"> What is Face/Off?</span>
                          <span class="line"></span>
                        </span>
                      </h1>      
                </div>
                <div style="font-size: 20px; padding:20px">
 		<h4 style="word-spacing: 2px;line-height: 40px;letter-spacing: 1.5px;text-align-last: auto;color: rgb(102, 102, 102);">
		Challenge Your Opponent in Face/Off and enjoy the experience of realtime challenge. The users who completes the question first wins. *The Opponent must be online to have a Face/Off <span> <a href="/docs/challenges/Faceoff.html" style="text-decoration: none; font-size:16px; color:grey; ">Learn More</a></span> 
		</h4>
                </div>
            </div> 

    </div>

        <div class="divonline divbody">
        <div style="word-spacing: 3px; font-size:20px;margin-top:15px; border-bottom:1px solid black"><h3>User Online</h3></div>

        <div style="display: flex; flex-direction:column; justify-content:stretch;width:100%" class="onlineUsers">
            <!-- <a href="#">
                <div class="SearchRecentUsers">
                ayush123
                </div>
            </a>

             <a href="#">
                <div class="SearchRecentUsers">
                ayush123
                </div>
            </a>

             <a href="#">
                <div class="SearchRecentUsers">
                ayush123
                </div>
            </a>

             <a href="#">
                <div class="SearchRecentUsers">
                ayush123
                </div>
            </a> -->
         
        </div>
     </div>



</div>



 <!-- .................................Recommended User Div. Dummy and Format for each card ............................ -->
<div style="width: 100%; padding:20px;margin-bottom:60px; display:flex;justify-content:center; align-items:center">
      <div style="margin:0 auto">
        <h1>Recommended Users</h1>
        <h5>Based on your current league</h5>
      </div>
      <div style="display: flex; position:relative; margin: 0 auto; height:200px; width:50vw;">  
        <i class="fa fa-angle-left" id="left-button"></i>
          
        <div id="recommended-Users-div">

        </div>
    
        <i class="fa fa-angle-right" id="right-button"></i>
      

      </div>
</div>


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

<div>

<?php
require_once 'footer.php';
?>

</div>


    <script type="text/javascript" src="https://www.codegreenback.com/assets/plugins/search/006/"></script>
</body>
</html>


