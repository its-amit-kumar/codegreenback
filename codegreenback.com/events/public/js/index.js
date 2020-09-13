var distance;
//var lastSavedCode;


$(document).ready(function(){

	$.ajax({

		url:"/events/mw/getQuestions.php",

		type:"post",

		data:{"task_id":document.getElementById("eid").value, qNo :  document.getElementById("qNo").value},

		success:function(data){

		  info = JSON.parse(data);

		  //console.log(data);

		  document.getElementById("title").innerHTML = info.title;

           		document.getElementById("statement").innerHTML = info.statement;

           		document.getElementById("if").innerHTML = info.if;

           		document.getElementById("of").innerHTML = info.of;

           		document.getElementById("constrain").innerHTML = info.constrain;

           		document.getElementById("sampleI").innerHTML = "<pre style='color : white'>"+info.sampleI+"</pre>";

				document.getElementById("sampleO").innerHTML = "<pre style='color : white'>"+info.sampleO+"</pre>";

			   distance = info.endTime;

		  }

	});



	$.ajax({  

        url: '/events/mw/getSolvedQuestions.php',

        type: 'POST',

        data: {event_id : document.getElementById('eid').value},

        success : function(d){

		  data = JSON.parse(d);

		  console.log(data);

		  btnn = document.getElementsByClassName('ques');

          for(i=0;i<data.length;i++){

            btnn[data[i]].style.background = "#00b51b";

          }

        }  



	});

	$.ajax({

		url : "/events/mw/event_mw.php",

		type : "post",

		data : {

		  "service":"runningDetails",

		  "eventId" : document.getElementById("eid").value

		},

		success : function(data){

		  data = JSON.parse(data);

		  console.log(data);

		  for($i = 0; $i<data['rank'].length; $i++){



			$(".rank").append("<li>"+($i+1)+"    "+data['rank'][$i]+"</li>");

			if($("#user").val() == data['rank'][$i]){

				$("#your-rank").append($i+1);

			}



		  }

		  $("#submissions").append(data['totalSubmissions']);

		  $("#successful-submissions").append(data['successfulSubmission']);

		}

	  });

	$.ajax({ url: "/codeSaver/getCode.php",
                data : {
                        cid : document.getElementById("eid").value,
                        qNo : document.getElementById("qNo").value
                },
                type : "post",
                success: function(info){
                        info = JSON.parse(info);
                        editor.setValue(info['code']);
                }
            });






});



const second = 1000,

      minute = second * 60,

      hour = minute * 60,

      day = hour * 24;



//let countDown = new Date('Sep 9, 2020 00:00:00').getTime(),

    x = setInterval(function() {    



      //let now = new Date().getTime(),

          //distance = countDown - now;

		  distance = distance - 1;

		if(distance<=0){
			window.location.href = "https://www.codegreenback.com";
		}		  

		  var hours = Math.floor((distance / (60 * 60)));

		  var minutes = Math.floor((distance % (60 * 60)) / (60));

		  var seconds = Math.floor((distance % 60));



      

        document.getElementById('hours').innerText = hours,

        document.getElementById('minutes').innerText = minutes,

        document.getElementById('seconds').innerText = seconds;





    }, second)



var dragging = false;



	$('.dragbar').mousedown(function(e){

	  e.preventDefault();

	  dragging = true;

	  var side = $('#questionDisplayer');

	  var side2 = $('#editor');

	  $($('.contentParent')).mousemove(function(ex){

	  	if(ex.pageX<$('.contentParent').width()*.6){

			side2.css("width", $('.contentParent').width() - (ex.pageX + 2));

			  side.css("width", ex.pageX + 2);

	  	}

	  });

	});



	$($('.contentParent')).mouseup(function(e){

	  if (dragging) 

	  {

	    $($('.contentParent')).unbind('mousemove');

	    dragging = false;

	  }

    });



    ace.config.set('basePath', '/Ace');

	ace.config.set('themePath', '/Ace');

	var editor = ace.edit("editor");

	editor.setTheme("ace/theme/twilight");

	ace.require("ace/ext/language_tools");

	editor.setOptions({

		enableLiveAutocompletion: true

	});

	//editor.setValue(lastSavedCode);

    var m = document.getElementById("textsize");

    editor.setFontSize(m.value+"px");

    

	m.addEventListener("change",function(){

		var a = m.value;

		editor.setFontSize(a+"px");

		

	});



	document.getElementById("ThemeSelector").addEventListener("change",function(){

		editor.setTheme("ace/theme/"+document.getElementById("ThemeSelector").value);

    });

    

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

		if(strUser == "c"){

                        editor.session.setMode("ace/mode/c_cpp");

                lang = "c";

                }

		if(strUser == "java"){

			editor.session.setMode("ace/mode/java");

    		lang = "java";

		}

		if(strUser == "bash"){

			editor.session.setMode("ace/mode/batchfile");

    		lang = "bash";

		}

		if(strUser == "erlang"){

			editor.session.setMode("ace/mode/erlang");

    		lang = "erlang";

		}

		if(strUser == "golang"){

			editor.session.setMode("ace/mode/golang");

    		lang = "golang";

		}

		if(strUser == "haskell"){

			editor.session.setMode("ace/mode/haskell");

    		lang = "haskell";

		}

		if(strUser == "javascript"){

			editor.session.setMode("ace/mode/javascript");

    		lang = "javascript";

		}

		if(strUser == "ruby"){

			editor.session.setMode("ace/mode/ruby");

    		lang = "ruby";

		}

		if(strUser == "swift"){

			editor.session.setMode("ace/mode/swift");

    		lang = "swift";

		}

	});





	editorCustom = ace.edit("custom-output");

  ace.config.set('basePath', '/Ace');

    ace.config.set('themePath', '/Ace');

    editorCustom.setTheme("ace/theme/twilight");

    $(".toggle-output").click(function(){

      if($(".outputDisplayer").height() == 300){

		$(".outputDisplayer").height("20px");

		$(".toggle-output").empty();

        $(".toggle-output").text("Open Output");

      }

      else{

		$(".outputDisplayer").height("300px");

		$(".toggle-output").empty();

        $(".toggle-output").text("Close Output");

      }

	});

	

	var CustInput = document.getElementById("CustomInput");



	



	CustInput.addEventListener("change",function(){



			if(!$("#CustomInput").is(":checked")){

				$(".outputDisplayer").height("20px");

				$(".toggle-output").empty();

				$(".toggle-output").text("Open Output");

			}

			else{

				$(".outputDisplayer").height("300px");

				$(".toggle-output").empty();

				$(".toggle-output").text("Close Output");

				$(".outputDisplayer").empty();

				$(".outputDisplayer").html("<div class='toggle-output'>Open Output</div><div class='out'><textarea class='custom-input' placeholder='Input here'></textarea><div id='custom-output'></div>");

				editorCustom = ace.edit("custom-output");

    			editorCustom.setTheme("ace/theme/twilight");

				$(".toggle-output").click(function(){

				if($(".outputDisplayer").height() == 300){

				  $(".outputDisplayer").height("20px");

				  $(".toggle-output").empty();

				  $(".toggle-output").text("Open Output");

				}

				else{

				  $(".outputDisplayer").height("300px");

				  $(".toggle-output").empty();

				  $(".toggle-output").text("Close Output");

				}

			

			  });

			}



		

	});



var run = $("#run-code");

var submit = $("#submit-code");



run.click(function(){

	console.log("A");

	if($("#LanguageSelector").val()!=null){

		document.getElementById('run-code').style.pointerEvents = 'none';

		document.getElementById('submit-code').style.pointerEvents = 'none';

		input = $(".custom-input").val();

		$(".outputDisplayer").height("300px");

		$(".toggle-output").empty();

		$(".toggle-output").text("Close Output");

		$(".outputDisplayer").empty();

		if($("#CustomInput").is(":checked")){

			$(".outputDisplayer").append("<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='margin: auto; background: rgb(54, 54, 54); display: block; shape-rendering: auto;' width='211px' height='211px' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'><path fill='none' stroke='#ec4d37' stroke-width='1' stroke-dasharray='243.75948181152344 12.829446411132807' d='M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z' stroke-linecap='round' style='transform:scale(0.88);transform-origin:50px 50px'><animate attributeName='stroke-dashoffset' repeatCount='indefinite' dur='0.9803921568627451s' keyTimes='0;1' values='0;256.58892822265625'></animate></path></svg>");

			$.ajax({

				url:"/events/mw/inputEvent.php",

				type:"post",

				data:{

					"eventId" : $("#eid").val(),

					'runType' : "runcode",

					"CustomInput" : true,

					"code_obj1" : editor.getValue(),

					"language" : $("#LanguageSelector").val(),

					"question_id" : $("#qNo").val()-1,

					"input" : input



				},

				success:function(data){



				  data = JSON.parse(data);

				  $(".outputDisplayer").empty();

				$(".outputDisplayer").html("<div class='toggle-output'>Close Output</div><div class='out'><textarea class='custom-input' placeholder='Input here'></textarea><div id='custom-output'></div>");

				

				editorCustom = ace.edit("custom-output");

				editorCustom.setTheme("ace/theme/twilight");

				editorCustom.setReadOnly(true);

				editorCustom.setFontSize("18px");

				editorCustom.setValue(data[1]['result'], 1);

				$(".toggle-output").click(function(){

					if($(".outputDisplayer").height() == 300){

					  $(".outputDisplayer").height("15px");

					  $(".toggle-output").empty();

					  $(".toggle-output").text("Open Output");

					}

					else{

					  $(".outputDisplayer").height("300px");

					  $(".toggle-output").empty();

					  $(".toggle-output").text("Close Output");

					}

				

				  });

				  },

				error : function(){

					alert("error");

				}



			});

		}

		else{

			$(".outputDisplayer").append("<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='margin: auto; background: rgb(54, 54, 54); display: block; shape-rendering: auto;' width='211px' height='211px' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'><path fill='none' stroke='#ec4d37' stroke-width='1' stroke-dasharray='243.75948181152344 12.829446411132807' d='M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z' stroke-linecap='round' style='transform:scale(0.88);transform-origin:50px 50px'><animate attributeName='stroke-dashoffset' repeatCount='indefinite' dur='0.9803921568627451s' keyTimes='0;1' values='0;256.58892822265625'></animate></path></svg>");

			$.ajax({

				url:"/events/mw/inputEvent.php",

				type:"post",

				data:{

					"eventId" : $("#eid").val(),

					'runType' : "runcode",

					"CustomInput" : false,

					"code_obj1" : editor.getValue(),

					"language" : $("#LanguageSelector").val(),

					"question_id" : $("#qNo").val()-1,

					"input" : ""



				},

				success : function(data){

					data = JSON.parse(data);

					console.log(data);

					$(".outputDisplayer").empty();

					$(".outputDisplayer").append("<div class='toggle-output'>Close Output</div><div class='test-option'><div class='test-cases'></div><div class='tags'><div class='tag'>Input</div><div class='tag'>Your Output</div><div class='tag'>Expected Output</div></div></div><div class='out'><div id='input'></div><div id='your-output'></div><div id='expected-output'></div></div>");

					$(".toggle-output").click(function(){

						if($(".outputDisplayer").height() == 300){

						  $(".outputDisplayer").height("15px");

						  $(".toggle-output").empty();

						  $(".toggle-output").text("Open Output");

						}

						else{

						  $(".outputDisplayer").height("300px");

						  $(".toggle-output").empty();

						  $(".toggle-output").text("Close Output");

						}

					

					  });

					  input = ace.edit("input");

						yourOutput = ace.edit("your-output");

						expectedOutput = ace.edit("expected-output");

						ace.config.set('basePath', '/Ace');

						ace.config.set('themePath', '/Ace');

						input.setTheme("ace/theme/twilight");

						yourOutput.setTheme("ace/theme/twilight");

						expectedOutput.setTheme("ace/theme/twilight");

						input.setFontSize("18PX");

						yourOutput.setFontSize("18PX");

						expectedOutput.setFontSize("18PX");

						input.setReadOnly(true);

						yourOutput.setReadOnly(true);

						expectedOutput.setReadOnly(true);

					for(i = 1; i <= data.NoOfPublicTestCases; i++){

						if(data[i]['success'] == 'true'){

							$(".test-cases").append("<div class='test-case success' name='"+i+"'>Test Case "+i+"</div>");

						}

						else{

							$(".test-cases").append("<div class='test-case failure' name='"+i+"'>Test Case "+i+"</div>");

						}

					}

					$(".test-case").click(function(){

						a = $(this).attr("name");

						input.setValue(data[a]['input'], 1);

						yourOutput.setValue(data[a]['output'], 1);

						expectedOutput.setValue(data[a]['ExpectedOutput'], 1);

						$(".test-case").removeClass("selected");

						$(this).addClass("selected");

					});

				}

			});

		}

		document.getElementById('run-code').style.pointerEvents = 'auto';

		document.getElementById('submit-code').style.pointerEvents = 'auto';



	}

	else{

		alert("Please select language.");

	}

});





submit.click(function(){

	console.log("A");

	if($("#LanguageSelector").val()!=null){

		document.getElementById('run-code').style.pointerEvents = 'none';

		document.getElementById('submit-code').style.pointerEvents = 'none';

		input = $(".custom-input").val();

		$(".outputDisplayer").height("300px");

		$(".toggle-output").empty();

		$(".toggle-output").text("Close Output");

		$(".outputDisplayer").empty();

		if($("#CustomInput").is(":checked")){

			$(".outputDisplayer").append("<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='margin: auto; background: rgb(54, 54, 54); display: block; shape-rendering: auto;' width='211px' height='211px' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'><path fill='none' stroke='#ec4d37' stroke-width='1' stroke-dasharray='243.75948181152344 12.829446411132807' d='M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z' stroke-linecap='round' style='transform:scale(0.88);transform-origin:50px 50px'><animate attributeName='stroke-dashoffset' repeatCount='indefinite' dur='0.9803921568627451s' keyTimes='0;1' values='0;256.58892822265625'></animate></path></svg>");

			$.ajax({

				url:"/events/mw/inputEvent.php",

				type:"post",

				data:{

					"eventId" : $("#eid").val(),

					'runType' : "submitCode",

					"CustomInput" : true,

					"code_obj1" : editor.getValue(),

					"language" : $("#LanguageSelector").val(),

					"question_id" : $("#qNo").val()-1,

					"input" : input



				},

				success:function(data){



				  data = JSON.parse(data);

				  $(".outputDisplayer").empty();

				$(".outputDisplayer").html("<div class='toggle-output'>Close Output</div><div class='out'><textarea class='custom-input' placeholder='Input here'></textarea><div id='custom-output'></div>");

				

				editorCustom = ace.edit("custom-output");

				editorCustom.setTheme("ace/theme/twilight");

				editorCustom.setReadOnly(true);

				editorCustom.setFontSize("18px");

				editorCustom.setValue(data[1]['result'], 1);

				$(".toggle-output").click(function(){

					if($(".outputDisplayer").height() == 300){

					  $(".outputDisplayer").height("15px");

					  $(".toggle-output").empty();

					  $(".toggle-output").text("Open Output");

					}

					else{

					  $(".outputDisplayer").height("300px");

					  $(".toggle-output").empty();

					  $(".toggle-output").text("Close Output");

					}

				

				  });

				  },

				error : function(){

					alert("error");

				}



			});

		}

		else{

			$(".outputDisplayer").append("<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='margin: auto; background: rgb(54, 54, 54); display: block; shape-rendering: auto;' width='211px' height='211px' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'><path fill='none' stroke='#ec4d37' stroke-width='1' stroke-dasharray='243.75948181152344 12.829446411132807' d='M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z' stroke-linecap='round' style='transform:scale(0.88);transform-origin:50px 50px'><animate attributeName='stroke-dashoffset' repeatCount='indefinite' dur='0.9803921568627451s' keyTimes='0;1' values='0;256.58892822265625'></animate></path></svg>");

			$.ajax({

				url:"/events/mw/inputEvent.php",

				type:"post",

				data:{

					"eventId" : $("#eid").val(),

					'runType' : "submitCode",

					"CustomInput" : false,

					"code_obj1" : editor.getValue(),

					"language" : $("#LanguageSelector").val(),

					"question_id" : $("#qNo").val()-1,

					"input" : ""



				},

				success : function(data){

					data = JSON.parse(data);

					console.log(data);

					$(".outputDisplayer").empty();

					$(".outputDisplayer").append("<div class='toggle-output'>Close Output</div><div class='test-option'><div class='test-cases'></div><div class='tags'><div class='tag'>Input</div><div class='tag'>Your Output</div><div class='tag'>Expected Output</div></div></div><div class='out'><div id='input'></div><div id='your-output'></div><div id='expected-output'></div></div>");

					$(".toggle-output").click(function(){

						if($(".outputDisplayer").height() == 300){

						  $(".outputDisplayer").height("15px");

						  $(".toggle-output").empty();

						  $(".toggle-output").text("Open Output");

						}

						else{

						  $(".outputDisplayer").height("300px");

						  $(".toggle-output").empty();

						  $(".toggle-output").text("Close Output");

						}

					

					  });

					  input = ace.edit("input");

						yourOutput = ace.edit("your-output");

						expectedOutput = ace.edit("expected-output");

						ace.config.set('basePath', '/Ace');

						ace.config.set('themePath', '/Ace');

						input.setTheme("ace/theme/twilight");

						yourOutput.setTheme("ace/theme/twilight");

						expectedOutput.setTheme("ace/theme/twilight");

						input.setFontSize("18PX");

						yourOutput.setFontSize("18PX");

						expectedOutput.setFontSize("18PX");

						input.setReadOnly(true);

						yourOutput.setReadOnly(true);

						expectedOutput.setReadOnly(true);

					for(i = 1; i <= data.NoOfTestCases; i++){

						if(data[i]['success'] == 'true'){

							$(".test-cases").append("<div class='test-case success' name='"+i+"'>Test Case "+i+"</div>");

						}

						else{

							$(".test-cases").append("<div class='test-case failure' name='"+i+"'>Test Case "+i+"</div>");

						}

					}

					$(".test-case").click(function(){

						a = $(this).attr("name");

						input.setValue(data[a]['input'], 1);

						yourOutput.setValue(data[a]['output'], 1);

						expectedOutput.setValue(data[a]['ExpectedOutput'], 1);

						$(".test-case").removeClass("selected");

						$(this).addClass("selected");

					});

					if(data.NoOfTestCases == data.NoOfPassedTestCases){

						btnn = document.getElementsByClassName('ques');

						btnn[document.getElementById("qNo").value - 1].style.background = "#00b51b";					

					}

					else{

						btnn = document.getElementsByClassName('ques');

						btnn[document.getElementById("qNo").value - 1].style.background = "transparent";

					}

				}

			});

		}

		document.getElementById('run-code').style.pointerEvents = 'auto';

		document.getElementById('submit-code').style.pointerEvents = 'auto';



	}

	else{

		alert("Please select language.");

	}

});





$('#keyBind button').on('click', function (e) {

	e.preventDefault();

	 ace.require($(this).val());

	 editor.setKeyboardHandler($(this).val());

  });



  $('#softWrap button').on('click', function (e) {

	e.preventDefault();

	 if($(this).val()=="off"){

		 editor.getSession().setUseWrapMode(false);

	 }

	 else if($(this).val()=="free"){

		 editor.getSession().setUseWrapMode(true);

		 editor.getSession().setWrapLimitRange();

	 }

	 else{

		 editor.getSession().setUseWrapMode(true);

		 editor.getSession().setWrapLimitRange(0,$(this).val());

	 }

  });



  document.getElementById('inputfile') 

  .addEventListener('change', function() { 

	

  var fr=new FileReader(); 

  fr.onload=function(){ 

	  if(confirm("Are you sure you want to load this code?")){

		  editor.setValue(fr.result); 

	  }

  } 

	

  fr.readAsText(this.files[0]); 

});

document.getElementById('autocomplete').addEventListener("change", function(){

  if ($('#autocomplete').is(':checked')) {

	  editor.setOptions({

		 enableLiveAutocompletion: true

	  });

  }

  else{

	  editor.setOptions({

		 enableLiveAutocompletion: false

	  });

  }



}); 





$("#download").click(function(){

  function download(filename, text) {

		var element = document.createElement('a');

		element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));

		element.setAttribute('download', filename);



		element.style.display = 'none';

		document.body.appendChild(element);



		element.click();



		document.body.removeChild(element);

	  }

	  lang = $("#LanguageSelector").val();

	  if(lang=="python"){

		  download("code.py",editor.getValue());

	  }

					  if(lang=="c"){

		  download("code.c",editor.getValue());

	  }

					  if(lang=="cpp"){

		  download("code.cpp",editor.getValue());

	  }

					  if(lang=="java"){

		  download("code.java",editor.getValue());

	  }

					  if(lang=="erlang"){

		  download("code.erl",editor.getValue());

	  }

					  if(lang=="golang"){

		  download("code.go",editor.getValue());

	  }

					  if(lang=="haskell"){

		  download("code.hs",editor.getValue());

	  }

					  if(lang=="bash"){

		  download("code.sh",editor.getValue());

	  }

					  if(lang=="javascript"){

		  download("code.js",editor.getValue());

	  }

					  if(lang=="ruby"){

		  download("code.rb",editor.getValue());

	  }

					  if(lang=="swift"){

		  download("code.swift",editor.getValue());

					  }

	  if(lang==null){

		  alert("Please slect language");

	  }



});



$(".deleteCode").click(function(){

  if(confirm("Do you wanto to  clear your code?")){

	  editor.setValue("//##Type your code here##//");

  }

});

setInterval(function(){

$.ajax({ url: "/codeSaver/saver.php",
                data : {
                        cid : document.getElementById("eid").value,
                        code : editor.getValue(),
                        qNo : document.getElementById("qNo").value
                },
                type : "post",
                success: function(info){
                }
            });
},10000);


