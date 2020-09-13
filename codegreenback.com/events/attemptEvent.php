<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


$id = $_GET['eid'];
if(!isset($_GET['qNo'])){
  $qNo = 1;
}
else{
  $qNo = $_GET['qNo'];
}

$user = new User();
if(!$user->isLoggedIn()){
        Redirect::to('');
}




$mdb = new mongo("Events");
$res = $mdb->get($id,array("type" => "details"))[0];
$title = $res['title'];
$instructions = $res['instruction'];
$event = new event($id);



if (!$event->started()) {
    if($event->ended()){
        echo "<h1>The event has ended</h1>";
        exit();
    }

  echo "<h1>The event is yet to start</h1>";
  exit();
}

if(!$event->isUserRegistered()){
  echo "<h1>SORRY! YOU DID NOT REGISTER FOR THIS EVENT!</h1>";
  exit();
}

if(!$event->isStartedByUser()){
  if(!$event->insertStarted()){
    echo "error";
    exit();
  }
}

$count = count($res['questions']);

?>












<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Attempt Event</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/events/public/css/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://use.fontawesome.com/4414b550a0.js"></script>
</head>
<body>
<input type="hidden" id = "eid" value="<?php echo $id; ?>">
<input type="hidden" id = "qNo" value="<?php echo $qNo; ?>">
<input type="hidden" id = "user" value="<?php echo $_SESSION['user']; ?>">
<!-- partial:index.partial.html -->

<nav class="sidenav">
  <ul class="sidebar-nav">
    <ul class="main-buttons">

      <li>
        <i class="fa fa-question fa-2x"></i>
        Questions
        <ul class="hidden">
          <?php
          for($i = 0; $i < $count; $i++){
            if($qNo == $i+1){
              echo "<a href = 'attemptEvent.php?eid=".$id."&qNo=".($i+1)."'><li class='ques selected-question' value='".($i+1)."'>Question ".($i+1)."</li></a>";
            }
            else{
              echo "<a href = 'attemptEvent.php?eid=".$id."&qNo=".($i+1)."'><li class='ques' value='".($i+1)."'>Question ".($i+1)."</li></a>";
            }
          }
          ?>
        </ul>
      </li>
      <li>
        <i class="fa fa-trophy fa-2x" aria-hidden="true"></i>
        LEADER BOARD
        <ul class="hidden rank">
	<li><h2>LIVE RANK</h2></li>

        </ul>
      </li>
      <li>
      <i class="fa fa-tachometer fa-2x" aria-hidden="true"></i>
          Your status
         <ul class="hidden">
          <li id='your-rank'>Rank  -  </span></li>
          <li id = "submissions">Submissions  - </li>
          <li id="successful-submissions">Successful -  </li>
          <div class="container">
    
            <ul style="color:aqua;">
              <li><span id="hours"></span>Hours</li>
              <li><span id="minutes"></span>Minutes</li>
              <li><span id="seconds"></span>Seconds</li>
            </ul>
        
          </div>
        </ul>
      </li>
    </ul>
  </ul>
</nav>
<div class="container1">
  <div class="editorNav">
    <select id="LanguageSelector" class="LanguageSelector rounded highlight">
      <option value="" selected="" disabled="" hidden="">Language</option> 
          <option value="python" id="python">Python</option> 
          <option value="cpp" id="cpp">C++</option> 
          <option value="c" id="cpp">C</option> 
          <option value="java" id="java">Java</option> 
          <option value="bash" id="bash">Bash</option> 
          <option value="erlang" id="erlang">Erlang</option> 
          <option value="haskell" id="haskell">Haskell</option> 
          <option value="javascript" id="javascript">Javascript</option> 
          <option value="ruby" id="ruby">Ruby</option> 
          <option value="swift" id="swift">Swift</option> 
      </select> 
      <input type="number" class="LanguageSelector rounded" value="18" min="10" max="50" id="textsize">
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
    <div id="run-code" class = "run" disabled="disabled">
      RUN
    </div>
    <div id="submit-code" class = "submit">
      SUBMIT
    </div>
    <span class = "custom"><input type="checkbox" id = "CustomInput" value="value" id="customBox"><label for="customBox">&nbsp;&nbsp;Custom</label></span>
    <div class="txoption">
      <label class="settings" title="Settings" type="button" data-toggle="modal" data-target="#exampleModalCenter">
        <img class="navImg" src="/events/public/img/settFinal.png">
      </label>
      <label class="downloadCode" title="Download this code." id="download">
        <img class="navImg" src="/events/public/img/downloadFinal.png">
      </label>
      <label for="inputfile" class="uploadCode" title="Upload your code.">
        <img class="navImg" src="/events/public/img/uploadFinal.png">
        <input type="file" name="inputfile" id="inputfile" style="display:none;">
      </label>
      <label class="deleteCode" title="Clear this code.">
        <img class="navImg" src="/events/public/img/deleteFinal.png">
      </label>
      <label class="keyBinding" type="button" data-toggle="modal" data-target="#exampleModalLong" title="Short-cut Keys.">
        <img class="navImg" src="/events/public/img/infoFinal.png">
      </label>
    </div>
  </div>
  <div class="contentParent">

    <div class="content"  id="questionDisplayer">
      <h1 id="title">
        Title of the question
      </h1>
      <br>
      <h1 id="Title">
        QUESTION STATEMENT
      </h1>
      <div id="statement" class="text">
        
      </div>
      <br>
      <h1 id="Title">
        INPUT FORMAT
      </h1>
      <div id="if" class="text">
        
      </div>
      <br>
      <h1 id="Title">
        OUTPUT FORMAT
      </h1>
      <div id="of" class="text">
        
      </div>
      <br>
      <h1 id="Title">
        CONSTRAIN
      </h1>
      <div id='constrain' class="text">
        
      </div>
      <br>
      <br>
      <h1 id="Title">
        SAMPLE INPUT
      </h1>
      <div class="rounded">
        <div id='sampleI' class="text">
        
        </div>
      </div>
      <br>
      <h1 id="Title">
        SAMPLE OUTPUT
      </h1>
      <div class="rounded">
        <div id='sampleO' class="text">
          
        </div>
      </div>
      <br>
      <br>
      <br>
      <br>
      <br>
    </div>
    <div class ="dragbar"></div>
    <div class = "main" id="editor"></div>
  </div>
</div>
<br>
  <br>
  <div class="outputDisplayer">
    <div class="toggle-output">Open Output</div>
    <div class="out">
    <textarea class="custom-input" placeholder="Input here"></textarea>
    <div id="custom-output"></div>
  </div>
  </div>





  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background : #363636; color: white;">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Key Bindings</h5><hr>
            <ul class="nav nav-tabs" id="keyBind" role="tablist" style="font-size : 25px;">
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
            <ul class="nav nav-tabs" id="softWrap" role="tablist" style="font-size : 25px;">
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


    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background : #363636; color: white;">
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




<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="/Ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/Ace/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<script src="/events/public/js/index.js" type="text/javascript" >

</script>
</body>
</html>
