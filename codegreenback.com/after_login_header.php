<?php
$img = $_SESSION['img'];
if(empty($img))
{
	$img = 'public/img/avatar.png';
}

?>
<!DOCTYPE html>
<html>
<head>

    <meta name="description" content="CodeGreenback is the new era of competitive coding. A place where you can code and earn money, make friends and challenge them to a 1v1 battle Arena. We're loaded with ton of new feature. A social coding platform for coders to code and chill, because that's what coders do."/>

   <meta name="keywords" content="earn money, earn & code, cgb, programming competition, programming contest, online programming, online computer programming, best competitive coding">





<link rel="shortcut icon" type="image/x-icon" href="https://www.codegreenback.com/favicon.ico" />
<!-- Global site tag (gtag.js) Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-166333071-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-166333071-1');
</script>

	 <title>codegreenback</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--    <script src="https://use.fontawesome.com/a5cdbcd94b.js"></script>  -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:600|Libre+Baskerville&display=swap" rel="stylesheet">
	<script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.js"></script>


<input type="hidden" id="username" value="<?php echo $_SESSION['user'] ?>">
<input type="hidden" id="user_type" value="<?php echo $_SESSION['user_type'];  ?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda+One">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.codegreenback.com/assets/header/">
  <meta name="google-signin-scope" content="profile email">

    <meta name="google-signin-client_id" content="289502152611-qgc0c5ue90b8o4vrokbnmjl2rpsiq1ia.apps.googleusercontent.com">

    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head> 
<body>

<nav class="navbar navbar-expand-xl navbar-light bg-light" style='position:fixed;z-index:10000;width:100%; top:0;margin:0'>
	<a href="https://www.codegreenback.com/Home/" class="navbar-brand"><img src="/public/img/1.png" style='height:45px;'><b style="margin-left: 10px;">CodeGreenBack</b></a>
	<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
		<span class="navbar-toggler-icon"></span>
	</button>
	<!-- Collection of nav links, forms, and other content for toggling -->
	<div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
		<div class="navbar-nav">
			<a href="https://www.codegreenback.com/Compete/" class="nav-item nav-link active">Compete </a>

			<div class="container1 dropdown">

				<a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" id="challengeRequests">Challenge Request<span class="badge" id="numChallengeReq" style="display: none;"></span>
				</a>
				<div class="dropdown-menu" style="border:none; padding:0; padding-top:10px; border-radius:10px">
					<div class="bubble" id="notification" >

						<!-- <div style="display: flex;justify-content: center;align-items: center;" >
							<div>
								<img class="round" src="/nav/3.jpeg" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>Chandan Abhishek</h3>
								<p>wants to challange you in <br><p1 style="font-weight: bold;">Turn Your Turn</p1></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<span class="red" >20</span>Code Coins
								<i class="fa fa-check-circle" style="font-size:38px;color:#12c46b;"></i>
								<i class="fa fa-times-circle" style="font-size:38px;color:red;"></i>
							</div>


						</div>			 -->
					</div>		
				</div>
			</div>

			<div class="container2 dropdown">

				<a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" id="userReq">Your Challenges<span class="badge" id="numUserReq" style="display:none"></span></a>
				<div class="dropdown-menu" style="border:none; padding:0;padding-top:5px;  border-radius:10px">
					<div class="bubble" id="yourReq">
						<!-- <div style="display: flex;justify-content: center;align-items: center;" >
							<div>
								<img class="round" src="/nav/3.jpeg" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>Chandan Abhishek</h3>
								<p>wants to challange you in <br><p1 style="font-weight: bold;">Turn Your Turn</p1></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<span class="red" >20</span>Code Coins
								<i class="fa fa-check-circle" style="font-size:38px;color:#12c46b;"></i>
								<i class="fa fa-times-circle" style="font-size:38px;color:red;"></i>
							</div>


						</div>			 -->
					</div>		
				</div>
			</div>

			<div class="container3 dropdown">

				<a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" id="friendReqBut">Friend Requests<span class="badge" id="numFriendReq" style="display:none"></span></a>
				<div class="dropdown-menu" style="border:none;padding:0;padding-top:5px;  border-radius:10px">
					<div class="bubble" id="friendRequests" style='width:600px'>
						<!-- <div style="display: flex;justify-content:space-around;align-items: center;padding: 15px;" >
							<div>
								<img class="round" src="/nav/3.jpeg" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>ayush123</h3>
								<p>Wants To Be Your Friend <br></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<i class="fa fa-check-circle" style="font-size:38px;color:#12c46b;"></i>
								<i class="fa fa-times-circle" style="font-size:38px;color:red;"></i>
							</div> -->

							<!-- <li style="display: flex; align-content: center; flex-wrap: wrap; "><div  style=" width: 70%; display: flex; align-self: center;">` +
								data[i] +
								` sent You A friend Request</div><div  style="display: flex; width: 30%;"> <button class='cgbbtn btn-accept acceptfrndReq' value='` +
								data[i] +
								`'> accept </button> <button class='cgbbtn btn-decline declinefrndReq' value='` +
								data[i] +
								`'>Decline</button></div>
							</li> -->
						</div>			
					</div>
				</div>
			</div>
		</div>

		<div class="navbar-nav ml-auto">
			<div class="nav-item nav-link " id="user-type" style='border-radius:50%;margin:0;padding:0'></div>

            <div class="nav-item dropdown">
				<a href="#"  data-toggle="dropdown" id="generalNotificationBut" class="nav-item nav-link notifications user-action active"><i class="fa fa-bell-o fa-2x"></i><span class="badge" id="numGeneralReq" style="display: none;"></span></a>
				<div class="dropdown-menu" style="margin-left: -180px; width: 400px;">
					<span class="dropdown-menu-arrow"></span>
					<div id="generalNotification" style="width: 100%;display: flex; justify-content: center; flex-direction:column">
						<div style="width: 100%;display: flex; justify-content: center;background-color: #ffffff;">
						<a href="#" class="dropdown-item" style="margin:0 auto;text-align: center;" id="clearGeneralNotification"><i class="fa fa-trash" style="margin:0 auto; font-size: 2vw;"></i></a></a>
						<div class="dropdown-divider"></div>
                    </div>

                        <div id="generalNotification-content" style="padding: 10px;z-index:100">
			</div>
					</div>
				</div>
			</div>
			<div class="nav-item dropdown">
				<a  id="headerUsername" href="#" data-toggle="dropdown" class="nav-link dropdown-toggle user-action active"><img src="/<?php echo $img ?>" class="avatar" alt="Avatar"><?php echo Session::get(Config::get('session/session_name')); ?><b class="caret"></b></a>
				<div class="dropdown-menu">
					<span class="dropdown-menu-arrow"></span>
					<a href="https://www.codegreenback.com/userprofile/" class="dropdown-item"><i class="fa fa-user-o"></i> Profile</a></a>
					<a href="https://www.codegreenback.com/accounts/pay/buycc.php" class="dropdown-item"><i class="fas fa-coins fa-4x"></i> Buy CC</a></a>
                    <a href="https://www.codegreenback.com/AccountSettings/" class="dropdown-item"><i class="fa fa-sliders"></i> Settings</a></a>
                    <a href="https://www.codegreenback.com/accounts/redeem" class="dropdown-item"><i class="fa fa-sliders"></i> Account</a></a>
					<div class="dropdown-divider"></div>
					<a href="#"  id='logout' class="dropdown-item"><i class="material-icons">&#xE8AC;</i> Logout</a></a>
				</div>
			</div>
		</div>
	</div>
	
</nav>

<script>
    const user_type = "<?php echo Session::get(Config::get('session/user_type'));  ?>";
</script>



<script type="text/javascript" src="https://www.codegreenback.com/assets/plugins/getnot/005/" ></script>
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
	    console.log('User signed out.');
    });
  }

      function onLoad() {
      gapi.load('auth2', function() {
        gapi.auth2.init();
      });
    }
  $("#logout").click(function(){
	  signOut();
	      window.location.replace("https://www.codegreenback.com/logout.php");

  })

</script>
 <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
