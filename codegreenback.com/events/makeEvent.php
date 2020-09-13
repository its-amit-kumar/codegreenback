<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

require_once "/var/www/html/php/after_login_header.php";

?>
<link rel="stylesheet" type="text/css" href="public/css/makeEvent.css">
<link rel="stylesheet" type="text/css" href="public/css/trix.css">
<script type="text/javascript" src="public/js/trix.js"></script>
<div class="overlay" hidden="hidden">
	<div class="loader"></div>
</div>
<div class="content">
	<h1>CREATE A NEW EVENT</h1><hr>
	<div class="dashboard">s
		<span style="display: flex; margin: 10px;">
			<input type="textbox" name="name" placeholder="Name of the event" id="name" style="width: 50%; height: 40px; font-size: 30px" autocomplete="off">
		</span>
		<hr>
		<div style="display: flex; margin: 10px;align-content: space-between;">
			<div>
				DURATION OF THE EVENT IN MINUTES : <input type="textbox" name="duration" id="duration" autocomplete="off">
			</div>
		<div>
			COMMENCEMENT DATE: <input id="startDate" type="date" name="date"></div><div>TIME: <input id="startTime" type="time" name="date">
		</div>
	    </div>
		<hr>
		<div style="display: flex; align-content: space-between; width: 100%;">
			<h5 style="margin: 5px;">
			Type of event   
			</h5>
			<div style="margin: 5px;">
				PAID : <input type="radio" value="paid" name="typeOfEvent" id="paid">
			</div>
			<div style="margin: 5px">
				UNPAID : <input type="radio" name="typeOfEvent" value="unpaid" name="typeOfEvent" id="unpaid">
			</div>
			<div style="margin: 5px;">
				<input type="textbox" id="cc" placeholder="Enter registration cost" hidden="hidden">
			</div>
		</div>
		<hr>
		<div style="display: flex; align-content: space-between; width: 100%;">
			<h5>
				Number of Questions  <input id="numberOfQuestions" type="number" name="numberOfQuestions">
			</h5>
		</div>
		<hr>
		<div style="display: flex; align-content: space-between; width: 100%;">
			<h5 style="margin: 5px;">
			Type of promotion   
			</h5>
			<div style="margin: 5px;">
				STANDARD : <input type="radio" value="standard" name="typeOfPromotion">
			</div>
			<div style="margin: 5px">
				SPECIAL : <input type="radio" name="typeOfPromotion" value="special">
			</div>
			<div style="margin: 5px;">
				ADVANCED : <input type="radio" name="typeOfPromotion" value="advanced">
			</div>
		</div>
		<hr>
		<h5 style="margin: 5px">DETAILS(Like Reward and vision)</h5>
		<form>
            <input id="details" type="hidden" name="details">
            <trix-editor id = "detail" input="details"></trix-editor>
        </form>
        <hr>
        <h5 style="margin: 5px">Instruction (Official Rulebook)</h5>
		<form>
            <input id="instructions" type="hidden" name="instructions">
            <trix-editor id = "instruction" input="instructions"></trix-editor>
        </form>
        <hr>




	</div>
	<button type="button" id="makeEvent" class="btn btn-success">MAKE EVENT</button>
</div>

<script type="text/javascript">
	var typeOfEvent = "";
	radio1 = document.getElementById("paid");
	radio1.addEventListener("change",function(){
		$("#cc").removeAttr("hidden");
		typeOfEvent = 'paid';
	});

	radio2 = document.getElementById("unpaid");
	radio2.addEventListener("change",function(){
		$("#cc").attr("hidden","hidden");
		typeOfEvent = 'unpaid';
	});

</script>

<script type="text/javascript">
	var typeOfPromotion;
	$("#makeEvent").click(function(){
			var ele = document.getElementsByName('typeOfPromotion'); 
	              
	            for(i = 0; i < ele.length; i++) { 
	            	typeOfPromotion = "";
	                if(ele[i].checked) 
	                typeOfPromotion = ele[i].value; 
	            }
	        if(validate()){ 
			data = {
			'name' : $("#name").val(),
			'duration' : $("#duration").val(),
			"startDate" : $("#startDate").val(),
			'startTime' : $('#startTime').val(),
			'typeOfEvent' : typeOfEvent,
			"cc" : $("#cc").val(),
			'typeOfPromotion' : typeOfPromotion,
			'numberOfQuestions' : $("#numberOfQuestions").val(),
			'details' : $("#details").val(),
			'instruction' : $("#instructions").val(),
			'service' : "makeEvent" 
			};
			$.ajax({
				url : "event_mw.php",
				data : data,
				type : "post",
				beforeSend : function(){
					$(".overlay").removeAttr("hidden", "hidden");
					$("#makeEvent").attr("hidden", "hidden");
				},
				success : function(d){
					if (d==1) {
						$(".overlay").attr("hidden", "hidden");
						alert("The event has successfully been created.");
						location.reload();

					};
				}

			});
		}
	}); 

	
</script>

<script type="text/javascript">
	function validate(){
		if ($("#name").val()=="") {
			alert("Please enter the event title!");
			return false;
		}
		if ($("#duration").val()=="" || isNaN($("#duration").val())) {
			alert("Please enter a valid duration");
			return false;
		}
		if ($("#startDate").val()=="") {
			alert('Please enter a valid date!');
			return false;
		}
		if ($("#startTime").val()=="") {
			alert("Please enter a starting time");
			return false;
		}
		if(typeOfEvent==""){
			alert("please select type of event!")
		}
		if(typeOfEvent=="paid"){
			if($("#cc").val()=="" || isNaN($("#cc").val())){
				alert("The event is marked paid! Please enter valid cc.");
				return false;
			}
		}
		if (typeOfPromotion=="") {
			alert("Please select a type of promotion.");
			return false;
		}
		if ($("#numberOfQuestions").val()=="" || isNaN($("#numberOfQuestions").val())) {
			alert("Please enter a valid value for number of question");
			return false;
		}
		if ($("#details").val()=="") {
			alert("Please enter the details of the event!");
			return false;
		}

		if ($("#instruction").val()=="") {
			alert("Pleased enter the instruction for the event");
			return false;
		}
		return true;

	}
</script>