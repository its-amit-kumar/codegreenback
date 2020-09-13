<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';



    if(isset($_GET['name']) && isset($_GET['vid']) && isset($_GET['email']) && isset($_GET['token']))
    {
        if($data = Token::jwtEmailTokenVerify($_GET['token']))
        {
            $user = htmlspecialchars(trim($_GET['name']));
            $email = htmlspecialchars(trim($_GET['email']));
            if($data['user'] == $_GET['name'])
            {
                if(!StaticFunc::verifyForgetPassToken($user,$email,$_GET['vid']))
                {
                    Redirect::to("../../index");
                }else{
                    echo "passed";

                    $token = $_GET['vid'];
                }
            }
        }
    }
    else{
        Redirect::to("../../index");
    }

$token_csrf = Token::generate('csrf');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?_i=<?php echo random_int(1,10000); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Forgot Password</title>

</head>
<body>


<style>
.f-header {
    width: 100%;
    height: 12vh;
    background-color:  #18183b;
    display: flex;
}

.f-header-img-div {
    width: 8%;
    background-color: rgb(61, 236, 193);
    border-top-right-radius: 60px;
    border-bottom-right-radius: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.f-header-body {
    display: flex;
    width: 90%;
    justify-content: space-between;
}

.f-header-title {
    width: 60%;
    height: 100%;
    /* background-color: rgb(107, 233, 255); */
    /* text-align: center; */
    padding: 15px;
    color: whitesmoke;
}

.f-main-container {
    width: 50vw;
    /* background-color: red; */
    height: 70vh;
    margin: 0 auto;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-image: linear-gradient(to bottom , rgb(235, 239, 245), white);
    /* border:0.1px solid grey; */
}

.input-cards {
    width: 100%;
    height: 20vh;
    /* background-color: yellow; */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin:10px;
}

.f-input-headings {
    font-size:2vw;
    padding: 10px;
    width: 100%;
    text-align: center;
    /* background-color: #18183b; */

}

.f-inputs {
    width: 100%;
    border-radius: 25px;
    height: 6vh;
    font-size: 1.1vw;
    padding: 25px;
}

#reset_pass {
    width: 100%;
    height: 5.5vh;
    border-radius: 25px;
    background-color: #18183b;
    color:white;
}

#reset_pass:focus {
    outline: none;
}

#reset_pass:hover {
    background-color: grey;
}


.res-loader {
  border: 5px solid #f3f3f3; /* Light grey */
  border-top: 5px solid #18183b; /* Blue */
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display:none;
  margin-top: 10px;
  animation: spin 2s linear infinite;
  position: absolute;
  top: 0vw;
  right: 1vw;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}


.reset-footer-msg {
    overflow: hidden;
    width:60%;
    height: 7vh;
    display: none;
    background-color: rgb(61, 236, 193);
    text-align: center;
    font-size: 1.2vw;
    position: absolute;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
    bottom: 0vh;
    left: 20%;

}


.correct {
    color:green;
    font-size: 1.4vw;
    position: absolute;
    right:2%;
    top:25%;
    display:none;
}

.incorrect {
    color:red;
    font-size: 1.4vw;
    position: absolute;
    right:2%;
    top:25%;
    display:none;
}
</style>



    
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



<div class="f-main-container">

<div style="border-bottom: 0.2px solid black ; width:80%;padding:10px">
    <div>
        <h2>Hello <?php echo escape(htmlspecialchars($user)); ?></h2>
    </div>

    <div>
        <h4>You can reset your password by typing new password in the box !</h4>
    </div>
</div>
    <div class="input-cards" >
        <div class="f-input-headings">
            <p>New Password</p>
        </div>
        <div style="width: 100%; display:flex; justify-content:center; align-items:center">
            <div style="width: 50%">
                <input type="password" name="new_pass" id="new_pass"  class="f-inputs" placeholder="Enter New Password">
            </div>
            
        </div>
        
    </div>

    <div class="input-cards">
        <div class="f-input-headings">
            <p>Re-Enter Password</p>
        </div>
        <input type="hidden" name="vid" id="vid" value="<?php echo escape($token) ?>">
        <div style="width: 100%; display:flex; justify-content:center; align-items:center">
            <div style="width:50%; position:relative">
                <input type="password" name="new_pass_re" id="new_pass_re" class="f-inputs" placeholder="Enter Above Password">
                <i class="fa fa-check-circle correct"></i>
			    <i class="fa fa-exclamation-circle incorrect"></i>
            </div>
            <input type="hidden" name="g_token" id="g-token" value="">

            <input type="hidden" name="token" id="r-token" value='<?php echo escape($token_csrf) ?>'> 
        </div>
        
    </div>


    <div style="width:100%; display:flex; justify-content:center; align-items:center">
        <div style="width: 35%;position:relative">
            <button type="submit" name="submit" id="reset_pass">Reset Password</button>
            <div class="res-loader"></div>
        </div>
            
    </div>


    
</div>

<div class="reset-footer-msg">
    <h3 id="reset-msg"></h3>
</div>


<!-- javascript starts -->
<script src="https://www.google.com/recaptcha/api.js?render=6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<script type="text/javascript">

$(document).ready(function(){

    $("#new_pass_re").keyup(function()
    {
        if($("#new_pass_re").val() !== $("#new_pass").val())
        {
            $(".incorrect").show();
            $(".correct").hide();
        }
        else{
             $(".incorrect").hide();
            $(".correct").show();
        }
    });

    $("#new_pass").keyup(function()
    {
        if($("#new_pass_re").val() === '' && $("#new_pass").val() ==='' )
        {
            $(".incorrect").hide();
            $(".correct").hide();
        }
        else if($("#new_pass_re").val() !== $("#new_pass").val())
        {
            $(".incorrect").show();
            $(".correct").hide();
        }
        else{
             $(".incorrect").hide();
            $(".correct").show();
        }
    });

  $("#reset_pass").click(function(){
    $("#reset_pass").prop("disabled", true);
    setCaptcha('g-token', ProcessResetPass);
  });        
});


function ProcessResetPass()
{
    const new_pass = $("#new_pass").val();
    const new_pass_re = $("#new_pass_re").val();

    if(new_pass === '' || new_pass_re === '')
    {
        displayMsg("All Fields Are Required !");
        $("#reset_pass").prop("disabled", false);
    }
    else if(new_pass !== new_pass_re)
    {
        displayMsg("Password Does Not Match !");
        $("#reset_pass").prop("disabled", false);
    }
    else if(new_pass.length <= 5)
    {
        displayMsg("Password Should Be Greater Than 5 Characters !");
        $("#reset_pass").prop("disabled", false);
    }
    else{
        processRequest(new_pass);
    }
}


request = null;
function processRequest(pass)
{
  const g_token = $("#g-token").val();
  const token = $("#r-token").val();
  const vid = $("#vid").val();
  const user = "<?php echo escape(htmlspecialchars($user));  ?>";
  const email = "<?php echo escape(htmlspecialchars($email)); ?>";

 

  request = $.ajax({
    url:"processRequest.php",
    type:"POST",
    dataType:"json",
    data:{
      "g-token" : g_token,
      "vid" : vid,
      "token" : token,
      "user" : user,
      "email" : email,
      "service": "R_P",
      "new_pass" : pass
    },
    beforeSend: function(){
        if (request != null) {
          request.abort();
          request = null;
        }
      
      $(".res-loader").show();
      
    },

    complete: function(){
      $(".res-loader").hide();
      $("#reset_pass").prop("disabled" , false);
    },
    success: function(json){
      console.log(json);
      if(json.status == 1)
      {
          displayMsg(json.msg);
          setTimeout(function(){
              window.location.replace("../../");
          }, 1500);
      }
    }
  })
}


function displayMsg(msg)
{
    $("#reset-msg").text(msg);
    $(".reset-footer-msg").fadeIn();

        setTimeout( function(){
            $(".reset-footer-msg").fadeOut();
        }, 3000);

}


async function setCaptcha(id, _callback) {
  var inp = document.getElementById(id);
  await grecaptcha
    .execute("6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK", { action: "homepage" })
    .then(function (token) {
      inp.value = token;
    });

  _callback();
}
</script>



</body>
</html>