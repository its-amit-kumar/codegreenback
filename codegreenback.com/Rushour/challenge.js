$(".dot").click(function(){
    if($(".info").height() >0){
        $(this).css({
                        '-webkit-transform': 'rotate(' + 0 + 'deg)',
                        '-moz-transform': 'rotate(' + 0 + 'deg)',
                        'transform': 'rotate(' + 0 + 'deg)'
                    });
    $(".info").css("height", "0px");
    $(".dot").css("opacity", "1");
    }
    else{
        $(this).css({
                        '-webkit-transform': 'rotate(' + 180 + 'deg)',
                        '-moz-transform': 'rotate(' + 180 + 'deg)',
                        'transform': 'rotate(' + 180 + 'deg)'
                    });
                    $(".info").css("height", "160px");
                    $(".dot").css("opacity", "0.5");
    }
})



var timePassed;
    var editor = ace.edit("editor");
    $(document).ready(function(){
		$.ajax({ url: "getInfo.php",
        	data : {
        		cid : document.getElementById("cid").value, 
        		qno : document.getElementById("quesNo").value
        	},
        	type : "post",
        	success: function(info){
           		info = JSON.parse(info);
           		if(info['time'] <= 0){
					window.location = "https://www.codegreenback.com";
           		}
           		else{
				timePassed = 1800 - info.time;
           		document.getElementById("statement").innerHTML = info.question;
           		document.getElementById("if").innerHTML = info.if;
           		document.getElementById("of").innerHTML = info.of;
           		document.getElementById("constrain").innerHTML = info.constrain;
           		document.getElementById("sampleI").innerHTML = "<pre>"+info.sampleI+"</pre>";
				   document.getElementById("sampleO").innerHTML = "<pre>"+info.sampleO+"</pre>";
				   document.getElementById("challenger").innerHTML = info.challenger;
				   document.getElementById("opponent").innerHTML = info.opponent;
				for(i = 0; i<info.solved.length; i++){
                    $("#ques"+info['solved'][i]).addClass("successque");
            }

					   
           		}
        	}});
	$("#ques"+$("#quesNo").val()).addClass("present-question");

	    $.ajax({ url: "/codeSaver/getCode.php",
                data : {
                        cid : document.getElementById("cid").value,
			qNo : document.getElementById("quesNo").value
                },
                type : "post",
                success: function(info){
			info = JSON.parse(info);
			editor.setValue(info['code']);
		}
	    });


});
    ace.config.set('basePath', '/Ace');
    ace.config.set('themePath', '/Ace');
    require('ace/ext/language_tools');
    editor.setOptions({
            enableLiveAutocompletion: true
        });
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

$.ajax({ url: "/codeSaver/saver.php",
                data : {
                        cid : document.getElementById("cid").value,
			code : editor.getValue(),
			qNo : document.getElementById("quesNo").value
                },
                type : "post",
                success: function(info){
                        info = JSON.parse(info);
                        editor.setValue(info['code']);
                }
            });
},10000);





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
			cid :  document.getElementById("cid").value,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			quesNo :document.getElementById('quesNo').value
			//codes = editor.getValue();
		};

		}
		else {

			  code_obj = {
			runType : "runcode",
			CustomInput : false,
			code_obj1 : editor.getValue(),
			language : lang,
			cid :  document.getElementById("cid").value,
			input : document.getElementById('InputTestCase').value,
			coderun : document.getElementById('coderun').value,
			quesNo:document.getElementById('quesNo').value
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
			cid :  document.getElementById("cid").value,
			input : false,
			coderun : document.getElementById('coderun').value,
			quesNo : document.getElementById('quesNo').value
			//codes = editor.getValue();

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

			{$("#outputDisplayer").show();
			
						$("#output").empty();
			
						$("#output").append("<img id='loader' src='data/loading.gif'>");
			
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
							$("#ques"+$("#quesNo").val()).addClass("successque");
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
	
				$("#output").append("<img id='loader' src='data/loading.gif'>");
	
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
					    $cols.removeAttr("id","active");
					    $(this).attr("id","active");
					});
	
		    	
	
	
		    	}
		    	if(data["runtype"]=="runcode"){
		    		var result = []
		    		var str = "";
		    		$("#output").empty();
		    		for (var i = 1; i <= data["NoOfPublicTestCases"]; i++) {
					if(!(i in data)){
                                                                break;
                                                        }

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



			$("#outputDisplayer").hide();
	$("#LanguageSelector").change(function(){
		$("#LanguageSelector").removeAttr("class","highlight");
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
				if(lang==""){
					alert("Please slect language");
				}

        });

        $(".deleteCode").click(function(){
        	if(confirm("Do you wanto to  clear your code?")){
        		editor.setValue("//##Type your code here##//");
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


$("#sbs").click(function(){
	$(".content").removeAttr("hidden","hidden");
	$(".main").css("height","700px");
	$("#cont").css("flex-direction","row");
	$(".content").css("width","44.5%");
	$(".tx").css("position","relative");
	$(".tx").css("width","54.5%");
	$(".tx").css("margin-left","0");
	$(".dragbar").css("width",".5%");
	$(".dragbar").removeAttr("hidden");
	$(".content").css("margin-left","0");
	$(".content").css("overflow-x","hidden");
	$(".content").css("overflow-y","scroll");
	$(".tx").css("overflow-y","scroll");
	$(".content").css("height","100vh");
	$(".tx").css("height","100vh");
	$(".main").css("margin-left","3%");
	$(".main").css("margin-right","3%");
	$(".main").css("width","94%");
	$(".input").css("margin-left","3%");
	$(".input").css("margin-right","3%");
	$(".input").css("width","94%");
    //$( ".content" ).resizable();
 	var dragging = false;

	$('.dragbar').mousedown(function(e){
	  e.preventDefault();
	  dragging = true;
	  var side = $('.content');
	  $(document).mousemove(function(ex){
	  	if(ex.pageX<$(window).width()*.6){
	  		side.css("width", ex.pageX +2);
	  	}
	  });
	});

	$(document).mouseup(function(e){
	  if (dragging) 
	  {
	    $(document).unbind('mousemove');
	    dragging = false;
	  }
	});


});

$("#fs").click(function(){
	$(".dragbar").attr("hidden","hidden");
	$(".content").attr("hidden","hidden");
	$(".tx").css("position","absolute");
	$(".tx").css("margin-left","0");
	$(".tx").css("width","100%");
	$(".tx").css("height","100vh");
	$(".main").css("height","90vh");
});


$("#ud").click(function(){
	$(".content").removeAttr("hidden","hidden");
	$(".main").css("height","700px");
	$("#cont").css("flex-direction","column");
	$(".content").css("width","65%");
	$(".tx").css("position","relative");
	$(".tx").css("width","65%");
	$(".tx").css("margin-left","17.5%");
	$(".dragbar").css("width",".5%");
	$(".dragbar").attr("hidden","hidden");
	$(".content").css("margin-left","17.5%");
	$(".content").css("overflow-x","");
	$(".content").css("overflow-y","");
	$(".tx").css("overflow-y","");
	$(".content").css("height","");
	$(".tx").css("height","");
	$(".main").css("margin-left","");
	$(".main").css("margin-right","");
	$(".main").css("width","100%");
	$(".input").css("margin-left","");
	$(".input").css("margin-right","");
	$(".input").css("width","100%");

	});

	const FULL_DASH_ARRAY = 283;
	const WARNING_THRESHOLD = 600;
	const ALERT_THRESHOLD = 300;
	
	const COLOR_CODES = {
	  info: {
		color: "green"
	  },
	  warning: {
		color: "orange",
		threshold: WARNING_THRESHOLD
	  },
	  alert: {
		color: "red",
		threshold: ALERT_THRESHOLD
	  }
	};
	
	const TIME_LIMIT = 1800;
	let timeLeft = TIME_LIMIT;
	let timerInterval = null;
	let remainingPathColor = COLOR_CODES.info.color;
	
	document.getElementById("app").innerHTML = `
	<div class="base-timer">
	  <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
		<g class="base-timer__circle">
		  <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
		  <path
			id="base-timer-path-remaining"
			stroke-dasharray="283"
			class="base-timer__path-remaining ${remainingPathColor}"
			d="
			  M 50, 50
			  m -45, 0
			  a 45,45 0 1,0 90,0
			  a 45,45 0 1,0 -90,0
			"
		  ></path>
		</g>
	  </svg>
	  <span id="base-timer-label" class="base-timer__label">${formatTime(
		timeLeft
	  )}</span>
	</div>
	`;
	
	startTimer();
	
	function onTimesUp() {
	  clearInterval(timerInterval);
	}
	
	function startTimer() {
	  timerInterval = setInterval(() => {
		timePassed = timePassed += 1;
		timeLeft = TIME_LIMIT - timePassed;
		document.getElementById("base-timer-label").innerHTML = formatTime(
		  timeLeft
		);
		setCircleDasharray();
		setRemainingPathColor(timeLeft);
	
		if (timeLeft === 0) {
		  onTimesUp();
		window.location = "https://www.codegreenback.com";

		}
	  }, 1000);
	}
	
	function formatTime(time) {
	  const minutes = Math.floor(time / 60);
	  let seconds = time % 60;
	
	  if (seconds < 10) {
		seconds = `0${seconds}`;
	  }
	
	  return `${minutes}:${seconds}`;
	}
	
	function setRemainingPathColor(timeLeft) {
	  const { alert, warning, info } = COLOR_CODES;
	  if (timeLeft <= alert.threshold) {
		document
		  .getElementById("base-timer-path-remaining")
		  .classList.remove(warning.color);
		document
		  .getElementById("base-timer-path-remaining")
		  .classList.add(alert.color);
	  } else if (timeLeft <= warning.threshold) {
		document
		  .getElementById("base-timer-path-remaining")
		  .classList.remove(info.color);
		document
		  .getElementById("base-timer-path-remaining")
		  .classList.add(warning.color);
	  }
	}
	
	function calculateTimeFraction() {
	  const rawTimeFraction = timeLeft / TIME_LIMIT;
	  return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
	}
	
	function setCircleDasharray() {
	  const circleDasharray = `${(
		calculateTimeFraction() * FULL_DASH_ARRAY
	  ).toFixed(0)} 283`;
	  document
		.getElementById("base-timer-path-remaining")
		.setAttribute("stroke-dasharray", circleDasharray);
	}
	




