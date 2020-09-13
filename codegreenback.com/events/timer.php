<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/core/init.php';


//$id = 5;

//$question = new Ques($id);

//$ques = $question->get_question();

$id = $_GET["task_id"];

$event = new event($id);

$endTime = $event->getEndTime($id);

$endTime = $endTime - time();

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index');
}




?> 



<!DOCTYPE html>
<html>
<head>
	<title>Count Down</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>

<style type="text/css" media="screen">
	
	#demo{
		height: 70px;
		width: 100%;
		font-size: 42px;
		text-align: center;
		background-color: #28334aff;
		font-weight: bolder; 
	}

</style>

<div class="row">
			<div id="demo">
				
			</div>
		
	</div>
	

<script type="text/javascript">
	var percentColors = [
    { pct: 0.0, color: { r: 0xff, g: 0x00, b: 0 } },
    { pct: 0.5, color: { r: 0xff, g: 0xff, b: 0 } },
    { pct: 1.0, color: { r: 0x00, g: 0xff, b: 0 } } ];

var getColorForPercentage = function(pct) {
    for (var i = 1; i < percentColors.length - 1; i++) {
        if (pct < percentColors[i].pct) {
            break;
        }
    }
    var lower = percentColors[i - 1];
    var upper = percentColors[i];
    var range = upper.pct - lower.pct;
    var rangePct = (pct - lower.pct) / range;
    var pctLower = 1 - rangePct;
    var pctUpper = rangePct;
    var color = {
        r: Math.floor(lower.color.r * pctLower + upper.color.r * pctUpper),
        g: Math.floor(lower.color.g * pctLower + upper.color.g * pctUpper),
        b: Math.floor(lower.color.b * pctLower + upper.color.b * pctUpper)
    };
    return 'rgb(' + [color.r, color.g, color.b].join(',') + ')';
    // or output as hex if preferred
};
</script>






<script>
	var distance = <?php echo $endTime?>;
var x = setInterval(function() {
  
  distance = distance-1;    //get using php

  // Time calculations for days, hours, minutes and seconds
  var hours = Math.floor((distance / (60 * 60)));
  var minutes = Math.floor((distance % (60 * 60)) / (60));
  var seconds = Math.floor((distance % 60));

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
    //window.location.replace("index.php");
  }



  colorr = '#fbde44ff';
	//console.log(colorr);
	$("#left").css("background-color",colorr);
	$("#right").css("background-color",colorr);
	$("#top").css("background-color",colorr);
	$("#bottom").css("background-color",colorr);
	$("#demo").css("border","3px solid "+colorr);
	$("#demo").css("color",colorr);
	//$("#demo").css("-webkit-text-stroke-width",".2px");
	//$("#demo").css("-webkit-text-stroke-color","8a8a8a");
   //dataSend = {"initial":true};
  //$.ajax({
//url:"AttemptChallenge.php" ,
    //      type: "POST",
    //      data: dataSend
          //dataType: ""
          //beforeSend: function(x) {
          //  if (x && x.overrideMimeType) {
          //    x.overrideMimeType("application/j-son;charset=UTF-8");
          //  }
          //},
          //success: function(result) {
          //}
//}); 
}, 1000);




</script>




















<script src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
