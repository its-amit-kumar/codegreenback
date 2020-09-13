
<?php

// require_once 'FetchQuestion.php';

require_once dirname(__DIR__,1).'/core/init.php';


$id = $_GET['id'];

$typeOfQuestion = $_GET["typeOfQuestion"];

$challengeid = $_GET['challengeid'];

$username = $_GET['username'];

//$question = new Ques($id);

//$ques = $question->get_question();

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index');
}


?> 




<!DOCTYPE html>
<html lang="en">
<head>
<title>CGB-RUSHOUR</title>
<style type="text/css" media="screen">
#editor { 
        position: absolute;
        top: 20px;
        right: 0;
        bottom: 0;
        left: 0;
    }

.main{
height: 700px;

}
.outputtxt{
width: 60%;
height: 100px;
border: 2px solid #ada9a8;

}
#output{

	padding-top: 10px;
	padding-right: 0px;

}

#input{

	padding-top: 10px;
	padding-left: 0px;
	width:100px;
}

.input{
	height: 100px;
}

#options{

	margin: 25px;

}
#outputdiv{
	height: 150px;
	width: 60%;
}
#CustomInput{
	padding-top: 3px;
}

.LanguageSelector {
    border: 1px solid #d4c4c3;
    width: 150px;
    background:#f2f2f2;
    height: 25px;
}
.ThemeSelector {
    border: 1px solid #d4c4c3;
    width: 150px;
    background:#f2f2f2;
    height: 25px;
}
.LanguageSelector:hover{
	cursor: pointer;
}

.ThemeSelector:hover{
	cursor: pointer;;
}

#LanguageOption{
   background: #f2f2f2;
}

#title{
	font-family: 'Montserrat', sans-serif;
	font-weight: 600;
	font-size: 55px;
	color: #5e5e5e;
}

#Title{
	font-family: 'Open Sans', sans-serif;
	font-weight: 400;
	font-size: 20px;
	color: #3d3d3d;
}

#questionDisplayer{
	box-shadow: 5px 10px 13px #888888;
}

#statement{
	padding-left: px;
	padding-right: 5px;
}


.text{
	font-family: 'Roboto', sans-serif;
	font-weight: 300;
	font-size: 15px;
}

#textEditorNavbar{
	box-shadow: 5px 10px 13px #888888;
}
#editor{
	box-shadow: 5px 10px 13px #888888;
}
.sample{
	background: #e3e3e3;
}
.buttonn{
    height: 50px;
    width: 200px;
    
    text-align: center;
    line-height: 50px;
    font-weight: bold;
    font-size: 20px;
    letter-spacing: 1px;
    box-shadow: 5px 10px 13px #888888;
}

.successs{
    background: green;
    color: white;
}

.failuree{
    background: #575757;
    color: white;
}

.buttonn:hover{
    cursor: pointer;
}

.buttonn:active{
    box-shadow: 2px 4px 5px #888888;
}

.btntext{

    display: inline-block;
    vertical-align: middle;
    line-height: normal;
    

}

#loader {
  margin-left: auto;
  margin-right: auto;
  display: block;
  margin-top: auto;
  margin-bottom: auto;
  /* vertical-align: middle; */
}

.highlight{
	border: 10px solid #ff1100;
	--box-shadow-color: #ff1100;
	box-shadow: 0 0 25px var(--box-shadow-color);

}

.timer{
	position: fixed;
	left: 1650px;
	top:50px;
}

#demo{

	box-shadow: 5px 10px 13px #888888;

}


#top, #bottom, #left, #right {
	position: fixed;
	}
	#left, #right {
		top: 0; bottom: 0;
		width: 5px;
		}
		#left { left: 0; }
		#right { right: 0; }
		
	#top, #bottom {
		left: 0; right: 0;
		height: 5px;
		}
		#top { top: 0; }
		#bottom { bottom: 0; }



</style>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../public/css/test.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>


	<div id="left"></div>
	<div id="right"></div>
	<div id="top"></div>
	<div id="bottom"></div>




<input type="hidden" name="coderun" id="coderun" value ="<?php echo Token::generate('coderun');  ?>">
	<div class="timer">
		<?php 
		$cid = $challengeid;
		require_once "AttemptChallenge.php" 
		?>
	</div>
<div class="d-flex justify-content-center">
	<div class="col-8">
		<h1 id="title">
		</h1>
		<hr>
	</div>
</div>
<div class="d-flex justify-content-center">

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
        <option value="cpp" id="cpp">C</option> 
        <option value="java" id="java">Java</option> 
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
  <script>
  	var lang="";
  </script>
  <!--
<script>jQuery("#python").click(function(e){
    editor.session.setMode("ace/mode/python");
    lang = "python";
//do something
e.preventDefault();
});
</script>
<script>jQuery("#java").click(function(e){
    editor.session.setMode("ace/mode/java");
    lang = "java";
//do something
e.preventDefault();
});
</script>
<script>jQuery("#cpp").click(function(e){
    editor.session.setMode("ace/mode/c_cpp");
    lang = "cpp";
//do something
e.preventDefault();
});
</script>
<script>
$(document).ready(function(){
  $(".dropdown-toggle").dropdown();
});

</script>
-->

<script type="text/javascript">


function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

	function getFormData(object) {
    const formData = new FormData();
    Object.keys(object).forEach(key => formData.append(key, object[key]));
    return formData;
	}


	

	var e = document.getElementById("LanguageSelector");
	e.addEventListener("change",function(){
		var strUser = e.options[e.selectedIndex].value;
		if(strUser == "python"){
			editor.session.setMode("ace/mode/python");
    		lang = "python";
		}
		if(strUser == "cpp"){
			editor.session.setMode("ace/mode/c_cpp");
    		lang = "cpp";
		}
		if(strUser == "java"){
			editor.session.setMode("ace/mode/java");
    		lang = "java";
		}
	});



</script>


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

    
<script  type="text/javascript"  src="../ace-builds-master/src-noconflict/ace.js" charset="utf-8"></script>
<script>
	var codeid = "<?php echo $id ?>";
    var editor = ace.edit("editor");
    $(document).ready(function(){
		$.ajax({ url: "getlastsavedcode.php",
        	data : {quesid : "<?php echo $id ?>", user:"<?php Session::get(Config::get('session/session_name'))?>"},
        	type : "post",
        	success: function(info){
           		editor.setValue(info);
        	}});
});
    ace.config.set('basePath', '/Ace');
    ace.config.set('themePath', '/Ace');
    editor.setTheme("ace/theme/twilight");
    var m = document.getElementById("textsize");
    editor.setFontSize(m.value+"px");
    
	m.addEventListener("change",function(){
		var a = m.value;
		editor.setFontSize(a+"px");
		
	});

	document.getElementById("ThemeSelector").addEventListener("change",function(){
		editor.setTheme("ace/theme/"+document.getElementById("ThemeSelector").value);
	});



	setInterval(function(){
			if(editor.getValue()!=""){
			var codesave = {code : editor.getValue(), qid : "<?php echo $id; ?>", user:"<?php echo $_SESSION['user']?>"};
		}
			else{
			var codesave = {code : "NOCODE", qid : "<?php echo $id; ?>", user:"<?php echo $_SESSION['user']?>"};

			}
		 //codesave1 = getFormData(codesave);

		$.ajax({
					url: 'savecode.php',
				    type: 'POST',   
				    data: codesave
			});
		//console.log(getCookie('code5'));
	}
	,20000);


</script>

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
	<div class="col-6">
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
	<script type="text/javascript">
	var code1 = document.getElementById("runcodecustom");
	var code2 = document.getElementById("submitCode");
	var codes;
	var code_obj = {
	};
	//code.addEventListener("click",function(){
	//	 codes = editor.getValue();
	
	code1.addEventListener("click",function(){
		if (CustInput.checked) {

			code_obj = {
			runType : "runcode",
			CustomInput :true,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : <?php echo $id ?>,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			challengeid : "<?php echo $challengeid?>"
			//codes = editor.getValue();
		};

		}
		else {

			  code_obj = {
			runType : "runcode",
			CustomInput : false,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : <?php echo $id ?>,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			challengeid : "<?php echo $challengeid?>"
			//codes = editor.getValue();
	};

		}
		
	});
	code2.addEventListener("click",function(){
		

			code_obj = {
				runType : "submitCode",
			CustomInput :false,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : <?php echo $id ?>,
			input : false,
			coderun : document.getElementById('coderun').value,
			challengeid : "<?php echo $challengeid?>"
			//codes = editor.getValue();

		}
		
	});
	//code.addEventListener("click",function(){
	//		codes = JSON.stringify(code_obj);
	//});


	

	var form_data;
	code1.addEventListener("click",function(){

		form_data = getFormData(code_obj);

	});

	code2.addEventListener("click",function(){

		form_data = getFormData(code_obj);

	});




	code2.addEventListener("click",function(){

		var result = [];
		if(lang!="")

			{$("#outputDisplayer").show();
			
						$("#output").empty();
			
						$("#output").append("<img id='loader' src='../data/loading.gif'>");
			
						document.querySelector("#output").scrollIntoView({
				            behavior: 'smooth'
				        });
			
			
			
					$.ajax({  
					url: 'inputchallenge.php',
				    type: 'POST',  
				    //dataType:'JSON',
				    //contentType: 'application/json; charset=utf-8', 
				    data: form_data,
				    //async : true,
				    processData: false,
				    contentType: false, 
				    success: function(text) {
				    	data = JSON.parse(text);
				    		console.log(data);
				    	
				    if(data["runtype"]=="submitrun"){
				    	   	
				    		var result = []
				    		var str = "";
				    		$("#output").empty();
				    		for (var i = 1; i <= data["NoOfTestCases"]; i++) {
				    			if(data[i]["success"]=="true"){
				    				result[i] = {"title":"Passed","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
				    				if(i==1){
				    					str+="<div id='TestCaseOption'><div class = 'testCase pass' name='1' href='#'>TestCase 1</div>";
				    				}
				    				if(i!=1 && i!=data["NoOfTestCases"]){
				    					str+="<div class = 'testCase pass' name='"+i+"' href='#'>TestCase "+i+"</div>";
				    				}
				    				if(i==data["NoOfTestCases"]){
				    					str+="<div class = 'testCase pass' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
				    				}
				    			}
				    			if(data[i]["success"]=="false"){
				    				result[i] = {"title":"Wrong Answer","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
				    				if(i==1){
				    					str+="<div id='TestCaseOption'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
				    				}
				    				if(i!=1 && i!=data["NoOfTestCases"]){
				    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div>";
				    				}
				    				if(i==data["NoOfTestCases"]){
				    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
				    				}
				    			}
				    			if(data[i]["success"]=="error"){
				    				if(data[i]["errorType"]=="compilationErr"){
				    					result[i] = {"title":"Compilation error","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
				    				}
				    				if(data[i]["errorType"]=="timeLimit"){
				    					result[i] = {"title":"Time Limit exceeded","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
				    				}
				    				if(i==1){
				    					str+="<div id='TestCaseOption'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
				    				}
				    				if(i!=1 && i!=data["NoOfTestCases"]){
				    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div>";
				    				}
				    				if(i==data["NoOfTestCases"]){
				    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
				    				}
				    			}
				    		}
				    		$("#output").append(str);
				    		if(data["NoOfTestCases"]==data["NoOfPassedTestCases"]){
				    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>ALL PUBLIC TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
				    		}
				    		if(data["NoOfTestCases"]>data["NoOfPassedTestCases"] && data["NoOfPassedTestCases"]!=0){
				    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>SOME TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
				    		}
				    		if(data["NoOfPassedTestCases"]==0){
				    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>NONE OF THE TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
				    		}
			
				    		var editorinput = ace.edit("editorinput");
						    editorinput.setFontSize("14px");
						    editorinput.setReadOnly(true);
						    var editorYourOutput = ace.edit("editorYourOutput");
						    editorYourOutput.setFontSize("14px");
						    editorYourOutput.setReadOnly(true);
						    var editorExpectedOutput = ace.edit("editorExpectedOutput");
						    editorExpectedOutput.setFontSize("14px");
						    editorExpectedOutput.setReadOnly(true);
						    var addid = 'active';
							var $cols = $('.testCase').click(function(e) {
								//$cols.removeAttr("class","fail");
								//$cols.removeAttr("class","pass");
							    $cols.removeAttr("id","active");
							    $(this).attr("id","active");
							    var b = $(this).attr("name");
							    $("#testcaseinfotitle").empty();
							    $("#testcaseinfotitle").append(result[b]["title"]);
							    editorinput.setValue(result[b]["input"]);
							    editorinput.clearSelection();
							    editorExpectedOutput.setValue(result[b]["ExpectedOutput"]);
							    editorExpectedOutput.clearSelection();
							    editorYourOutput.setValue(result[b]["output"]);
							    editorYourOutput.clearSelection();
							});
			
			
			
				    	}
				    	
				    	//document.getElementById("outputdiv").innerHTML = obj1.output;
			
				    	//$.get('input.php')
				//.then(function(data){
					//data1 = JSON.parse(data)
				//});
				    	
				    },
				    error: function(){
				    	alert('error!');
				    }
					});}

else{

	$("LanguageSelector").attr("class","highlight");

	document.getElementById("LanguageSelector").scrollIntoView({
		            behavior: 'smooth',
		            inline: "center",
		            block: "center"
		        });



}

	});


	code1.addEventListener("click",function(){

		if(lang!="")


	{		$("#outputDisplayer").show();
	
				$("#output").empty();
	
				$("#output").append("<img id='loader' src='../data/loading.gif'>");
	
				document.querySelector("#output").scrollIntoView({
		            behavior: 'smooth'
		        });
	
	
			$.ajax({  
			url: 'inputchallenge.php',
		    type: 'POST',  
		    //dataType:'JSON',
		    //contentType: 'application/json; charset=utf-8', 
		    data: form_data,
		    //async : true,
		    processData: false,
		    contentType: false, 
		    success: function(text) {
				console.log(text);
		    	data = JSON.parse(text);
		    	console.log(data);
		    	if(data["runtype"]=="custom"){
		    		$("#output").empty();
		    		if(data['1']['success']=="true"){
		    		$("#output").append("<div id='TestCaseOption'><div class = 'testCase pass' href='#'>TestCase 01</div></div><div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>RESULT </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div></div></div>");
		    		}
		    		if(data['1']['success']=="false"){
		    			$("#output").append("<div id='TestCaseOption'><div class = 'testCase fail' href='#'>TestCase 01</div></div><div class='col-9' id='outputContainer'><div class='row'><h3 id='testcaseinfotitle'>RESULT </h3></div><div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div></div></div>");
		    		}
		    		var editorinput = ace.edit("editorinput");
				    editorinput.setFontSize("14px");
				    editorinput.setReadOnly(true);
				    var editorYourOutput = ace.edit("editorYourOutput");
				    editorYourOutput.setFontSize("14px");
				    editorYourOutput.setReadOnly(true);
		    		editorinput.setValue(data['1']['input'],1);
		    		editorYourOutput.setValue(data['1']['result'],1);
		    		var addid = 'active';
					var $cols = $('.testCase').click(function(e) {
						//$cols.removeAttr("class","fail");
						//$cols.removeAttr("class","pass");
					    $cols.removeAttr("id","active");
					    $(this).attr("id","active");
					});
	
		    	
	
	
		    	}
		    	if(data["runtype"]=="runcode"){
		    		var result = []
		    		var str = "";
		    		$("#output").empty();
		    		for (var i = 1; i <= data["NoOfPublicTestCases"]; i++) {
		    			if(data[i]["success"]=="true"){
		    				result[i] = {"title":"Passed","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
		    				if(i==1){
		    					str+="<div id='TestCaseOption'><div class = 'testCase pass' name='1' href='#'>TestCase 1</div>";
		    				}
		    				if(i!=1 && i!=data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase pass' name='"+i+"' href='#'>TestCase "+i+"</div>";
		    				}
		    				if(i==data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase pass' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
		    				}
		    			}
		    			if(data[i]["success"]=="false"){
		    				result[i] = {"title":"Wrong Answer","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
		    				if(i==1){
		    					str+="<div id='TestCaseOption'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
		    				}
		    				if(i!=1 && i!=data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div>";
		    				}
		    				if(i==data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
		    				}
		    			}
		    			if(data[i]["success"]=="error"){
		    				if(data[i]["errorType"]=="compilationErr"){
		    					result[i] = {"title":"Compilation error","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
		    				}
		    				if(data[i]["errorType"]=="timeLimit"){
		    					result[i] = {"title":"Time Limit exceeded","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
		    				}
		    				if(i==1){
		    					str+="<div id='TestCaseOption'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
		    				}
		    				if(i!=1 && i!=data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div>";
		    				}
		    				if(i==data["NoOfPublicTestCases"]){
		    					str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
		    				}
		    			}
		    		}
		    		$("#output").append(str);
		    		if(data["NoOfPublicTestCases"]==data["NoOfPassedTestCases"]){
		    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>ALL PUBLIC TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
		    		}
		    		if(data["NoOfPublicTestCases"]>data["NoOfPassedTestCases"] && data["NoOfPassedTestCases"]!=0){
		    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>SOME TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
		    		}
		    		if(data["NoOfPassedTestCases"]==0){
		    			$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>NONE OF THE TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
		    		}
	
		    		var editorinput = ace.edit("editorinput");
				    editorinput.setFontSize("14px");
				    editorinput.setReadOnly(true);
				    var editorYourOutput = ace.edit("editorYourOutput");
				    editorYourOutput.setFontSize("14px");
				    editorYourOutput.setReadOnly(true);
				    var editorExpectedOutput = ace.edit("editorExpectedOutput");
				    editorExpectedOutput.setFontSize("14px");
				    editorExpectedOutput.setReadOnly(true);
				    var addid = 'active';
					var $cols = $('.testCase').click(function(e) {
						//$cols.removeAttr("class","fail");
						//$cols.removeAttr("class","pass");
					    $cols.removeAttr("id","active");
					    $(this).attr("id","active");
					    var b = $(this).attr("name");
					    $("#testcaseinfotitle").empty();
					    $("#testcaseinfotitle").append(result[b]["title"]);
					    editorinput.setValue(result[b]["input"]);
					    editorinput.clearSelection();
					    editorExpectedOutput.setValue(result[b]["ExpectedOutput"]);
					    editorExpectedOutput.clearSelection();
					    editorYourOutput.setValue(result[b]["output"]);
					    editorYourOutput.clearSelection();
					});
	
	
	
		    	}
		    	
		    	//document.getElementById("outputdiv").innerHTML = obj1.output;
	
		    	//$.get('input.php')
		//.then(function(data){
			//data1 = JSON.parse(data)
		//});
		    	
		    },
		    error: function(){
		    	alert('error!');
		    }
			});}

else{

	$("LanguageSelector").attr("class","highlight");

	document.getElementById("LanguageSelector").scrollIntoView({
		            behavior: 'smooth',
		            inline: "center",
		            block: "center"
		        });



}
	});

	//jquery.POST('input.php',form_data);
	//


	///code.addEventListener("click",function(){

	///	document.getElementById("outputdiv").innerHTML = ccc;

	///});

	var CustInput = document.getElementById("CustomInput");

	var InputTestCase = document.getElementById("InputTestCase");

	InputTestCase.style.visibility = "hidden";

	CustInput.addEventListener("change",function(){
		if(this.checked){
			InputTestCase.style.visibility = "visible";
		}
		else{
			InputTestCase.style.visibility = "hidden";
		}
	});






	</script>

	<script type="text/javascript">
		$(document).ready(function(){
		$.ajax({ url: "getQuestionsGeneral.php",
        	data : {quesid : "<?php echo $id ?>", typeOfQuestion: "<?php echo $typeOfQuestion ?>" },
        	type : "post",
        	success: function(info){
           		info = JSON.parse(info);
           		document.getElementById("title").innerHTML = info.title;
           		document.getElementById("statement").innerHTML = info.question;
           		document.getElementById("if").innerHTML = info.if;
           		document.getElementById("of").innerHTML = info.of;
           		document.getElementById("constrain").innerHTML = info.constrain;
           		document.getElementById("sampleI").innerHTML = "<pre>"+info.sampleI+"</pre>";
           		document.getElementById("sampleO").innerHTML = "<pre>"+info.sampleO+"</pre>";

        	}
        });
	});
	//$("#outputDisplayer").hide();
	</script>

	<script>

    
</script>


<script type="text/javascript">
	
</script>
<script type="text/javascript">
	$("#outputDisplayer").hide();
	$("#LanguageSelector").change(function(){
		$("#LanguageSelector").removeAttr("class","highlight");
	});
</script>

</body>
</html>
