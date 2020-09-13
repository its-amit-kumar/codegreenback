<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

require_once $_SERVER["DOCUMENT_ROOT"]."/after_login_header.php";


$mdb = new mongo("Events");
$id = $_GET["id"];

$details = $mdb->get($id, array("type" => "details"))[0];
$resultDetails = $mdb->get($id, array("type" => "result"))[0];
?>

<link rel="stylesheet" type="text/css" href="public/css/eventDetails.css">
<link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:wght@700" rel="stylesheet">
<div class="content">
	<?php
	echo "<div class='".$details['status']."'><h1 class='status'>".strtoupper($details['status'])."</h1></div>";
	?>
	<div class="title">
		<h1><?php echo $details['title'] ?></h1>
		<h1><?php echo $id; ?></h1>	
	</div>
	<hr>
	<?php

	if($details['status'] == "scheduled"){
		echo "<button type='button' id = 'launch' class='btn btn-success btn-lg btn-block'>LAUNCH EVENT NOW!</button>";
	}
	if($details['status'] == "running"){
		echo "<button type='button' id = 'end' class='btn btn-danger btn-lg btn-block'>STOP EVENT NOW!</button>";
	}

	If($details["status"]=="ended"){
		if($resultDetails['status']==0 && $resultDetails['rewardStatus'] == 0){
			echo "<button type='button' id = 'generateResults' class='btn btn-danger btn-lg btn-block'>GENERATE AND SET RESULTS</button><br><br>";
		}
		if($resultDetails['status']==1 && $resultDetails['rewardStatus'] == 0){
			echo "<button type='button' id = 'distributeResult' class='btn btn-danger btn-lg btn-block'>DISTRIBUTE REWARDS</button><br><br><div class='displayDBresult'><table style='width : 100%'><tr><th>RANK</th><th>NAME</th><th>TYPE</th><th>CRITERIA</th></tr>";
			$i = 1;
			while(true){
				if(!isset($resultDetails[$i])){
					break;
				}
				else{
					echo "<tr><td>".$i."</td><td>".$resultDetails[$i]['name']."</td><td>".$resultDetails[$i]['typeOfUser']."</td><td>".$resultDetails[$i]['criteria']."</td></tr>";
					$i++;
				}
			}
			echo "</table></div>";
		}
	}
	?>
	<h3 style="margin-top: 50px;">MINOR DETAILS</h3><hr style="margin-bottom: 25px;">
	<div style="display: flex; flex-direction: row; justify-content: space-between; color:green; width: 90%; margin-left: 5%;">
		<h5>Users Registered : <?php echo (count($mdb->get($id))-3)?></h5>
		<h5>
			Start Date: <?php echo $details['startDate']; ?>
		</h5>
		<h5>
			Start time: <?php echo $details['startTime']; ?>
		</h5>

	</div>
	<br>
	<br>
	<br>

	<div style="display: flex; flex-direction: row; justify-content: space-between; color:green; width: 90%; margin-left: 5%;margin-bottom: 30px;">
		<h5>Type Of Event : <?php echo $details['typeOfEvent']; ?></h5>
		<?php
		if($details['typeOfEvent']=='paid'){
			echo "<h5>CC : ".$details['cc'];
		}
		?>
		<h5>
			Promotion: <?php echo $details['typeOfPromotion']; ?>
		</h5>
		<h5>
			Number of Questions: <?php echo $details['numberOfQuestions']; ?>
		</h5>

	</div>

	<h3>INSTRUCTIONS</h3><hr>
	<div style="display: flex; flex-direction: row; justify-content: space-between; width: 90%; margin-left: 5%;margin-bottom: 30px;">
		<?php echo $details['instruction'];?>
	</div>
	<div style="display: flex; flex-direction: row; justify-content: space-between; width: 90%; margin-left: 5%;margin-bottom: 30px;">
		<?php echo $details['details'];?>
	</div>
	<?php
	if($details['status']=='pending'){

		echo "<a href='makeEventQuestion.php?id=".$id."'><button type='button' class='btn btn-success'>Add all Questions to turn the status to scheduled</button></a><br><br><br><br>";

	}
	else{
		echo "<h3>QUESTIONS</h3><hr>";
		for($i = 0;$i<$details['numberOfQuestions'];$i++){
			echo "<div class='displayQuestion'><div class='question'><h2>".$details['questions'][$i]['title']."</h2><h2>".$details['questions'][$i]['difficulty']."</h2></div></div>";
		}
	}

	?>



<script type="text/javascript">
	$("#launch").click(function(){
		$("#launch").attr("hidden","hidden");
		$.ajax({
			url : "event_mw.php",
			type : "post",
			data : {
				"service" : "launch",
				eventId : "<?php echo $id?>"
			},

			success : function(data){
				if(data == -1){
					alert("no such event exits!");
				}
				if(data == -2){
					alert("This event does not have scheduled status!");
				}
				if(data==0){
					alert("Sorry! There was some error!");
				}
				if(data==1){
					location.reload();
				}
			}
		});
	});

	$("#end").click(function(){
		$("#end").attr("hidden","hidden");
		$.ajax({
			url : "event_mw.php",
			type : "post",
			data : {
				"service" : "end",
				eventId : "<?php echo $id?>"
			},

			success : function(data){
				if(data == -1){
					alert("no such event exits!");
				}
				if(data == -2){
					alert("This event does not have running status!");
				}
				if(data==0){
					alert("Sorry! There was some error!");
				}
				if(data==1){
					location.reload();
				}
			}
		});
	});

		function downloadURI(uri, name) {
		    var link = document.createElement("a");
		    // If you don't know the name or want to use
		    // the webserver default set name = ''
		    link.setAttribute('download', name);
		    link.href = uri;
		    document.body.appendChild(link);
		    link.click();
		    link.remove();
		}

		$("#generateResults").click(function(){
			eventId1 = "<?php echo $id?>";
		$.ajax({
			url : "event_mw.php",
			type : "post",
			data : {
				"service" : "generateResult",
				eventId : "<?php echo $id?>"
			},

			success : function(data){
				if (data==0) {
					alert("sorry! The event has not ended");
				}

				if (data==1) {
					downloadURI("results/"+eventId1+".csv","result.csv");
				}
			}
		});
	});

		$("#resultUpload").change(function(){
			$.ajax({
				
			});
		});


		$("#distributeResult").click(function(){
			if(confirm("Are you sure you want to distribute the results for this event?")){
			let randomString = Math.random().toString(36).replace('0.',"event_" || '');
			input = prompt("Please enter this string to confirm: \n"+randomString);
			if(input==randomString){
				window.location = "reward_script/"+"<?php echo $id ?>"+".php";
			}
		}
		});
</script>

</div>

