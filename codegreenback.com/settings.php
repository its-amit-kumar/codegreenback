<?php

/*  Page for user account settings  */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

require_once 'after_login_header.php';

?>
<link rel="stylesheet" href="https://www.codegreenback.com/assets/settings/">

 
<script src="https://www.codegreenback.com/assets/plugins/settings/002/"></script>





<div id="setting-main" style="margin-top: 80px">
    <div id="s-side-panel">

        <h3 class="s-panel-but s-active" id="s-profile-but" >User Profile</h3>
        <h3 class="s-panel-but" id="s-notification-but">Notifications</h3>
        <!-- <h3 class="s-panel-but" id="s-codecoins-but">CodeCoins Trans.</h3> -->
        <h3 class="s-panel-but" id="s-pass-but">Change Password</h3>

    </div>

    <div id="s-main-body">

        <div id="loader" style="display: none">
            <img src="https://www.codegreenback.com/public/img/cgbLoader.gif" alt="loader">
        </div>


        <div id="s-profile" class="s-content">

            <div id="s-profile-header">
                <div id="s-image-div" style="margin-left: 10%">
                    <img src="https://www.codegreenback.com/public/img/avatar.png" id="s-user-image" alt="user-image">
                </div>

                <div style="margin-left: 2vw">
                    <h3 id="s-username"></h3>
                    <h5 id="s-name"></h5>
                </div>
                <div style="margin-left: 20px">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeProfilePic">Change Profile Picture</button>
                </div>
                
            </div>
            <form id="profileForm">
            <div id="s-profile-main">
                <div class="s-profile-input-div">
                    <div style="width: 30%">
                        <h3>Name:</h3>
                    </div>
                    <div style="width: 70%">
                        <input type="text" id="change-name" class="s-profile-input" name="change-name">
                        <i class="fa fa-exclamation-circle" style="color: red;"  id="error-icon"></i>
                    </div>
                    
                </div>

                <div class="s-profile-input-div">

                    <div style="width: 30%">
                        <h3>Add WebSite</h3>
                    </div>
                    <div style="width: 70%">
                        <input type="text" id="website" class="s-profile-input" name="website" placeholder="add website">

                    </div>
                </div>
                <div class="s-profile-input-div">

                    <div style="width: 30%">
                        <h3>About</h3>
                    </div>

                    <div style="width: 70%">
                        <textarea name="about" id="about" class="s-profile-input" cols="10" rows="4"></textarea>
                    </div>
                    
                </div>

                 <div class="s-profile-input-div">

                    <div style="width: 30%">
                        <h3>Gender</h3>
                    </div>

                    <div style="width: 70%">
                        <input type="radio" id="male" name="gender" value="male" class="s-profile-input-radio">
                        <label for="male" style="font-weight:bold">Male</label><br>
                        <input type="radio" id="female" name="gender" value="female" class="s-profile-input-radio">
                        <label for="female" style="font-weight:bold">Female</label><br>
                        <input type="radio" id="other" name="gender" value="other" class="s-profile-input-radio">
                        <label for="other" style="font-weight:bold">Other</label><br><br>
                    </div>
                    
                </div>

                </form>

                <div class="s-profile-input-div">

                    <div style="width: 30%">
                        <h3>E-mail</h3>
                    </div>

                    <div style="width: 70%">
                        <h3 id="s-email"></h3>
                    </div>
                    
                </div>


                <div id="save-changes-pro-div">
                    <h3 id="save-changes-profile" style="width: 40%" class="cursor-non">Save Changes</h3>
                    <img src="https://www.codegreenback.com/public/img/cgbLoader.gif" alt="loader" style="width: 40px;height:40px; border-radius:50%; margin-left:10px;display:none" id="loader-ajax-profile">
                </div>


                
            </div>
          
        </div>
        <!-- Notification  -->
        <div id="s-notification" class="s-content">

            <div class="s-notification-div">
                <div style="width: 80%;border-left:0.5px solid black; padding-left:2vw; height:7vh" >
                    <h4>Recieve Email Updates About New Challenges And Upcomming Events </h4>
                </div>
                <form id="NotificationForm">

                <div style="width: 20%; padding-left:5vw">
                    <label class="switch">
                        <input type="checkbox"  name="not-set-general" id="not-set-general" class="notificationInputs" >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

              <div class="s-notification-div">
                <div style="width: 80%;border-left:0.5px solid black; padding-left:2vw; height:7vh" >
                    <h4>Recieve Notifications Through Email, When any User has challenged you </h4>
                </div>

                <div style="width: 20%; padding-left:5vw">
                    <label class="switch">
                        <input type="checkbox" name="not-set-challengeRequest"  id="not-set-challengeRequest"  class="notificationInputs">
                        <span class="slider round"></span>
                    </label>

                </div>
            </div>

              <div class="s-notification-div">
                <div style="width: 80%;border-left:0.5px solid black; padding-left:2vw; height:7vh" >
                    <h4>Recieve Notifications Through Email, When a Challenge is Accepted by the opponent </h4>
                </div>

                <div style="width: 20%; padding-left:5vw">
                    <label class="switch">
                        <input type="checkbox" name="not-set-challengeAccept" id="not-set-challengeAccept" class="notificationInputs" >
                        <span class="slider round"></span>
                    </label>

                </div>
            </div>

            <div class="s-notification-div">
                <div style="width: 80%;border-left:0.5px solid black; padding-left:2vw; height:7vh" >
                    <h4>Enable Web Push Notifications </h4>
                </div>

                <div style="width: 20%; padding-left:5vw">
                    <label class="switch">
                        <input type="checkbox" name="not-set-push" id="not-set-push"  class="notificationInputs">
                        <span class="slider round"></span>
                    </label>

                </div>

            </form>
            </div>

            <div id="s-not-Msg-div">
                <h3 id="s-not-Msg"></h3>
            </div>


            <div id="savechanges-notification-div">
                <h3 id="savechanges-not-but">Save Changes</h3>
                <img src="https://www.codegreenback.com/public/img/cgbLoader.gif" alt="loader" style="width: 40px;height:40px; border-radius:50%; margin-left:10px;display:none" id="loader-ajax-Notification">
            </div>

        </div>
        <!-- Code Coins Transactrion
        <div id="s-codecoins" class="s-content">
            <div>
                <table>
                    <tr>
                        <th>Order</th>
                        <th>Order Name</th>
                        <th>Amount</th> 
                    </tr>
                    <tr>
                        <td>Jill</td>
                        <td>Smith</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>Eve</td>
                        <td>Jackson</td>
                        <td>94</td>
                    </tr>
                    <tr>
                        <td>Adam</td>
                        <td>Johnson</td>
                        <td>67</td>
                    </tr>
                </table>
            </div>

        </div> -->




        <!-- Change password -->
        <div id="s-changePassword" class="s-content">
            <form  id="changePassForm">
            <div class="s-profile-input-div">

                <div style="width: 35%">
                    <h3>Current Password</h3>
                </div>
           
                <div style="width: 60%">
                    <input type="password" name="current-pass" id="current-pass" class="s-profile-input" required>
                </div>
                    
            </div>

            <div class="s-profile-input-div">

                <div style="width: 35%">
                    <h3>New Password</h3>
                </div>

                <div style="width: 60%">
                    <input  type="password" name="new-pass" id="password" class="s-profile-input" required>
                </div>
                    
            </div>

            <div class="s-profile-input-div">

                <div style="width: 35%">
                    <h3>Re-enter Password</h3>
                </div>

                <div style="width: 60%">
                    <input  type="password" name="password-again" id="password-again" class="s-profile-input" required>
                    <i class="fa fa-check-circle" style="color:green;" id="icon-correct"></i>
			        <i class="fa fa-exclamation-circle" style="color: red"  id="icon-incorrect"></i>
                </div>
                    
            </div>
                <input type="hidden" name="token" value="<?php echo Token::generate('update_token');?>">
            </form>
            <div id="save-changes-cp-div">
                <h3 id="save-changes-cp" class="cursor-non">Save Change</h3>
                <img src="https://www.codegreenback.com/public/img/cgbLoader.gif" alt="loader" style="width: 40px;height:40px; border-radius:50%; margin-left:10px;display:none" id="loader-ajax">
                <a href="#" style="margin-left: 15px; font-size:15px;padding:10px; padding-top:20px">Forgot Password ?</a>
            </div>


            <div id="s-cp-errorMsg-div">
                <h3 id="s-cp-errorMsg"></h3>
            </div>
        </div>

    </div>


</div>

  <script type="text/javascript" src="https://www.codegreenback.com/imagetest/croppie.js"></script>
    <link rel="stylesheet" href="https://www.codegreenback.com/imagetest/croppie.css">

 <!-- The Modal -->
  <div class="modal" id="changeProfilePic" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Change Profile Picture</h4>
          <button type="button" class="close" id="closemodal1" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <input type="file" name="s-upload-image" id="upload-image" style="display: none;">
            <h3 class="s-panel-but" id="s-upload-but" data-dismiss="modal">Upload New Photo</h3>
            <h3 class="s-panel-but" id="s-remove-but">Remove Current Photo</h3>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>


<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog" id="modal-dialog-image">
        <div class="modal-content" id="modal-content-image">
            <div class="modal-header-image">
                <div>
                    <h4 class="modal-title" style="font-size: 5vh">Upload & Crop Image</h4>
                </div>
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 8vh">&times;</button>
                </div>
                
                
            </div>

            <div class="modal-body" id="modal-body-image">

                <div id="image-content-div">
                    <div style="width: 100%">
                        <div id="image_demo">
                        </div>
                    </div>
                </div>

                <div id="modal-image-upload-but">
                    <div>
                        <button class="btn crop_image">Crop & Upload Image</button>
                    </div>
                    
                </div>
       <!-- <div class="col-md-4" style="padding-top:30px;">
        <br />
        <br />
        <br/>
         -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        
</div>
 

<div style="margin-top: 60px;">
    <?php
    require_once "footer.php";
    ?>
</div>

 <script type="text/javascript" src="https://www.codegreenback.com/imagetest/croppie.js"></script>



<!-- 
    <div id="uploadimageModal" class="modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload & Crop Image</h4>
        </div>
        <div class="modal-body">
          <div class="row">
       <div class="col-md-8 text-center">
        <div id="image_demo" style="width:750px; margin-top:30px">
        </div>
       </div>
       <div class="col-md-4" style="padding-top:30px;">
        <br />
        <br />
        <br/>
        <button class="btn btn-success crop_image">Crop & Upload Image</button>
     </div>
    </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
     </div>
    </div>
</div> -->
