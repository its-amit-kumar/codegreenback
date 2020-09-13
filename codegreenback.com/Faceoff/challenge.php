
<?php


require_once dirname(__DIR__,1).'/core/init.php';


// $id = $_GET['id'];

// $typeOfQuestion = $_GET["typeOfQuestion"];

$challengeid = $_GET['cid'];

$user = $_SESSION['user'];

//$question = new Ques($id);

//$ques = $question->get_question();

if(Session::exists(Config::get('session/session_name')))
{
	$user = Session::get(Config::get('session/session_name'));
	$obj = new Challenge3($challengeid, $user);

	$status = $obj->check();
	if($status['status'])
	{
		// print_r($status);
	}
	else
	{
		Redirect::to('Home');
		exit();
	}


}
else
{
	Redirect::to('');
	exit();
}




?> 

<script>
	const start_time = <?php echo $status['start_time']; ?>
</script>


<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<title>Face/Off</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="challenge.css?id=<?php  echo random_int(1,100000); ?>">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


</head>
<body>


	<!-- <input type="hidden" id="qid" value="<?php //echo $id?>"> -->
<input type="hidden" name="coderun" id="coderun" value ="<?php echo Token::generate('coderun');  ?>">
<input type="hidden" id="user" value="<?php echo $_SESSION['user']?>">
<input type="hidden" id="challengeid" value="<?php echo $challengeid?>">



<div id="starter_loader" style="width:100%; height: 100%; display:flex; justify-content:center; align-items:center;" >

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


<div class='c3-msg-div' style="display:none">
	<div style="color:white">
		<h1 id="c3-challenge-msg"></h1>
	</div>
	<div>
		<img src="" alt="status-gif" id="c3-challenge-img">
	</div>
</div>


<div class='main-body' style="display:none">

	    <div class="challenge-header">
        <div class="cgb-logo">
            <img src="https://www.codegreenback.com/public/img/1.png" alt="">
        </div>

        <div class="oppo-1">
            <p style="color: rgb(0, 0, 0);" id="player-1"></p>
        </div>

        <div class="challenge-vs">
            <img src="https://www.codegreenback.com/public/img/versus.png" alt="">
        </div>

        <div class="oppo-2">
            <p style="color: rgb(0, 0, 0);" id="player-2"></p>
        </div>


            <div class="challenge-cc-img">
                <img src="https://www.codegreenback.com/public/img/cclogo.jpeg" alt="">
            </div>

            <div class="challenge-cc-bet">
                <p id="cc_bet"></p>
            </div>
      

    </div>

	<div class="alert-displayer" style='margin-top:2em'>

		
		<h3 style="padding-left: 10px; padding-top: 7px;">Opponent Status</h3>

		<div id="container-typing" style="display: none;"  class='opponent-status' >
			<div id="ball-1" class="circle-type"></div>
			<div id="ball-2" class="circle-type"></div>
			<div id="ball-3" class="circle-type"></div>
		</div>	

		<div id="container-not-typing" class='opponent-status'>
			<div class="circle-type"></div>
			<div class="circle-type"></div>
			<div class="circle-type"></div>
		</div>
		
		<div id="container-code-running" class='opponent-status' style="display: none;">
			<img src="/public/img/coderunning.gif" alt="coderunning" width='50px' height="40px">
		</div>

		<div id="container-code-error" class='opponent-status' style="display: none;">
			<img src="/public/img/codeerror.gif" alt="error" width='120px'>
		</div>

		<div id="container-code-success" class='opponent-status' style="display: none;">
			<img src="/public/img/codesuccess.gif" alt="success" width="120vw">
		</div>
		
		
	</div>
		

	<input type="hidden" name="coderun" id="coderun" value ="<?php echo Token::generate('coderun');  ?>">


	<div class="d-flex justify-content-center" style="margin-top: 20vh;">

		<div class="col-8"  id="questionDisplayer">
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
		
	</div>


	<br>


	<div class="dropdown row" id = "selector">
		<div class="col-2"></div>
		<div class="col-8">

			<a href="#l" hidden="hidden"></a>
			
			<nav class="navbar navbar-dark bg-dark rounded" id="textEditorNavbar">
		<h5 class="text-white">Language:</h5>
		
		<select id="LanguageSelector" class="LanguageSelector rounded highlight">
			<option value="" selected disabled hidden>Choose here</option> 
			<option value="python" id="python">Python</option> 
			<option value="cpp" id="cpp">C++</option> 
			<option value="c" id="c">C</option> 
			<option value="java" id="java">Java</option> 
			<option value="ruby" id="ruby">Ruby</option>
			<option value="swift" id="swift">Swift</option>
			<option value="javascript" id="javascript">Javascript</option>
			<option value="bash" id="bash">Bash</option>
			<option value="haskell" id="haskell">Haskell</option>
			<option value="erlang" id="erlang">Erlang</option>
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
	</div>
	</div>
	<div class="col-2"></div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<div class="row">
		<div class="col-2"></div>
		<div class="col-8 main">
		<div id="editor"></div>
	</div>
	<div class="col-2"></div>
	</div>

		
	<script src="Ace/ace.js" type="text/javascript" charset="utf-8"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.js"
	integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<div class="row" id="edit">
		<div class="col-2"></div>
		<div class="col-8">
			<div class="row">
				<div>
					
				</div>
			</div>
			<br>
			<div class="row input">
				<div class="col-8" id="input">
					<input type="checkbox" name="CustomInput" id="CustomInput">Custom Input<br>
					<textarea type="text" name="input" class="outputtxt" placeholder="Type your input here" id="InputTestCase"></textarea>

				</div>
				<div class="col-4">
					<div class="row">
						<div class="col-6">
							<div class="buttonn rounded successs" id="runcodecustom"><span class="btntext">Run Code</span></div>
						</div>
						<div class="col-6">
							<div class="buttonn rounded failuree" id="submitCode"><span class="btntext">Submit Code</span></div>
						</div>
						<div class="row">
						</div>
						
					</div>

				
				</div>
			</div>
			
		</div>
		<div class="col-2"></div>
	</div>
	<br>
	<br>
		<div class="row" id="outputDisplayer">
		<a href="#output" hidden="hidden"></a>
		<div class="col-2"></div>
		<div class="col-8">
			<div id="output" class="row">
					<div id="TestCaseOption">
						<div class = "testCase fail" href="#">Question 01</div>
						<div class = "testCase pass" href="#">Question 02</div>
						<div class = "testCase pass" href="#">Question 03</div>
					</div>

			<div class="col-9" id="outputContainer">
				<div>
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
	<br>
	<br>

					
		
		</div>
		<div class="col-4"></div>
	</div>


</div>


<script type="text/javascript" src="challenge.js?id=004"></script>
</body>
</html>
