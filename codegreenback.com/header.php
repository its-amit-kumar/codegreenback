<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$token1 = Token::generate('signup');
$token2 = Token::generate('login');
//print_r($_SESSION);

?>

<!-- before login header    -->
<!DOCTYPE html>
<html>
<head>

<style>
.grecaptcha-badge { 
    visibility: hidden;
}
</style>

<script type="text/javascript">
	if(!navigator.cookieEnabled){
		alert("pls turn on cookies for better user experience !!")
	}

</script>


	<title>codegreenback home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:600|Libre+Baskerville&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href='https://fonts.googleapis.com/css?family=Plaster' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="public/css/index.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:600|Libre+Baskerville&display=swap" rel="stylesheet">
 <script src="https://kit.fontawesome.com/3b74ec65a6.js" crossorigin="anonymous"></script>
 <link href='https://fonts.googleapis.com/css?family=Candal' rel='stylesheet'>
 <link href='https://fonts.googleapis.com/css?family=Asul' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Hammersmith One' rel='stylesheet'>
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

<link rel="shortcut icon" href="#" />

  <link rel="icon" 
      type="image/png" 
      href="1.png" />

</head>
<body>

<div class="container hiden" style="margin-left: 20px;width:100%">
	<div class="row">
		
		<div class="col-sm-1" align="center" style="background-color:#1f1f2e; height: 180px;display: flex;border-color:#1f1f2e;padding: 0px;border-bottom-left-radius: 60px;border-bottom-right-radius: 60px;">
			 <img src="1.png" style="margin-top:70px;width: 88px; height:88px;margin-left:auto; object-fit: cover;margin-right: auto; padding-left: 0px;">
			  <!--img src="1.png" style="width: 88px; margin-top:9px; height:88px;object-fit: cover; padding-left: 0px; margin:auto;"-->
		</div>	
		<div class="col-sm-11" style="background-color:#00cc99;">
			<div class="row">
				<div class="col-sm-12">
					<div class="container-fluid" style="margin-top:0px;margin-left: 0px; height: 80px; margin-right: 0px;">
                  	<h1 style="text-align: left; padding-left: 0px; padding-top: 20px; color:white;font-size: 58px;text-indent: left;" >Code GreenBack        <span id="wyw" > ...worth your while</span></h1>
                	</div>

            	</div>
            	<div class="col-sm-12" style="width:100%; background-color:#00cc99; border:0px;outline:none;margin-bottom: 0px; height: 48px; margin-left: 0px; padding-left:0px;">
				       	
            		 <nav class="navbar navbar-inverse navbar1" style="border:none; outline:none;" >
                     <div class="container-fluid">
                     	<button type="button" class="navbar-toggle w" data-toggle="collapse" data-target="#myNavbar" >
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        </button>
				         <div class="collapse navbar-collapse" id="myNavbar" style="padding-left:0px;" >
                     <ul class="nav navbar-nav">
	                     <li><a href="#" class="active"><span><i class="fas fa-home"></i></span>  Home</a></li>
	                     <li><a href="#"><span><i class="fa fa-fw fa-search"></i></span>  About</a></li>
	                     <li><a href="#"><span><i class="fa fa-file-code-o" aria-hidden="true"></i></span>  Compile</a></li>
	                    
                     <li class="dropdown">
				         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-book" aria-hidden="true"></i> &nbsp; Learn <span class="caret"></span></a>
				        <ul class="dropdown-content">
				         <a href="#">Python</a>
				         <a href="#">Java</a>
				         <a href="#">C++</a>
				         <a href="#">C</a>
				        </ul>    
                     </li>
     
				      <li class="dropdown">
				         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-laptop" aria-hidden="true"></i> &nbsp; Practice <span class="caret"></span></a>
				         <ul class="dropdown-content">
				         <a href="#">Beginner</a>
				         <a href="#">Intermediate</a>
				         <a href="#">Hard</a>
				         </ul>
				     </li>
				      <li class="dropdown">
				         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;Compete<span class="caret"></span></a>
				         <ul class="dropdown-content">
				         <a href="#">Competition1</a>
				         <a href="#">Competition2</a>
				         <a href="#">Competition3</a>
				         </ul>
				     </li>
                     </ul>	
				<!--Right side-->
				    <ul class="nav navbar-nav navbar-right">
				      
				     <li class="dropdown">
				         <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-info" aria-hidden="true"></i>&nbsp;Help<span class="caret"></span></a>
				        <ul class="dropdown-content">
				          <a href="#"><span><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; Email</span></a>
				          <a href="#"><span><i class="fab fa-instagram"></i></span>&nbsp;Insta</a>
				          <a href="#"><span><i class="fab fa-twitter"></i></span>&nbsp;Twitter</a>
				          <a href="#"><span><i class="fab fa-facebook"></i></span>&nbsp;Facebook</a>
				          <a href="#"><span><i class="fa fa-linkedin" aria-hidden="true"></i></span>&nbsp;LinkedIn</a>
				        </ul>
				     </li>
			    
					      <li><a data-target="#signup" data-toggle="modal" ><span class="glyphicon glyphicon-user "></span> Sign Up</a></li>
					      <li><a data-target="#login" data-toggle="modal"><span class="glyphicon glyphicon-log-in "></span> Login</a></li>
					       
			      &nbsp;
			      &nbsp;
                     </ul>
                     </div>
                     </div>
                     </div>
                     </nav>
		</div>
	</div>
</div> 
       
  
  <div class="container hiden">
  <!-- Modal -->                                                         <!--for registration -->
  <div class="modal fade " id="signup" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 style="font-weight:bold; font-family: 'Segoe UI',Arial,sans-serif; color: #1f1f2e; font-size: 35px;"><span class="glyphicon glyphicon-lock" ></span>&nbsp;Sign Up</h4>
        </div>
        <div class="modal-body">
          <form role="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="formSignup">
            <div class="form-group">
              <label for="name"><span><i class="fas fa-user"></i></span>&nbsp;Name</label><i class="fas fa-info-circle" data-toggle="tooltip" title="Enter Your Name"></i>
			  <input type="text" class="form-control" id="name" name="name"  placeholder="Name" >
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error msg</small>
            </div>
            <div class="form-group">
              <label for="email"><span><i class="fas fa-globe-americas"></i></span>&nbsp;E-mail</label><i class="fas fa-info-circle" data-toggle="tooltip" title="Enter a Valid Email Address"></i>
			  <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
			  
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error msg</small>
            </div>
            <div class="form-group">
			  <label for="username"><span><i class="far fa-hand-paper"></i></span>&nbsp; Username</label><i class="fas fa-info-circle" data-toggle="tooltip" title="Username Must AlphaNumeric
			   i.e only Alphabets and Numbers are 
			   Allowed. Min: 5 and Max : 30 Characters"></i>

			  <input type="text" class="form-control" id="username" name="username" placeholder="Create username"> 
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error Msg</small>
            </div>
            <div class="form-group">
			  <label for="password"><span><i class="fas fa-user-lock"></i></span> &nbsp;Password</label><i class="fas fa-info-circle" data-toggle="tooltip" title="Set a Strong Password 
			   Min: 8 characters , contains atleast 1 number and a special Character like @,$,#"></i>

			  <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" >
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error msg</small>
            </div>
            <div class="form-group">
              <label for="password_again"><span><i class="fas fa-unlock"></i></span>&nbsp; Re-Enter Password</label>
			  <input type="password" class="form-control" id="password_Again" name="password_Again" placeholder="Re-Enter password">
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error msg</small>
            </div>
          
           <input type="hidden" id="token1" value="<?php echo $token1 ;?>">
		   <input type='hidden' id="g-token-signup" value=''>
            <button type="submit" class="btn btn-default btn-info" style="width: 100%;border-radius: 5px;" id="signupButton"><span class="glyphicon glyphicon-off"></span>&nbsp;Signup</button> &nbsp;&nbsp;

          
          </form>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="container hiden">                                                                   
  <div class="modal fade" id="login" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 style="font-weight:bold; font-family: 'Segoe UI',Arial,sans-serif; color: #1f1f2e; font-size: 35px;" ><span class="glyphicon glyphicon-log-in"></span> Login</h4>
        </div>
        <div class="modal-body">
			<div id="login_errorMsg"></div>
          <form role="form" method="POST" action="" id="formLogin">
            <div class="form-group">
              <label for="usrname"><span><i class="fas fa-user"></i></span>  Username</label>
			  <input type="text" class="form-control" id="usrname"  placeholder="Enter username" name="login_username" required>
			  <i class="fas fa-check-circle"></i>
			  <i class="fas fa-exclamation-circle"></i>
			  <small>Error msg</small>
            </div>
            <div class="form-group">
              <label for="psw"><span><i class="fas fa-unlock"></i></span> Password</label>
              <input type="password" class="form-control" id="psw"  placeholder="Enter password" name="login_password" required>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="remember" id="remember" >Remember me</label>
            </div>
			<input type="hidden" id="token2" value="<?php echo $token2 ;?>">
			<input type='hidden' id="g-token-login" value=''>
           <button type="submit" class="btn btn-default btn-success" style="width: 48%;border-radius: 5px;" name="login" id="loginButton"><span class="glyphicon glyphicon-off"></span>&nbsp;Login</button> &nbsp;&nbsp;

          
          </form>
        </div>
      </div>
    </div>
  </div>
</div>  
</div>

<div class="container" id="showStatus">
	<div>
		<p style="font-size: 60px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">Verification Link Sent :) </p>
	</div>

	<div id="statusContent">

	</div>
	
</div>

