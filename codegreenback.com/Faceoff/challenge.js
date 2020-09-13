	var socket = io.connect("https://www.codegreenback.com:1528");
    socket.on('connect', function () {
        socket.emit('hi',{'challengeid':document.getElementById("challengeid").value});
      });

    socket.on("new_order",function(data){


    console.log(data);
	if(data==document.getElementById("qid").value)
	{
		$(".appendAlert").append("<div class='questionAlert rounded'>This question has been solved.</div>");
		$("#runcodecustom").attr("hidden", "hidden");
		$("#submitCode").attr("hidden", "hidden");
		editor.setReadOnly(true);

    }
	else
	{
        $(".appendAlert").append("<div class='questionAlert rounded'>Question "+data+" has been solved.</div>");
	}


	  /**
	   * emitting i'm online in every 2 sec.
	   */

	  
	});

	socket.on('001', function(){
		$(".opponent-status").hide();
		$("#container-typing").show();
	});

	socket.on('110', function()
	{
		$(".opponent-status").hide();
		$("#container-not-typing").show();
	});

	socket.on('010', function(){
		$(".opponent-status").hide();
		$("#container-code-running").show();
	});

	socket.on("100", function(){
		$(".opponent-status").hide();
		$("#container-code-error").show();
	});
	socket.on("101", function () {
		$(".opponent-status").hide();
		$("#container-code-success").show();
	  });
	  
	socket.on("111", function (data){
		if(data == basic.username)
		{
			$("#c3-challenge-msg").text(`You Have Won The Challenge With ${basic.opponent} `)
			$("#c3-challenge-img").attr('src', 'https://www.codegreenback.com/public/img/codesuccess.gif');
			$(".c3-msg-div").show();

			setTimeout(function(){
				window.location.replace("https://www.codegreenback.com/");
			},5000);
		}
		else if(data == basic.opponent)
		{
			$("#c3-challenge-msg").text(`You Have Lost The Challenge With ${data} `)
			$("#c3-challenge-img").attr('src', 'https://www.codegreenback.com/public/img/codeerror.gif');
			$(".c3-msg-div").show();

			setTimeout(function(){
				window.location.replace("https://www.codegreenback.com/");
			},5000);
		}
		else
		{
			window.location.replace("https://www.codegreenback.com/");
		}
	});

	/**
	 * codes : 	typing : 001
	 * 			runcode : 010
	 * 			submit  : 011
	 * 			error 	: 100
	 * 			success : 101
	 * 			not_typing : 110
	 * 			challengeCompleted : 111
	 */	
	
const myActivity = {
	typing: function(){
		socket.emit("001");
		},
	not_typing : function(){
		socket.emit("110");
		},
	runcode: function(){
		socket.emit("010");
		},
	submit : function(){
		socket.emit("011");
		},
	error : function(){
		socket.emit("100");
		},
	success : function(){
		socket.emit("101");
	},
	
	typing_sent : 0,
	
	notTyping_sent : 0,

	counter : 0,

	main_counter : 0,

	qid : null                              //question id

}


const basic = {
	username : "",
	opponent : "",
	cc 		 : ""
}


sendActivity();

function sendActivity()
{
	$("#editor").keyup(function(){

		if(myActivity.typing_sent == 0 && myActivity.main_counter == 0)
		{
			myActivity.typing_sent = 1;

			myActivity.notTyping_sent = 0;

			myActivity.typing();

		}

		myActivity.counter = 0;
	});



}


setInterval(function(){

	myActivity.counter  = myActivity.counter + 1;

	if(myActivity.counter > 4)
	{
		if(myActivity.main_counter == 0)
		{
			if((myActivity.typing_sent == 0 && myActivity.notTyping_sent == 0) || (myActivity.typing_sent == 1 && myActivity.notTyping_sent == 0))
			{
				myActivity.not_typing();

				myActivity.notTyping_sent = 1;
				myActivity.typing_sent = 0;
			}
		}

		
	}
},1000);


var lang="";

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
		 if(strUser == "c"){
                        editor.session.setMode("ace/mode/c_cpp");
                lang = "c";
                }
		if(strUser == "java"){
			editor.session.setMode("ace/mode/java");
    		lang = "java";
		}
		 if(strUser == "ruby"){
                        editor.session.setMode("ace/mode/ruby");
                lang = "ruby";
                }
		 if(strUser == "swift"){
                        editor.session.setMode("ace/mode/swift");
                lang = "swift";
                }
		 if(strUser == "javascript"){
                        editor.session.setMode("ace/mode/javascript");
                lang = "javascript";
                }
		 if(strUser == "bash"){
                        editor.session.setMode("ace/mode/batchfile");
                lang = "bash";
                }
		 if(strUser == "haskell"){
                        editor.session.setMode("ace/mode/haskell");
                lang = "haskell";
                }
		 if(strUser == "erlang"){
                        editor.session.setMode("ace/mode/erlang");
                lang = "erlang";
                }
	});

	// var codeid = document.getElementById("qid").value;
    var editor = ace.edit("editor");
//     $(document).ready(function(){
// 		$.ajax({ url: "/getlastsavedcode.php",
//         	data : {quesid : document.getElementById("qid").value, user:document.getElementById("user").value},
//         	type : "post",
//         	success: function(info){
//            		editor.setValue(info);
//         	}});
// });
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



	// setInterval(function(){
	// 		if(editor.getValue()!=""){
	// 		var codesave = {code : editor.getValue(), qid : document.getElementById("qid").value, user:document.getElementById("user").value};
	// 	}
	// 		else{
	// 		var codesave = {code : "NOCODE", qid : document.getElementById("qid").value, user:document.getElementById("user").value};

	// 		}
	// 	$.ajax({
	// 				url: '/savecode.php',
	// 			    type: 'POST',   
	// 			    data: codesave
	// 		});
	// }
	// ,1000);

	var code1 = document.getElementById("runcodecustom");
	var code2 = document.getElementById("submitCode");
	var codes;
	var code_obj = {
	};
	
	code1.addEventListener("click",function(){
		if (CustInput.checked) {

			code_obj = {
			runType : "runcode",
			CustomInput :true,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : myActivity.qid,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			challengeid : document.getElementById("challengeid").value
		};

		}
		else {

			  code_obj = {
			runType : "runcode",
			CustomInput : false,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : myActivity.qid,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			challengeid : document.getElementById("challengeid").value
	};

		}
		
	});



	code2.addEventListener("click",function(){
		

			code_obj = {
				runType : "submitCode",
			CustomInput :false,
			code_obj1 : editor.getValue(),
			language : lang,
			question_id : myActivity.qid,
			input : false,
			coderun : document.getElementById('coderun').value,
			challengeid : document.getElementById("challengeid").value

		}
		
	});

	

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

			{
						/**
                 * sending msg to opponent that
                 * submit code is clicked
                 */
               		 		myActivity.main_counter = 1;
		        		myActivity.runcode();

						$("#outputDisplayer").show();
			
						$("#output").empty();
			
						$("#output").append("<img id='loader' src='https://www.codegreenback.com/data/loading.gif'>");
			
						document.querySelector("#output").scrollIntoView({
				            behavior: 'smooth'
				        });
			
			
			
					$.ajax({  
					url: 'inputchallenge3.php',
				    type: 'POST',  
					data: form_data,
					dataType:"json",
				    processData: false,
				    contentType: false, 
				    success: function(data) {

				    // data = JSON.parse(text);
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

									myActivity.success();
									setTimeout(function () {
									myActivity.main_counter = 0;
									myActivity.notTyping_sent = 0;
									myActivity.typing_sent = 0;
									}, 4000);
										}
								if(data["NoOfTestCases"]>data["NoOfPassedTestCases"] && data["NoOfPassedTestCases"]!=0){

									/**
									 * send msg to opponent that runcode failed
									 */

									myActivity.error();
									setTimeout(function () {
										myActivity.main_counter = 0;
										myActivity.notTyping_sent = 0;
										myActivity.typing_sent = 0;
									}, 4000);


									$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>SOME TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
								}
								if(data["NoOfPassedTestCases"]==0){

									/**
									 * send msg to opponent that runcode failed
									 */

									myActivity.error();
									setTimeout(function () {
										myActivity.main_counter = 0;
										myActivity.notTyping_sent = 0;
										myActivity.typing_sent = 0;
									}, 4000);


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
		{
				 myActivity.main_counter = 1;
			        myActivity.runcode();
				$("#outputDisplayer").show();
	
				$("#output").empty();
	
				$("#output").append("<img id='loader' src='https://www.codegreenback.com/data/loading.gif'>");
	
				document.querySelector("#output").scrollIntoView({
		            behavior: 'smooth'
		        });
	
	
			$.ajax({  
				url: 'https://www.codegreenback.com/Faceoff/inputchallenge3.php',
		    type: 'POST',  
		    data: form_data,
		    processData: false,
		    contentType: false, 
		    success: function(text) {
		    	data = JSON.parse(text);
		    	console.log(data);
		    	if(data["runtype"]=="custom"){
					$("#output").empty();
					
		    		if(data['1']['success']=="true"){

						/**
						 * send msg to opponent that runcode success
						 */
					myActivity.success();
					setTimeout(function () {
						myActivity.main_counter = 0;
						myActivity.notTyping_sent = 0;
						myActivity.typing_sent = 0;
					} , 4000);

		    		$("#output").append("<div id='TestCaseOption'><div class = 'testCase pass' href='#'>TestCase 01</div></div><div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>RESULT </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div></div></div>");
		    		}
		    		if(data['1']['success']=="false"){

						/**
						 * send msg to opponent that runcode failed
						 */

						myActivity.error();
						setTimeout(function () {
							myActivity.main_counter = 0;
							myActivity.notTyping_sent = 0;
							myActivity.typing_sent = 0;
						}, 4000);

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
							$cols.removeAttr("id","active");
							$(this).attr("id","active");
						});
		
					
	
	
				}
				
					if(data["runtype"]=="runcode")
					{
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
								if(i==data["NoOfPublicTestCases"] && data['NoOfPublicTestCases']!=1){
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
								if(i==data["NoOfPublicTestCases"] && data['NoOfPublicTestCases']!=1){
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
								if(i==data["NoOfPublicTestCases"] && data['NoOfPublicTestCases']!=1){
									str+="<div class = 'testCase fail' name='"+i+"' href='#'>TestCase "+i+"</div></div>";
								}
							}
						}
						$("#output").append(str);

						if(data["NoOfPublicTestCases"]==data["NoOfPassedTestCases"]){

								myActivity.success();
								setTimeout(function () {
									myActivity.main_counter = 0;
									myActivity.notTyping_sent = 0;
									myActivity.typing_sent = 0;
								}, 4000);

							$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>ALL PUBLIC TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
						}
						if(data["NoOfPublicTestCases"]>data["NoOfPassedTestCases"] && data["NoOfPassedTestCases"]!=0){

							myActivity.error();
							setTimeout(function () {
								myActivity.main_counter = 0;
								myActivity.notTyping_sent = 0;
								myActivity.typing_sent = 0;
							}, 4000);
							$("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>SOME TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
						}
						if(data["NoOfPassedTestCases"]==0){

							myActivity.error();
							setTimeout(function () {
								myActivity.main_counter = 0;
								myActivity.notTyping_sent = 0;
								myActivity.typing_sent = 0;
							}, 4000);
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




$.ajax({ 
	url: "https://www.codegreenback.com/Faceoff/c3middle.php",
	data : {
		_ser : "_10",
		_cid : $("#challengeid").val()
	},
	type : "post",
	dataType:"json",
	success: function(info){
		
		console.log(info);
		if(info.status)
		{
			make_page(info.problem);
			myActivity.qid = info.problem.qid;
			basic.username = info.username;
			basic.opponent = info.opponent;
			basic.cc	=	info.cc;

		$("#player-1").text(`${basic.username}`);
        $("#player-2").text(`${basic.opponent}`);
        $("#cc_bet").text(`${basic.cc}`);
		}
		else
		{
			alert("An Error Occurred");
			setTimeout(function(){
				window.location.replace("https://www.codegreenback.com/");
			},2000)
		}


	}
});

setloader();

function setloader()
{
	var time = new Date();
		var n = time.getTime();
		console.log(start_time);
		if(start_time*1000 < n)
		{
			var timer = 3;
			var fun = setInterval(function(){
				if(timer > 0)
				{
					$("#start-time").text(timer);
					timer = timer-1;
				}
				else
				{
					$("#start-time").text("");
					$("#starter_loader").hide();
					$(".main-body").fadeIn();
					clearInterval(fun);
				}
			},1000)
		}
		else
		{
			var timer = start_time - Math.floor(n/1000);
			var fun = setInterval(function(){
				if(timer > 0)
				{
					$("#start-time").text(timer);
					timer = timer-1;
				}
				else
				{
					$("#start-time").text("");
					$("#starter_loader").hide();
					$(".main-body").fadeIn();
					clearInterval(fun);
				}
			},1000)
		}
}

function make_page(info)
{
	// document.getElementById("title").innerHTML = info.title;
    document.getElementById("statement").innerHTML = info.question;
    document.getElementById("if").innerHTML = info.if;
    document.getElementById("of").innerHTML = info.of;
    document.getElementById("constrain").innerHTML = info.constrain;
    document.getElementById("sampleI").innerHTML =
        "<pre>" + info.sampleI + "</pre>";
    document.getElementById("sampleO").innerHTML =
		"<pre>" + info.sampleO + "</pre>";
		

}


$("#outputDisplayer").hide();
	$("#LanguageSelector").change(function(){
		$("#LanguageSelector").removeAttr("class","highlight");
	});

    $(document).ready(function(){
	


  	});
