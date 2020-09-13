<?php 

// this page is for searched user information and to make challenges


require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(!Session::exists(Config::get('session/session_name')))
{
	Redirect::to('');
	exit();
}
$user = Session::get(Config::get('session/session_name'));

$_cs_to = Token::generate('csrf');                         //csrf token

$token = Token::jwtGenerate(Session::get('user'));


if(isset($_GET['search']) && $_GET['search'] != $user)
{
    echo '<script type="text/javascript">const search_username = "'.$_GET['search'].'"</script>';
    echo '<script type="text/javascript">const user_cc = "'. Session::get(Config::get('session/user_cc')) .'"</script>';
    echo '<script type="text/javascript">const user = "'. Session::get(Config::get('session/session_name')) .'"</script>';
    echo '<script type="text/javascript">const league = "'. Session::get(Config::get('session/user_league')) .'"</script>';
    echo '<script type="text/javascript">const userType = "'. Session::get(Config::get('session/user_type')) .'"</script>';

        require_once 'after_login_header.php';
    }
    else {
        Redirect::to(404);
	exit();
    }

?>
	<input type="hidden" id="sid" value="<?php echo $token ?>">
<link rel="stylesheet" href="https://www.codegreenback.com/public/css/challenge.css?id=001">
<input type="hidden" id="profile_username" value="<?php echo $_GET['search']?>">





<!-- ..................loader................ -->
<div class="overlay-loader" style="display:none">
    <div class="overlay__inner-loader">
        <div class="overlay__content-loader">
          <div>
            <span class="spinner-loader"></span>
          </div>
          
          <div>
            <p class='process-msg'></p>
          </div>
        </div>
    </div>
</div>


<!-- loader for challenge3 -->

<div class="overlay-loader-c3" style="display:none">
    <div class="overlay__inner-loader">
        <div class="overlay__content-loader">
          <div>
            <span class="spinner-loader"></span>
          </div>
          
          <div style="display: flex; flex-direction:column; justify-content:center; align-items:center">
            <div>
              <p class='process-msg-c3'></p>
            </div>
            <div>
              <h1 id="timeout" style="font-size: 2vw;"></h1>
            </div>
            
          </div>
        </div>
    </div>
</div>








<div class="container" style='margin-top:70px;'>
<div class='s-main-div'>

<div class="container">

  <div class="row">

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="our-team">
        <div class="picture">
          <img class="img-fluid" src="https://www.codegreenback.com/public/img/avatar.png" alt="user-image" id="searched_user_image">
        </div>

        <div class='online-status ofline-active'>
          <span class='online-circle'></span>
        </div>

        <div class="team-content">
          <h3 class="name" id="searchedUser-username"></h3>
          <h4 class="title" id="searchedUser-name"></h4>
        </div>

        <div style=" width:90%;" id="friendStatus">
            
        </div>

        <div class="searchedUser-dropdown">
          <button  class="searchedUser-dropbtn"><i class="fa fa-ellipsis-v"></i></button>
          <div id="myDropdown" class="searchedUserdropdown-content">
            
          </div>
        </div>

        
          <div style="width: 100%; display:flex; justify-content:center" class='challenge-but-div' >
            <button type="button" class="btn btn-primary" id="challengeButton">CHALLENGE</button>
        </div>

      
        <ul class="social">

        </ul>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-8 col-lg-9 searched-user-data">
                    <div class="container">
                <div class="row">
                  <div class="col-sm">
                    <div class="cards zoom shadow-lg p-3 mb-5 rounded">

                            <div class="icon"><i class="fa fa-trophy" aria-hidden="true" style='font-size:48px'></i></div>
                            <p class="titles">League</p>
                            <p class="texts" id="searchedUser-League">-</p>

                    </div>
                  </div>
                  <div class="col-sm">
                    <div class="cards zoom shadow-lg p-3 mb-5 rounded">

                            <div class="icon"><i class="fa fa-shield" aria-hidden="true" style='font-size:48px'></i></div>
                            <p class="titles">Total Challenges</p>
                            <p class="texts" id="searchedUser-Challenges">-</p>

                    </div>
                  </div>
                  <div class="col-sm">
                    <div class="cards zoom shadow-lg p-3 mb-5 rounded">

                            <div class="icon"><i class="fa fa-star" aria-hidden="true" style='font-size:48px'></i></div>
                            <p class="titles">Points</p>
                            <p class="texts" id="searchedUser-Points">-</p>

                    </div>
                  </div>
                </div>
              </div>



            
      <div style="width:100%; border-bottom: 1px solid black;">
        <h4 style="padding: 10px;">Recommended Challengers</h4>
      </div>

      <!-- .................................Recommended User Div. Dummy and Format for each card ............................ -->

      <div style="display: flex; position:relative; margin-top:10px; height:200px; width:40vw; margin-left:50px">
        
        
          <i class="fa fa-angle-left" id="left-button"></i>
          
        <div id="recommended-Users-div">

         
      </div>
    
        <i class="fa fa-angle-right" id="right-button"></i>
      

      </div>

    </div>

    



  </div>
</div>


</div>

</div>

<div class="container">
  <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal fade" id="myModal" >
    <div class="modal-dialog modal-xl modal-dialog-centered"style="width: 100vw; display:flex;justify-content:center" >
      <div class="modal-content" style='width:50%'>
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Challenge <?php echo $_GET['search'];  ?></h4>
          <button type="button" class="close" data-dismiss="modal" style="color:#f1f1f1">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

            <div id="selectChallenge">
            <div id="selectChallenge-title">
              <h3 style="font-size: 2vw">Select Challenge ..</h3>
            </div>
              <div>
                <button id="challenge1"  class="challengesbut" value="TurnYourTurn">TurnYourTurn</button>
              </div>
              <div>
                <button id="challenge2" class="challengesbut" value="Rushour">Rushour</button>
              </div>
              <div>
                <button id="challenge3"  class="challengesbut" value="Face/Off">Face/Off</button>
              </div>

            </div>

            <div id="selectCC" style="display: none">
              <div id="selectedChallenge">

              </div>
              <div>
                <h1>CodeCoins</h1>
                <div>
                  <p class='msg_001'></p>
                </div>
                
              </div>
              <div class="slidecontainer" style="width: 100%;margin-top:30px">
                <input type="range" min="10" max="10" value="10" class="slider" id="ccrange">
              </div>
              <div id="ccValue">

              </div>
              <div style="display:flex;width: 100%">
                <div id="back">
                  <button >Go Back</button>
                </div>

                <div id="sendChallengeRequest">
                  <button data-dismiss="modal">Challenge</button>
                </div>
              </div>
       
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger modal-close-button" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>

<!-- tok -->
<input type="hidden" id="_cs_to" value="<?php echo $_cs_to; ?>">

<div id="customAlert" class="customModal">

  <!-- Modal content -->
  <div class="customModal-content">
    <span class="customClose">&times;</span>
    <p id="customAlertMsg"></p>
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
      <p id="c3-body-msg"></p> 
      <span class='c3-cc-img'><img src="https://www.codegreenback.com/public/img/cclogo.png" alt="cc-log" ></span>
      <span id="c3-cc-bet"></span>
    </div>

    <div class='c3-timer-div'>
      <p id="c3-timer"></p>
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
<div class="overlay-loader-req-c3" style="display: none;">
    <div class="overlay__inner-loader-req">
        <div class="overlay__content-loader-req">
          <div>
            <span class="spinner-loader-req"></span>
          </div>
          
          <div>
            <p class='process-msg-c3-req'></p>
          </div>
        </div>
    </div>
</div>

 <script type="text/javascript" src="https://www.codegreenback.com/public/js/challenge.js?id=009"> </script>   
<?php require_once "footer.php"; ?>
</body>
</html>


    
