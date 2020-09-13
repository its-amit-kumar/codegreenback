<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
$user = new User();
$user->isLoggedIn()?true:Redirect::to('index');
$data = $user->data();
// print_r($data);
$user_img = $data->user_image;
$stats = $user->statsData();
$cc = $user->cc();                                         //update cookie cc here at this page
Cookie::put('cc',$cc->cc,86400);                             // .....update cookie at the backend when the user redeems the codecoins
$token = Token::generate('csrf');                                
// print_r($_COOKIE);

require_once 'after_login_header.php';
?>
<link rel="stylesheet" href="https://www.codegreenback.com/public/css/profile.css?id=001">
<script type="text/javascript" src="https://www.codegreenback.com/public/js/profile.js?id=007"></script>


<div class="container" style="background-color: red; margin-top:70px;">
    <div class="row content">
        <div class="col-sm-12" id="profile_background" style="position: relative;">
            <div style="position: absolute;left:7%; top:28%; display:flex; flex-direction:column; justify-content:space-between; align-items:center">
              <div>
                <img  src="" alt="userimage" id="profile_user_image" class="img-circle center-block">
              </div>
              <div>
                  <h3 style="text-align:center;" id="profile_username"><?php echo $data->username; ?></h3>
              </div> 
        
            
            </div>
        

        </div>
    </div>
</div>
 <!-- main control panel for data -->
<i class='fa fa-Running' style='color:green; font-size:1.2vw'></i>
 <div class="container" style="display: flex; height:60vh; margin-top:20px">
    <div id="profileSideNav">
        <button  id="profileGeneralBut" class='p-side-nav-but'>General</button>
        <button  id="profileChallenge1But" style="border-left:5px solid red" class='p-side-nav-but'>TurnYourTurn</button>
        <button  id="profileChallenge2But"style="border-left:5px solid orange" class='p-side-nav-but'>Rushour</button>
        <button  id="profileChallenge3But"style="border-left:5px solid blue" class='p-side-nav-but'>Face/Off</button>
        <button  id="profileFriendBut" class='p-side-nav-but'>Friends</button>
    </div>

      <!-- Page content -->
    <div class="content" style="margin-top:20px; padding:0; width:100%; min-height:90vh;">
      <div id="profileGeneral" class="profileMainContent" >
         <div style="text-align:center">
            <h2>OverAll Stats</h2>
        </div>
        <div  class="profileMainContentBody">

          <div class="zoom" >
            <h4>Global Rank</h4>
            <h3 id="profileGlobalRank">1</h3>
          </div>

          <div class="zoom" >
            <h4>League</h4>
            <h3 id="profileLeague">Bronze</h3>
          </div>

          <div class="zoom" >
            <h4>OverAll points</h4>
            <h3 id="profileOverAllPoints">250</h3>
          </div>

        </div>
        <div style="text-align:center">
            <h2>Challenge Stats</h2>
            
        </div>

        <div class="profileMainContentBody">

          <div class="zoom" >
            <h4>Total Challenges</h4>
            <h3 id="profileTotalChallenges">2</h3>
          </div>

          <div class="zoom" >
            <h4>Successfully Compiled</h4>
            <h3 id="profileTotalQues">6</h3>
          </div>

          <div class="zoom" >
            <h4>Won</h4>
            <h3 id="profileTotalChallengesWon">1</h3>
          </div>

        </div>
        <!-- <div class="profileMainContentBody" style="flex-direction: column">
          <div>
            <h2>Language Proficiency</h2>
          </div>
          
          <div style="width: 80%">
            <h3>C++</h3>
            <div class="profileLanguageProficiency">
              <div class="skills html">90%</div>
            </div>

            <h3>Python</h3>
            <div class="profileLanguageProficiency">
              <div class="skills css">80%</div>
            </div>

            <h3>Java</h3>
            <div class="profileLanguageProficiency">
              <div class="skills js">65%</div>
            </div>

            <h3>PHP</h3>
            <div class="profileLanguageProficiency">
              <div class="skills php">60%</div>
            </div>

          </div>
          
        </div> -->

     
   
       
      </div>

      <div id="profileChallenge1" class="profileMainContent" style="display: none;max-height:50vh;overflow-y:auto">
        <div class="loader" style="display: none;"></div>
        <div>
          <table id="challenge1Table" class='info-tables' style='overflow-y:auto'>
            <!-- <tr>
                <th></th>
                <th>Opponent</th>
                <th>Date</th>
                <th>CC</th>
                <th>Status</th>
            </tr> -->
         
          </table>
        </div>
      </div>

      <div id="profileChallenge2" class="profileMainContent" style="display: none;max-height:50vh;overflow-y:auto">
        <div class="loader"></div>
         <div>
          <table id="challenge2Table" class='info-tables' style='overflow-y:auto'>
            <!-- <tr>
                <th></th>
                <th>Opponent</th>
                <th>Date</th>
                <th>CC</th>
                <th>Status</th>
            </tr> -->
         
          </table>
        </div>
      </div> 

      <div id="profileChallenge3" class="profileMainContent" style="display: none; max-height:50vh;overflow-y:auto">
        <div class="loader"></div>
          <div>
          <table id="challenge3Table" class='info-tables' style='overflow-y:auto'>
            <!-- <tr>
                <th></th>
                <th>Opponent</th>
                <th>Date</th>
                <th>CC</th>
                <th>Status</th>
            </tr> -->
         
          </table>
        </div>
      </div>

      <div id="profileFriends" class="profileMainContent" style="display: none">

        <div  class='profileFriendsHeader'>
            <div style="text-align:center ;margin-left:20px;">
              <h3 style="margin:0; padding-left:10px;">Friends <span id="no-of-friends"></span></h3>
            </div>

            <div>
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="search.." id="searchUserFriend">
            </div>
        </div>

          <div id="profileFriendsBody" >
            
              
          </div>
      </div>
  </div>
 </div>
<input type="hidden" name="cs" id="csrf" value="<?php echo $token ?>">
</body>
</html>

<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php include_once ("footer.php")  ?>


