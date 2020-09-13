<?php

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

	if(Session::exists(Config::get('session/session_name')))
	{
		$user = Session::get(Config::get('session/session_name'));
		$obj = new Challenge2($user , $_GET['cid']);
		if($obj->check()){
			if(!$obj->checkChallengeStatus()){
				Redirect::to('../index');
			}
		}
		else{
			Redirect::to('../index');
		}
	}
	$cid = $_GET['cid'];

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../public/css/challenge2Test.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">
</head>
<body>

	<script
  src="https://code.jquery.com/jquery-3.5.0.js"
  integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc="
  crossorigin="anonymous"></script>


<div id="left"></div>
<div id="right"></div>
<div id="top"></div>
<div id="bottom"></div>

<br>

<div class="row">
	<div class="col-2"></div>
	<div class="col-8">
		<h1 class="challengeHeading">RACE AGAINST TIME</h1>
		<hr>
		<br>
		<div class="timer">
			<?php 
			$cid = $_GET['cid'];
			require_once "AttemptChallenge.php";
				
			?>
  		</div>
		<div class="challenge">
			<div class="questions">
				<div id="question" name="1" class="question"><span class="questionText">Question 01</span></div>
				<div id="question" name="2"  class="question"><span class="questionText">Question 02</span></div>
				<div id="question" name="3"  class="question"><span class="questionText">Question 03</span></div>
			</div>
			<div class="questionDisplayer">
				<img class="image" src="../data/timer.jpg">
				<br>
				<div class="challengeDescription">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</div>	


			</div>
		</div>
		<br>
		<br>
		<br>


	</div>
	<div class="col-2"></div>
</div>



<script type="text/javascript">
	var data1;
	$(document).ready(function(){
    $.ajax({ url: "getQuestions.php",
          data : {"challengeid" : "<?php echo $cid; ?>","challengeType":"challenge2"},
          type : "post",
          success : function(data){
            console.log(data);
            
            data = JSON.parse(data);
            data1 = data; 
            ques1 = data[1]["question"];
            ques1id = data[1]["qid"];
            ques2 = data[2]["question"];
            ques2id = data[2]["qid"];
            ques3 = data[3]["question"];
            ques3id = data[3]["qid"];
            console.log(ques1);

          }
        });
});


	var $questions = $(".question").click(function(e){
		$questions.removeAttr("id","afterClickaddup");
		$questions.attr("id","question");
		$(this).removeAttr("id","question");
		$(this).attr("id","afterClickaddup");
		$quesid = $(this).attr("name");
		$(".questionDisplayer").empty();
		$(".questionDisplayer").append("<h1 id='title'></h1><div name="+data1[$quesid]['qid']+" class='buttonn rounded successs' id='attemptChallenge'><span class='btntext'>Attempt Question</span></div><br><h1 id='Title'>QUESTION STATEMENT<br><hr></h1><div id='statement' class='text'></div><br><h1 id='Title'>INPUT FORMAT<br><hr></h1><div id='if' class='text'></div><br><h1 id='Title'>OUTPUT FORMAT<br><hr></h1><div id='of' class='text'></div><br><h1 id='Title'>CONSTRAIN<br><hr></h1><div id='constrain' class='text'></div><br><br><h1 id='Title'>SAMPLE INPUT<br><hr></h1><div class='sample rounded'><div id='sampleI' class='col-12 text'></div></div><br><h1 id='Title'>SAMPLE OUTPUT<br><hr></h1><div class='sample rounded'><div id='sampleO' class='col-12 text'></div></div><br>");
		//info = JSON.parse(info);
		qid = data1[$quesid]['qid'];
        document.getElementById("title").innerHTML = data1[$quesid]['title'];
        document.getElementById("statement").innerHTML = data1[$quesid]['question'];
        document.getElementById("if").innerHTML = data1[$quesid]['if'];
        document.getElementById("of").innerHTML = data1[$quesid]['of'];
        document.getElementById("constrain").innerHTML = data1[$quesid]['constrain'];
        document.getElementById("sampleI").innerHTML = "<pre>"+data1[$quesid]['sampleI']+"</pre>";
        document.getElementById("sampleO").innerHTML = "<pre>"+data1[$quesid]['sampleO']+"</pre>";
        attemptChallenge = document.getElementById("attemptChallenge");
		attemptChallenge.addEventListener("click",function(){
		  userr = "<?php echo $_SESSION['user'] ?>";
		  cid = "<?php echo $cid;?>"
		  url = 'ChallengeTexteditor.php?id='+qid+'&typeOfQuestion=challenge2&challengeid='+cid+'&username='+userr;
		  window.location = url;
});
	});

	
</script>












  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


</body>
</html>

		
