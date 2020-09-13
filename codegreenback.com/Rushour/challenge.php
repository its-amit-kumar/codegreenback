<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


$cid = $_GET['cid'];
$quesNo = $_GET['qNo'];
$user = new User();

if($quesNo>3 || $quesNo<1){
	Redirect::to(404);
        exit();
}


if(!$user->isLoggedIn()){
	Redirect::to('');
}

        if(Session::exists(Config::get('session/session_name')))
        {
                $user = Session::get(Config::get('session/session_name'));
                $obj = new Challenge2($user , $_GET['cid']);
                if($obj->check()){
                        if(!$obj->checkChallengeStatus()){
                               Redirect::to(404);
        exit();

                        }
                }
                else{
                        //Redirect::to(404);

                }
        }

        $challenge = new Challenge2($_SESSION[Config::get('session/session_name')],$cid);
        $typeOfUser;
      
        if($challenge->isChallenger()){
          $typeOfUser = "challenger";
        }
        else{
          $typeOfUser = "opponent";
        }
      
        if(!$challenge->checkStarted()){
          $challenge->setTime($typeOfUser);
      
        }



?> 





<html>
    <head>
        <title>
            Rush Hour
        </title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="challenge.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </head>
    <body>
    <div id="starter_loader" hidden = "hidden" style="background: white; position: fixed; z-index: 3; width:100%; height: 100%; display:flex; justify-content:center; align-items:center;" >

<div>
        <h1>Preparing Challenge Please Wait</h1>
</div>
<div style="border-radius:50%;">
        <img src="https://www.codegreenback.com/public/img/White_Loading.gif" alt="loader" style="border-radius: 50%">
</div>

<div>
        <h2 id="start-time"></h2>
</div>
</div>


        <div class="info">
            <br>
            <br>
            <div class = "versus">
                <div id = "challenger" style="width : 48%;text-align : center; vertical-align : middle;"><center>alebron77</center></div>
                <img src="data/vs.png" height="50px" width="50px" style="width : 4%;">
                <div id = "opponent" style="width : 48%; text-align: center; vertical-align: middle;"><center>Aankitdevil</center></div>
            </div>
	    <div class="options">
		<div class='option'id="ques1"  onclick = "window.location='challenge.php?cid=<?php echo $cid ?>&qNo=1';">
                    Question 1
		</div>
               <div class='option' id="ques2"  onclick = "window.location='challenge.php?cid=<?php echo $cid ?>&qNo=2';">
                    Question 02
		</div>
                <div class='option' id="ques3" onclick = "window.location='challenge.php?cid=<?php echo $cid ?>&qNo=3';">
                    Question 03
		</div>

            </div>
            
        </div>
        <span class="dot">
        <img src="data/ad.png" id="ad">
        </span>

        


<input type="hidden" id="quesNo" value="<?php echo $quesNo?>">
<input type="hidden" name="coderun" id="coderun" value ="<?php echo escape(Token::generate('coderun'));  ?>">
<input type="hidden" id="cid" value="<?php echo $cid ?>">



<div id="cont"  style="display: flex;flex-direction: column;">
	<div class="content"  id="questionDisplayer">
    <h1 id="title">
    </h1>
    <hr>
		<br>
		<h1 id="Title">
			QUESTION STATEMENT
		</h1>
		<hr>
		<div id="statement" class="text">
			
		</div>
		<br>
		<h1 id="Title">
			INPUT FORMAT
		</h1>
		<hr>
		<div id="if" class="text">
			
		</div>
		<br>
		<h1 id="Title">
			OUTPUT FORMAT
		</h1>
		<hr>
		<div id="of" class="text">
			
		</div>
		<br>
		<h1 id="Title">
			CONSTRAIN
		</h1>
		<hr>
		<div id='constrain' class="text">
			
		</div>
		<br>
		<br>
		<h1 id="Title">
			SAMPLE INPUT
		</h1>
		<hr>
		<div class="sample rounded">
			<div id='sampleI' class="col-12 text">
			
			</div>
		</div>
		<br>
		<h1 id="Title">
			SAMPLE OUTPUT
		</h1>
		<hr>
		<div class="sample rounded">
			<div id='sampleO' class="col-12 text">
				
			</div>
		</div>
		<br>
	</div>























<div class ="dragbar" hidden="hidden">
</div>


	<div class="tx">
 
		<a href="#l" hidden="hidden"></a>
		<nav class="navbar navbar-dark bg-dark rounded">
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img src="data/views.png" class="views">
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" id = "ud" href="#">Up Down</a>
    <a class="dropdown-item" id="sbs" href="#">Side By Side</a>
    <a class="dropdown-item" id="fs" href="#">Full Screen</a>
  </div>
</div>
  <label>
    <select id="LanguageSelector" class="LanguageSelector rounded highlight">
    <option value="" selected="" disabled="" hidden="">Language</option> 
        <option value="python" id="python">Python</option> 
        <option value="cpp" id="cpp">C++</option> 
        <option value="c" id="c">C</option> 
        <option value="java" id="java">Java</option> 
        <option value="bash" id="bash">Bash</option> 
        <option value="erlang" id="erlang">Erlang</option>  
        <option value="haskell" id="haskell">Haskell</option> 
        <option value="javascript" id="javascript">Javascript</option> 
        <option value="ruby" id="ruby">Ruby</option> 
        <option value="swift" id="swift">Swift</option> 
    </select> 
  </label>
  <label>
    <select id="ThemeSelector" class="ThemeSelector rounded"> 
        <option value="ambiance" id="LanguageOption">Ambiance</option> 
        <option value="chaos" id="LanguageOption">Chaos</option> 
        <option value="chrome" id="LanguageOption">Chrome</option> 
        <option value="clouds" id="LanguageOption">Clouds</option>
        <option value="clouds_midnight" id="LanguageOption">Clouds Midnight</option> 
        <option value="cobalt" id="LanguageOption">Cobalt</option> 
        <option value="crimson_editor" id="LanguageOption">Crimson Editor</option> 
        <option value="dawn" id="LanguageOption">Dawn</option> 
        <option value="dracula" id="LanguageOption">Dracula</option> 
        <option value="dreamweaver" id="LanguageOption">Dreamweaver</option> 
        <option value="eclipse" id="LanguageOption">Eclipse</option> 
        <option value="github" id="LanguageOption">Github</option> 
        <option value="gob" id="LanguageOption">Gob</option> 
        <option value="gruvbox" id="LanguageOption">Gruvbox</option> 
        <option value="idle_fingers" id="LanguageOption">Idle Fingers</option> 
        <option value="iplastic" id="LanguageOption">Iplasic</option> 
        <option value="katzenmilch" id="LanguageOption">Katzenmilch</option> 
        <option value="kr_theme" id="LanguageOption">Kr Theme</option> 
        <option value="kuroir" id="LanguageOption">Kuroir</option> 
        <option value="merbivore" id="LanguageOption">Merbivore</option> 
        <option value="merbivore_soft" id="LanguageOption">Merbivore Soft</option> 
        <option value="mono_industrial" id="LanguageOption">Mono Industrial</option>  
        <option value="monokai" id="LanguageOption">Monokai</option> 
        <option value="nord_dark" id="LanguageOption">Nord Dark</option> 
        <option value="pastel_on_dark" id="LanguageOption">Pastel on Dark</option> 
        <option value="solarized_dark" id="LanguageOption">Solarized Dark</option> 
        <option value="solarized_light" id="LanguageOption">Solarized Light</option> 
        <option value="sqlserver" id="LanguageOption">SQL Server</option> 
        <option value="terminal" id="LanguageOption">Terminal</option> 
        <option value="textmate" id="LanguageOption">Textmate</option> 
        <option value="tomorrow" id="LanguageOption">Tomorrow</option> 
        <option value="tomorrow_night" id="LanguageOption">Tomorrow Night</option>
        <option value="tomorrow_night_blue" id="LanguageOption">Tomorrow Night Blue</option> 
        <option value="tomorrow_night_bright" id="LanguageOption">Tomorrow Night Bright</option> 
        <option value="tomorrow_night_eighties" id="LanguageOption">Tomorrow Night Eighties</option> 
        <option value="twilight" selected="selected" id="LanguageOption">Twilight</option> 
        <option value="vibrant_ink" id="LanguageOption">Vibrant Ink</option> 
        <option value="xcode" id="LanguageOption">X Code</option> 

    </select>
  </label>
    <label>
    <input type="number" class="LanguageSelector rounded" value="18" min="10" max="50" id="textsize">
  </label>
    <label class="downloadCode" title="Download this code." id="download">
      <img src="data/downloadCode.png" style=" width: 70%; margin-left: 15%; margin-bottom: 10%;">
    </label>
    <label for="inputfile" class="uploadCode" title="Upload your code.">
      <img src="data/uploadCode.png" style=" width: 70%; margin-left: 15%; margin-bottom: 10%;">
      <input type="file" name="inputfile" id="inputfile" style="display:none;">
    </label>
    <label class="deleteCode" title="Clear this code.">
      <img src="data/deleteCode.png" style=" width: 70%; margin-left: 15%; margin-bottom: 10%;">
    </label>
    <label class="keyBinding" type="button" data-toggle="modal" data-target="#exampleModalLong" title="Short-cut Keys.">
      <img src="data/keyBinding.png" style=" width: 70%; margin-left: 15%; margin-bottom: 10%;">
    </label>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Key Bindings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
      <h2>Line Operations</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Ctrl-D</td>
<td align="left">Command-D</td>
<td align="left">Remove line</td>
</tr>
<tr>
<td align="left">Alt-Shift-Down</td>
<td align="left">Command-Option-Down</td>
<td align="left">Copy lines down</td>
</tr>
<tr>
<td align="left">Alt-Shift-Up</td>
<td align="left">Command-Option-Up</td>
<td align="left">Copy lines up</td>
</tr>
<tr>
<td align="left">Alt-Down</td>
<td align="left">Option-Down</td>
<td align="left">Move lines down</td>
</tr>
<tr>
<td align="left">Alt-Up</td>
<td align="left">Option-Up</td>
<td align="left">Move lines up</td>
</tr>
<tr>
<td align="left">Alt-Delete</td>
<td align="left">Ctrl-K</td>
<td align="left">Remove to line end</td>
</tr>
<tr>
<td align="left">Alt-Backspace</td>
<td align="left">Command-Backspace</td>
<td align="left">Remove to linestart</td>
</tr>
<tr>
<td align="left">Ctrl-Backspace</td>
<td align="left">Option-Backspace, Ctrl-Option-Backspace</td>
<td align="left">Remove word left</td>
</tr>
<tr>
<td align="left">Ctrl-Delete</td>
<td align="left">Option-Delete</td>
<td align="left">Remove word right</td>
</tr>
<tr>
<td align="left">---</td>
<td align="left">Ctrl-O</td>
<td align="left">Split line</td>
</tr>
</tbody>
</table><br>
<h2>Selection</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Ctrl-A</td>
<td align="left">Command-A</td>
<td align="left">Select all</td>
</tr>
<tr>
<td align="left">Shift-Left</td>
<td align="left">Shift-Left</td>
<td align="left">Select left</td>
</tr>
<tr>
<td align="left">Shift-Right</td>
<td align="left">Shift-Right</td>
<td align="left">Select right</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-Left</td>
<td align="left">Option-Shift-Left</td>
<td align="left">Select word left</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-Right</td>
<td align="left">Option-Shift-Right</td>
<td align="left">Select word right</td>
</tr>
<tr>
<td align="left">Shift-Home</td>
<td align="left">Shift-Home</td>
<td align="left">Select line start</td>
</tr>
<tr>
<td align="left">Shift-End</td>
<td align="left">Shift-End</td>
<td align="left">Select line end</td>
</tr>
<tr>
<td align="left">Alt-Shift-Right</td>
<td align="left">Command-Shift-Right</td>
<td align="left">Select to line end</td>
</tr>
<tr>
<td align="left">Alt-Shift-Left</td>
<td align="left">Command-Shift-Left</td>
<td align="left">Select to line start</td>
</tr>
<tr>
<td align="left">Shift-Up</td>
<td align="left">Shift-Up</td>
<td align="left">Select up</td>
</tr>
<tr>
<td align="left">Shift-Down</td>
<td align="left">Shift-Down</td>
<td align="left">Select down</td>
</tr>
<tr>
<td align="left">Shift-PageUp</td>
<td align="left">Shift-PageUp</td>
<td align="left">Select page up</td>
</tr>
<tr>
<td align="left">Shift-PageDown</td>
<td align="left">Shift-PageDown</td>
<td align="left">Select page down</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-Home</td>
<td align="left">Command-Shift-Up</td>
<td align="left">Select to start</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-End</td>
<td align="left">Command-Shift-Down</td>
<td align="left">Select to end</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-D</td>
<td align="left">Command-Shift-D</td>
<td align="left">Duplicate selection</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-P</td>
<td align="left">---</td>
<td align="left">Select to matching bracket</td>
</tr>
</tbody>
</table><br>
<h2>Multicursor</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Ctrl-Alt-Up</td>
<td align="left">Ctrl-Option-Up</td>
<td align="left">Add multi-cursor above</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Down</td>
<td align="left">Ctrl-Option-Down</td>
<td align="left">Add multi-cursor below</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Right</td>
<td align="left">Ctrl-Option-Right</td>
<td align="left">Add next occurrence to multi-selection</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Left</td>
<td align="left">Ctrl-Option-Left</td>
<td align="left">Add previous occurrence to multi-selection</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Shift-Up</td>
<td align="left">Ctrl-Option-Shift-Up</td>
<td align="left">Move multicursor from current line to the line above</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Shift-Down</td>
<td align="left">Ctrl-Option-Shift-Down</td>
<td align="left">Move multicursor from current line to the line below</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Shift-Right</td>
<td align="left">Ctrl-Option-Shift-Right</td>
<td align="left">Remove current occurrence from multi-selection and move to next</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-Shift-Left</td>
<td align="left">Ctrl-Option-Shift-Left</td>
<td align="left">Remove current occurrence from multi-selection and move to previous</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-L</td>
<td align="left">Ctrl-Shift-L</td>
<td align="left">Select all from multi-selection</td>
</tr>
</tbody>
</table><br>
<h2>Go to</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Left</td>
<td align="left">Left, Ctrl-B</td>
<td align="left">Go to left</td>
</tr>
<tr>
<td align="left">Right</td>
<td align="left">Right, Ctrl-F</td>
<td align="left">Go to right</td>
</tr>
<tr>
<td align="left">Ctrl-Left</td>
<td align="left">Option-Left</td>
<td align="left">Go to word left</td>
</tr>
<tr>
<td align="left">Ctrl-Right</td>
<td align="left">Option-Right</td>
<td align="left">Go to word right</td>
</tr>
<tr>
<td align="left">Up</td>
<td align="left">Up, Ctrl-P</td>
<td align="left">Go line up</td>
</tr>
<tr>
<td align="left">Down</td>
<td align="left">Down, Ctrl-N</td>
<td align="left">Go line down</td>
</tr>
<tr>
<td align="left">Alt-Left, Home</td>
<td align="left">Command-Left, Home, Ctrl-A</td>
<td align="left">Go to line start</td>
</tr>
<tr>
<td align="left">Alt-Right, End</td>
<td align="left">Command-Right, End, Ctrl-E</td>
<td align="left">Go to line end</td>
</tr>
<tr>
<td align="left">PageUp</td>
<td align="left">Option-PageUp</td>
<td align="left">Go to page up</td>
</tr>
<tr>
<td align="left">PageDown</td>
<td align="left">Option-PageDown, Ctrl-V</td>
<td align="left">Go to page down</td>
</tr>
<tr>
<td align="left">Ctrl-Home</td>
<td align="left">Command-Home, Command-Up</td>
<td align="left">Go to start</td>
</tr>
<tr>
<td align="left">Ctrl-End</td>
<td align="left">Command-End, Command-Down</td>
<td align="left">Go to end</td>
</tr>
<tr>
<td align="left">Ctrl-L</td>
<td align="left">Command-L</td>
<td align="left">Go to line</td>
</tr>
<tr>
<td align="left">Ctrl-Down</td>
<td align="left">Command-Down</td>
<td align="left">Scroll line down</td>
</tr>
<tr>
<td align="left">Ctrl-Up</td>
<td align="left">---</td>
<td align="left">Scroll line up</td>
</tr>
<tr>
<td align="left">Ctrl-P</td>
<td align="left">---</td>
<td align="left">Go to matching bracket</td>
</tr>
<tr>
<td align="left">---</td>
<td align="left">Option-PageDown</td>
<td align="left">Scroll page down</td>
</tr>
<tr>
<td align="left">---</td>
<td align="left">Option-PageUp</td>
<td align="left">Scroll page up</td>
</tr>
</tbody>
</table><br>
<h2>Find/Replace</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Ctrl-F</td>
<td align="left">Command-F</td>
<td align="left">Find</td>
</tr>
<tr>
<td align="left">Ctrl-H</td>
<td align="left">Command-Option-F</td>
<td align="left">Replace</td>
</tr>
<tr>
<td align="left">Ctrl-K</td>
<td align="left">Command-G</td>
<td align="left">Find next</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-K</td>
<td align="left">Command-Shift-G</td>
<td align="left">Find previous</td>
</tr>
</tbody>
</table><br>
<h2>Folding</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Alt-L, Ctrl-F1</td>
<td align="left">Command-Option-L, Command-F1</td>
<td align="left">Fold selection</td>
</tr>
<tr>
<td align="left">Alt-Shift-L, Ctrl-Shift-F1</td>
<td align="left">Command-Option-Shift-L, Command-Shift-F1</td>
<td align="left">Unfold</td>
</tr>
<tr>
<td align="left">Alt-0</td>
<td align="left">Command-Option-0</td>
<td align="left">Fold all</td>
</tr>
<tr>
<td align="left">Alt-Shift-0</td>
<td align="left">Command-Option-Shift-0</td>
<td align="left">Unfold all</td>
</tr>
</tbody>
</table><br>
<h2>Other</h2><hr>
<table role="table">
<thead>
<tr>
<th align="left">Windows/Linux</th>
<th align="left">Mac</th>
<th align="left">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">Tab</td>
<td align="left">Tab</td>
<td align="left">Indent</td>
</tr>
<tr>
<td align="left">Shift-Tab</td>
<td align="left">Shift-Tab</td>
<td align="left">Outdent</td>
</tr>
<tr>
<td align="left">Ctrl-Z</td>
<td align="left">Command-Z</td>
<td align="left">Undo</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-Z, Ctrl-Y</td>
<td align="left">Command-Shift-Z, Command-Y</td>
<td align="left">Redo</td>
</tr>
<tr>
<td align="left">Ctrl-,</td>
<td align="left">Command-,</td>
<td align="left">Show the settings menu</td>
</tr>
<tr>
<td align="left">Ctrl-/</td>
<td align="left">Command-/</td>
<td align="left">Toggle comment</td>
</tr>
<tr>
<td align="left">Ctrl-T</td>
<td align="left">Ctrl-T</td>
<td align="left">Transpose letters</td>
</tr>
<tr>
<td align="left">Ctrl-Enter</td>
<td align="left">Command-Enter</td>
<td align="left">Enter full screen</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-U</td>
<td align="left">Ctrl-Shift-U</td>
<td align="left">Change to lower case</td>
</tr>
<tr>
<td align="left">Ctrl-U</td>
<td align="left">Ctrl-U</td>
<td align="left">Change to upper case</td>
</tr>
<tr>
<td align="left">Insert</td>
<td align="left">Insert</td>
<td align="left">Overwrite</td>
</tr>
<tr>
<td align="left">Ctrl-Shift-E</td>
<td align="left">Command-Shift-E</td>
<td align="left">Macros replay</td>
</tr>
<tr>
<td align="left">Ctrl-Alt-E</td>
<td align="left">---</td>
<td align="left">Macros recording</td>
</tr>
<tr>
<td align="left">Delete</td>
<td align="left">---</td>
<td align="left">Delete</td>
</tr>
<tr>
<td align="left">---</td>
<td align="left">Ctrl-L</td>
<td align="left">Center selection</td>
</tr>
</tbody>
</table><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <label class="settings" title="Settings" type="button" data-toggle="modal" data-target="#exampleModalCenter">
      <img src="data/settings.png" style=" width: 70%; margin-left: 15%; margin-bottom: 10%;">
    </label>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Key Bindings</h5><hr>
            <ul class="nav nav-tabs" id="keyBind" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" value="" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-selected="true">Ace</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" value="ace/keyboard/vim" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-selected="false">Vim</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" value="ace/keyboard/emacs" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-selected="false">Emacs</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="contact-tab" value="ace/keyboard/sublime" data-toggle="tab" href="#contact" role="tab" aria-selected="false">Sublime</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="contact-tab" data-toggle="tab" value="ace/keyboard/vscode" href="#contact" role="tab" aria-selected="false">VScode</button>
              </li>
            </ul>
            <br>

            <h5>Soft Wraps</h5><hr>
            <ul class="nav nav-tabs" id="softWrap" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" value="off" aria-selected="true">Off</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" value="free" aria-selected="false">View</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" value="margin" aria-selected="false">Margin</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" value="40" aria-selected="false">40</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" value="80" aria-selected="false">80</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" value="120" aria-selected="false">120</button>
              </li>
            </ul>
            <br>

            <h5>Autocomplete: <input type="checkbox" id="autocomplete" checked=""></h5>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>




</nav>

	<div class = "main" id="editor"></div>
    <br>
    <br>
    <br>
    <div class="input">
      <div style="width: 50%;">
        <input type="checkbox" name="CustomInput" id="CustomInput">Custom Input<br>
        <textarea type="text" name="input" class="outputtxt" placeholder="Type your input here" id="InputTestCase"></textarea>
      </div>
           <div class="btnn">
            <div class="buttonn rounded failuree" id="submitCode"><span class="btntext">Submit Code</span></div>
            <div class="buttonn rounded successs" id="runcodecustom"><span class="btntext">Run Code</span></div>
           </div>
         </div>
<br>
<br>
  <div class="row" id="outputDisplayer">
  <a href="#output"></a>
<div class="col-1"></div>
  <div class="col-10">
    <div id="output" class="row">
        <div id="TestCaseOption">
          <div class = "testCase fail" href="#">Question 01</div>
              <div class = "testCase pass" href="#">Question 02</div>
              <div class = "testCase pass" href="#">Question 03</div>
        </div>

    <div id="outputContainer">
      <div class="row">
        <h5>Input: </h5>
      </div>
      <div class="row">
        <div id="editorinput"></div>
      </div>
      <div class="row">
        <h5>Your Output</h5>
      </div>
      <div class="row">
        <div id="editorYourOutput"></div>
      </div>
      <div class="row">
        <h5>Expected Output</h5>
      </div>
      <div class="row">
        <div id="editorExpectedOutput"></div>
      </div>
      
    </div>
  </div>
</div>
    
<script src="/Ace/ace.js" type="text/javascript" charset="utf-8"></script>


<script src="/Ace/ext-language_tools.js"></script>

</div>
<br>
<br>

				
	
	<div class="col-4"></div>
</div>
</div>


<center>
<div id="app"></div>
</center>
<svg width="97%" height="95" style="z-index: 1; position:fixed; bottom:0;" viewBox="0 0 1436 115" fill="none" xmlns="http://www.w3.org/2000/svg">
<g filter="url(#filter0_d)">
<path d="M594 18.353C470 39.7132 137.333 114.983 4 116H1432C1432 116 1074 62.5993 908 27.5075C742 -7.58434 743.341 -7.37245 594 18.353Z" fill="#373838"/>
</g>
<defs>
<filter id="filter0_d" x="0" y="0" width="1436" height="124" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
<feFlood flood-opacity="0" result="BackgroundImageFix"/>
<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
<feOffset dy="4"/>
<feGaussianBlur stdDeviation="2"/>
<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
</filter>
</defs>
</svg>

<script type="text/javascript" src="challenge.js"></script>
    </body>
</html>
