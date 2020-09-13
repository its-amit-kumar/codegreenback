<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
  Redirect::to('index');
}



$id = $_GET['task_id'];

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

?>

<!DOCTYPE html>
<html>
<head>
  <title>Responsive Animated Sidebar Menu</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="public/css/attemptEvent.css">
  <script src="Ace/ace.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" src="public/js/attemptEvent.js"></script>
</head>
<body>
  
    <input type="hidden" id = "task_id" value="<?php echo $id; ?>">
    <input type="hidden" id = "user" value="<?php echo $_SESSION['user']; ?>">

  <input type="hidden" id="challengeId" value="<?php echo $id?>">
    <input type="hidden" id="type" value="event">
        <div id="mySidenav" class="sidenav">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
          <button id="refresh">Refrest</button>
          <div style="font: 20px; color: white; display: flex; flex-direction: row; margin-top: 11px; margin-bottom: 11px;">
            <div>Submissions</div>
            <div style="color: black; background: white; margin-left: 3%; letter-spacing: 2px;border-radius: 3px 3px 3px 3px;" id="submissions">1524</div>
          </div>
          <div style="font: 20px; color: white; display: flex; flex-direction: row;  margin-top: 11px; margin-bottom: 11px;">
            <div>Successful Submissions</div>
            <div style="color: black; background: white; margin-left: 3%; letter-spacing: 2px; border-radius: 3px 3px 3px 3px;" id="ssubmissions">829</div>
          </div>
          <h2 style="color: white; margin-top: 10px; margin-left: 12px;">RANK</h2>
          <div style="font: 20px; color: white; display: flex; flex-direction: row;  margin-top: 11px; margin-bottom: 11px; margin-left: 12px;">
            <div>Your Rank</div>
            <div style="color: black; background: white; margin-left: 12px; letter-spacing: 2px; border-radius: 3px 3px 3px 3px;" id = "yourRank">. . .</div>
          </div>
          <div id="ranking"></div>
        </div>

  <div class="options">
    <?php
    require_once "timer.php";
    ?>
  </div>

  <div class="info">
    <div id="liveDetails" style="font-size:30px;cursor:pointer;position: fixed; right: 0; top: 40vh; width: 2%; height: 19vh; border: 1px solid black;  border-radius: 15px 0px 0px 15px; background-color: #81ed68;" onclick="openNav()">
      <div style="margin-left: 20%">
        R
      </div>
      <div style="margin-left: 20%">
        A
      </div>
      <div style="margin-left: 20%">
        N
      </div>
      <div style="margin-left: 20%">
        K
      </div>
    </div>
    <div style="width: 80%; margin-top: 5%">
      <h1 class="name">TITLE OF EVENT</h1><hr>
      <br>
      <dir style="width: 80%; margin-top: 5%; margin-left: 10%;">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
      </dir>
    </div>
  </div>
  <div class="attemptQuestion" hidden="hidden">
    <div id="liveDetails" style="font-size:30px;cursor:pointer;position: fixed; right: 0; top: 40vh; width: 2%; height: 19vh; border: 1px solid black;  border-radius: 15px 0px 0px 15px; background-color: #81ed68;" onclick="openNav()">
      <div style="margin-left: 20%">
        R
      </div>
      <div style="margin-left: 20%">
        A
      </div>
      <div style="margin-left: 20%">
        N
      </div>
      <div style="margin-left: 20%">
        K
      </div>
    </div>
  <div style="width: 80%; margin-top: 5%"><h1 class="title" id="title">TITLE</h1><hr>
  <div class="content" id="statement">
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit rhoncus nulla eu consequat. Donec rhoncus, libero quis viverra faucibus, lectus mauris gravida erat, tincidunt aliquet odio lorem at nibh. Morbi quis sapien odio. Aliquam id consequat leo, fringilla molestie ipsum. Maecenas fringilla diam vitae lacus sagittis maximus. Cras blandit libero ut nisi placerat hendrerit. Maecenas magna massa, luctus eget ante et, volutpat hendrerit massa.
  </div>
  <h1 class="Title">INPUT FORMAT</h1><hr>
  <div class="content" id="if">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <h1 class="Title">OUTPUT FORMAT</h1><hr>
  <div class="content" id="of">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <h1 class="Title">CONSTRAIN</h1><hr>
  <div class="content" id="constrain">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <h1 class="Title">SAMPLE INPUT</h1><hr>
  <div class="content" id="sampleI">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>
  <h1 class="Title">SAMPLE OUTPUT</h1><hr>
  <div class="content" id="sampleO">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </div>

  <br>
  <br>
  <input type="hidden" id="qid" value="">
  <input type="hidden" name="coderun" id="coderun" value ="<?php echo escape(Token::generate('coderun'));  ?>">
  <nav class="navbar navbar-dark bg-dark rounded" id="textEditorNavbar">
  <h5 class="text-white">Language:</h5>
  
  <select id="LanguageSelector" class="LanguageSelector rounded highlight">
    <option value="" selected disabled hidden>Choose here</option> 
        <option value="python" id="python">Python</option> 
        <option value="cpp" id="cpp">C++</option> 
        <option value="cpp" id="cpp">C</option> 
        <option value="java" id="java">Java</option> 
        <option value="ruby" id="ruby">Ruby</option> 
        <option value="bash" id="bash">Bash</option> 
        <option value="erlang" id="erlang">Erlang</option> 
        <option value="golang" id="golang">Golang</option> 
        <option value="haskell" id="haskell">Haskell</option> 
        <option value="javascript" id="javascript">Javascript</option> 
        <option value="swift" id="swift">Swift</option> 
    </select> 


    <h5 class="text-white">Theme:</h5>
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
    <h5 class="text-white">Text Size:</h5>
    <input type="number" class="LanguageSelector rounded" value="18" min="10" max="50" id="textsize">


  </nav>
  <div id="editor"></div>
  <br>
  <br>
  <div class="runOptions">
    <input type="checkbox" name="CustomInput" id="CustomInput"><div style="padding-left: 5px; padding-right: 5px;">Custom Input</div>
    <br>
        <textarea type="text" name="input" class="outputtxt" placeholder="Type your input here" id="InputTestCase"></textarea>
        <div class="executeOption">
          <div class="buttonn rounded successs" id="runcodecustom"><span class="btntext">Run Code</span></div>
      
          <div class="buttonn rounded failuree" id="submitCode"><span class="btntext">Submit Code</span></div>
        </div>
        <br>
  </div>
  <div id="outputDisplayer" class="row">
          <a href="#output" hidden="hidden"></a>
          <div id="output"></div>
        </div>
</div>
</div>
</body>
</html>

