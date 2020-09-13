<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

Session::exists('user')?true:Redirect::to('Home');

if(isset($_GET['id'])){
    $status = Challenge1::check_challenge_1_id($_GET['id']);
    if($status['status'] == 1)
    {
        $jwt = Token::jwt_challenge_token($_GET['id'],$status['ques'],Session::get('user'),strtotime($status['endtime']));
    }
    else{
//        header("HTTP/1.1 404 Not Found");
  //      include "404.html";
	Redirect::to(404);
        exit();
 
    }
}else{Redirect::to(404);}


?>




<!DOCTYPE html>
<html lang="en">
<head>
<title>Challenge1</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script  type="text/javascript"  src="ace-builds-master/src-noconflict/ace.js" charset="utf-8"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link href="https://fonts.googleapis.com/css2?family=Merienda+One&display=swap" rel="stylesheet">
<link rel="stylesheet"  type="text/css" href="/public/css/texteditor_c1.css?id=008">
<!-- Load an icon library -->

<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>

<style>
    #overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
    }
    #loader{
  position: absolute;
  top: 50%;
  left: 50%;
  color: white;
  transform: translate(-50%,-50%);
  -ms-transform: translate(-50%,-50%);
}

</style>



</head>



<body>


<!--          loader                    -->
<div id="overlay">
    <img src="public/img/cgbLoader.gif" id="loader">
</div>


<div id="c1_logo_div">
        <img src="public/img/1.png" alt="logo" id="c1_logo">
        <p>CodeGreenBack <sub>worth your while</sub></p>
        
</div>
<div id="c1_cc_div">
        <img src="public/img/cclogo.jpeg" alt="logo" id="c1_cc">
        <h3><?php echo $status['cc']  ?></h3>
</div>
<div id="c1_header">

    <div style="display:flex; margin:auto; display:flex; justify-content:center; align-items:center">
        <div style="padding:10px;">
            <!-- <img src="/public/img/avatar.png" alt="" width="100px" style="border-radius: 50%;"> -->
            <h4 style="padding:5px;"><?php echo $status['challenger']?></h4>
        </div>
        
        <div id="c1_title" style="padding: 10px;">
            <img src="public/img/versus.png" alt="vs" width="100px">
        </div>

        <div style="padding: 10px;">
            <!-- <img src="/public/img/avatar.png" alt="" width="100px" style="border-radius: 50%;"> -->
            <h4 style="padding:5px;"><?php echo $status['opponent']?></h4>
        </div>
    </div>
</div>




<div id="time-panel">
    <div style="padding:5px">
        <div><h3 style="color:black">Time Remaining:</h3></div>
        <div id="demo"></div>
    </div>
    <div>
        <div id="demo1"></div>
    </div>
</div>





<div id="main-content">

    <div class="sidebar" >
    <button id="full_screen"><i class="fa fa-expand"></i></button>
  <button id="custom-input-but">Custom-Input</button>
  <button id="output-window-but">Output window</button>
  <button id="runcode"><i class="fa fa-running"></i> Run / Submit</button>
  <button  id="endchallenge"><i></i>End</button>
  
</div>


    <div id="editor-div">
        <div id="editor-panel">
            <div>
                <div class="dropdown">
                    <button class="dropbtn" id="showlang">Select Language</button>
                    <div class="dropdown-content">
                        <button class="selectedLang" value="python">Python</button>
                        <button class="selectedLang" value="java">Java</button>
                        <button class="selectedLang" value="cpp">Cpp</button>
			<button class="selectedLang" value="c">C</button>
                        <button class="selectedLang" value="ruby">Ruby</button>
                        <button class="selectedLang" value="javascript">Javascript</button>
                        <button class="selectedLang" value="swift">Swift</button>
                        <button class="selectedLang" value="bash">Bash</button>
                        <button class="selectedLang" value="erlang">erlang</button>
                        <button class="selectedLang" value="haskell">Haskell</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="dropdown">
                    <button class="dropbtn">Select Theme</button>
                    <div class="dropdown-content">
                        <button class="themeChange" value="chrome">Light</button>
                        <button class="themeChange" value="merbivore_soft">Dark</button>
                        
                    </div>
                </div>
            </div>

            <div>
                    <div class="slidecontainer">
                                <label for="myRange" style="color:white">Font-Size</label>
                                <input type="range" min="20" max="50" value="20" class="slider" id="myRange">
                        </div>
            </div>
        </div>

        <div id="editordiv">
            <div id="editor">

            </div>
        </div>

        
    </div>

    <div id="ques-div">

        <div id="quesHeader">
            <h2>Problem Statement...</h2>
        </div>


        <input type="text" name='ques_id' value="" hidden>
    
        <div id="ques-statement">
            <h4 id="ques"></h4>
         
        

            <div id="sample-input">
                <!-- Input format -->
                <h3 style="color:crimson">Input Format</h3>
                <div id="i-format-div" style="color: white;">
                
                </div>
            </div>
        

            <div id="sample-output">
                <!--  Output format -->
                <h3 style="color:crimson">Output Format</h3>
                 <div id="o-format-div" style="color: white;">
                    
                </div>

            </div>
            <div id="constrain">
                <!-- Constrain -->
                <h3 style="color:crimson">Constrain</h3>
                 <pre id="constrain-div" style="color:white">
                    
                </pre>

            </div>
            <div id="si">
                <!-- Sample input -->
                <h3 style="color:crimson">Sample input</h3>
                 <pre id="si-div" style="color:white">
                    
                </pre>
            </div>

            <div id="so">
                <!-- Sample Output -->
                <h3 style="color:crimson">Sample output</h3>
                 <pre id="so-div" style="color:white">
                    
                </pre>
            </div>
        </div>

         <div id="custom-input-div" style="display: none">
            <textarea  id="testcase" cols="30" rows="10" placeholder="Custom Input..."></textarea>
        </div>


        
    </div>

    <div id="output-divs" style="position:absolute; display:none">

        <div id="output-divsheader" style="background-color: black;padding:10px; display:flex; justify-content:space-between">
            <div>
                <h5 style="color: white;">Output Window</h5>
            </div>
            <div style="padding:5px" id="minimise-output-window">
                <i class="fa fa-window-minimize" style="color:white"></i>
            </div>
            
        </div>

        <div class="msg" id="customInputRow" style="display: none;">                         
            <div class="userinput-div" style="resize: both;">
                <h2 style="color:crimson">Your Input : </h2>
                <h5>
                    <pre id="userinput">

                    </pre>
                </h5>
            </div>
            <div class="useroutput-div" style="resize: both;">
                <h2 style="color:crimson">Your Output : </h2>
                <h5>
                    <pre id="output">

                    </pre>
                </h5>
                
            </div>

        </div>
        <!-- error msg -->
        <div class="msg" id="error" style="display: none">                          
                <div style="background-color: rgb(28, 32, 32); margin:10px; padding:15px;">
                    <h4 style="color:crimson">
                        Compilation Error !                
                    </h4>
                    <h5 id="errormsg" style="resize: both;">
                    </h5>
                </div>
        </div>

        <!-- output row -->
        <div class="msg" id="OutputRow" style="display:none">                         
                <div class="outputRow-divs" style="resize: both;">
                    <h3 style="color:crimson">Input: </h3>
                    <h5>
                        <pre id="input">

                        </pre>
                    </h5>
                </div>
                <div class="outputRow-divs" style="resize: both;">
                    <h3 style="color:crimson">Expected Output: </h3>
                    <h5>
                        <pre id="expectedOutput">

                        </pre>
                    </h5>
                </div>
                <div class="outputRow-divs" style="resize: both;">
                    <h3 style="color:crimson">Your Output: </h3>
                    <h5>
                        <pre id="YourOutput">

                        </pre>
                    </h5>
                    
                </div>
        </div>
    </div>

</div>



<input type="hidden" id="verify" value="<?php echo $jwt;?>">


<!-- The Modal -->
<div id="c3-model" class="c3-modal">

  <!-- Modal content -->


</div>

<script  type="text/javascript"  src="https://www.codegreenback.com/public/js/texteditor_c1.js?id=<?php echo random_int(1,1000); ?>" charset="utf-8"></script>
<script type="text/javascript">                                      
    var id = "<?php  echo $_GET['id'] ;   ?> ";
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
