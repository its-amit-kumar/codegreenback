function openNav() {
  document.getElementById("mySidenav").style.width = "14%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}


var ques;
window.addEventListener('load', function () {
  $.ajax({
    url:"/events/mw/getQuestions.php",
    type:"post",
    data:{"task_id":document.getElementById("task_id").value},
    success:function(data){
      data = JSON.parse(data);
      ques = data;
      for(i=0;i<data['numberOfQues'];i++){
        $(".options").append("<div name='"+(i+1)+"' id='question' class='question'><h3>QUESTION "+(i+1)+"</h3></div>");
      }
        $.ajax({  
        url: '/events/mw/getSolvedQuestions.php',
        type: 'POST',
        data: {event_id : document.getElementById('challengeId').value},
        success : function(d){
          data = JSON.parse(d);
          for(i=0;i<data.length;i++){
            btnn = document.getElementsByName(parseInt(data[i])+1)[0];
            btnn.style.background = "green";
          }
        }  

    });

        $("#liveDetails").click(function() {
        $("#yourRank").empty();
      $("#yourRank").html("<b>. . .</b>");
      $("#submissions").empty();
      $("#submissions").html("<b>. . .</b>");
      $("#ssubmissions").empty();
      $("#ssubmissions").html("<b>. . .</b>");
      $("#ranking").empty();
  $.ajax({
    url : "/events/mw/event_mw.php",
    type : "post",
    data : {
      "service":"runningDetails",
      "eventId" : document.getElementById("task_id").value
    },
    success : function(data){
      var thisRank;
      user = $("#user").val();
      data = JSON.parse(data);
      data1 = data;
      console.log(data);
      rank = data["rank"];
      console.log(rank);
      for(i=0;i<rank.length;i++){
        $("#ranking").append(". <a href='/searched_user.php?search="+rank[i]+"'>"+(i+1)+". "+rank[i]+"</a>");
        if (rank[i] == user) {
          thisRank = i+1;
        }
      }
      $("#yourRank").empty();
      $("#yourRank").html(thisRank);
      $("#submissions").empty();
      $("#submissions").html(data['totalSubmissions']);
      $("#ssubmissions").empty();
      $("#ssubmissions").html(data['successfulSubmission']);
    }
  });
});


$("#refresh").click(function(){
          $("#yourRank").empty();
      $("#yourRank").html("<b>. . .</b>");
      $("#submissions").empty();
      $("#submissions").html("<b>. . .</b>");
      $("#ssubmissions").empty();
      $("#ssubmissions").html("<b>. . .</b>");
      $("#ranking").empty();
  $.ajax({
    url : "/events/mw/event_mw.php",
    type : "post",
    data : {
      "service":"runningDetails",
      "eventId" : document.getElementById("task_id").value
    },
    success : function(data){
      var thisRank;
      user = $("#user").val();
      data = JSON.parse(data);
      data1 = data;
      console.log(data);
      rank = data["rank"];
      console.log(rank);
      for(i=0;i<rank.length;i++){
        $("#ranking").append(". <a href='/searched_user.php?search="+rank[i]+"'>"+(i+1)+". "+rank[i]+"</a>");
        if (rank[i] == user) {
          thisRank = i+1;
        }
      }
      $("#yourRank").empty();
      $("#yourRank").html(thisRank);
      $("#submissions").empty();
      $("#submissions").html(data['totalSubmissions']);
      $("#ssubmissions").empty();
      $("#ssubmissions").html(data['successfulSubmission']);
    }
  });
});









      var $questions = $(".question").click(function(e){
        $(".info").attr("hidden","hidden");
        $(".attemptQuestion").removeAttr("hidden","hidden");
        $questions.removeAttr("id","afterClickaddup");
        $questions.attr("id","question");
        $(this).removeAttr("id","question");
        $(this).attr("id","afterClickaddup");
        quesNumber = $(this).attr("name");
        $("#quesNo").attr("value",quesNumber-1);
        $("#title").html(ques[0][quesNumber-1]['title']);
        $("#statement").html(ques[0][quesNumber-1]['statement']);
        $("#if").html(ques[0][quesNumber-1]['if']);
        $("#of").html(ques[0][quesNumber-1]['of']);
        $("#sampleI").html(ques[0][quesNumber-1]['sampleI']);
        $("#sampleO").html(ques[0][quesNumber-1]['sampleO']);
        $("#constrain").html(ques[0][quesNumber-1]['constrain']);
        $("#qid").val(quesNumber-1);






        var editor = ace.edit("editor");
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

  var quesId;
var lang="";
function getFormData(object) {
  const formData = new FormData();
  Object.keys(object).forEach(key => formData.append(key, object[key]));
  return formData;
}


  var counter = 0;
  var code1 = document.getElementById("runcodecustom");
  var code2 = document.getElementById("submitCode");
  var codes;
  var code_obj = {
  };
  //code.addEventListener("click",function(){
  //   codes = editor.getValue();
  
  code1.addEventListener("click",function(){
    if (CustInput.checked) {

      code_obj = {
      runType : "runcode",
      CustomInput :true,
      code_obj1 : editor.getValue(),
      language : lang,
      question_id :  document.getElementById("qid").value,
      input : document.getElementById('InputTestCase').value,
      coderun : document.getElementById('coderun').value,
      type:document.getElementById('type').value,
      eventId : document.getElementById('challengeId').value
      //codes = editor.getValue();
    };
    quesId = document.getElementById("qid").value; 

    }
    else {

        code_obj = {
      runType : "runcode",
      CustomInput : false,
      code_obj1 : editor.getValue(),
      language : lang,
      question_id :  document.getElementById("qid").value,
      input : document.getElementById('InputTestCase').value,
      coderun : document.getElementById('coderun').value,
      type:document.getElementById('type').value,
      eventId : document.getElementById('challengeId').value
      //codes = editor.getValue();
  };
  quesId = document.getElementById("qid").value; 

    }
    
  });
  code2.addEventListener("click",function(){
    

      code_obj = {
        runType : "submitCode",
      CustomInput :false,
      code_obj1 : editor.getValue(),
      language : lang,
      question_id :  document.getElementById("qid").value,
      input : false,
      coderun : document.getElementById('coderun').value,
      type:document.getElementById('type').value,
      eventId : document.getElementById('challengeId').value
      //codes = editor.getValue();
    };
    quesId = document.getElementById("qid").value; 
    
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
	      if(counter == 0){
		 counter = 1;
	      $("#outputDisplayer").show();
      
            $("#output").empty();
      
            $("#output").append("<img id='loader' src='data/loading.gif'>");
      
            document.querySelector("#output").scrollIntoView({
                    behavior: 'smooth'
                });
      
      
      		
          $.ajax({  
          url: '/events/mw/inputEvent.php',
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
                      str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase pass' name='1' href='#'>TestCase 1</div>";
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
                      str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
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
                      str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
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

                  btnn = document.getElementsByName(parseInt(quesId)+1)[0];
                  btnn.style.background = "green";

                  $("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>ALL PUBLIC TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
                }
                if(data["NoOfTestCases"]>data["NoOfPassedTestCases"] && data["NoOfPassedTestCases"]!=0){
                  btnn = document.getElementsByName(parseInt(quesId)+1)[0];
                  btnn.style.background = "#4d4d4d";
                  $("#output").append("<div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>SOME TEST CASES HAVE PASSED </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div><div class='row'><h5>Expected Output</h5></div><div class='row'><div id='editorExpectedOutput'></div></div></div></div>");
                }
                if(data["NoOfPassedTestCases"]==0){
                  btnn = document.getElementsByName(parseInt(quesId)+1)[0];
                  btnn.style.background = "#4d4d4d";
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
              
		counter = 0;              
            },
            error: function(){
		    counter = 0;
              alert('error!');
            }
          });}}

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
	  if(counter==0){
		  counter = 1;
	  $("#outputDisplayer").show();
  
        $("#output").empty();
  
        $("#output").append("<img id='loader' src='data/loading.gif'>");
  
        document.querySelector("#output").scrollIntoView({
                behavior: 'smooth'
            });
  
  
      $.ajax({  
      url: '/events/mw/inputEvent.php',
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
            $("#output").append("<div id='TestCaseOption' class='col-3'><div class = 'testCase pass' href='#'>TestCase 01</div></div><div class='col-9' id='outputContainer'><div><div class='row'><h3 id='testcaseinfotitle'>RESULT </h3></div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div></div></div>");
            }
            if(data['1']['success']=="false"){
              $("#output").append("<div id='TestCaseOption' class='col-3'><div class = 'testCase fail' href='#'>TestCase 01</div></div><div class='col-9' id='outputContainer'><div class='row'><h3 id='testcaseinfotitle'>RESULT </h3></div><div><div class='row'><h5>Input: </h5></div><div class='row'><div id='editorinput'></div></div><div class='row'><h5>Your Output</h5></div><div class='row'><div id='editorYourOutput'></div></div></div></div>");
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
              if(data[i]["success"]=="true"){
                result[i] = {"title":"Passed","input":data[i]["input"],"ExpectedOutput":data[i]["ExpectedOutput"],"output":data[i]["output"]};
                if(i==1){
                  str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase pass' name='1' href='#'>TestCase 1</div>";
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
                  str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
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
                  str+="<div id='TestCaseOption' class='col-3'><div class = 'testCase fail' name='1' href='#'>TestCase 1</div>";
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
		counter = 0;
          
        },
        error: function(){
		counter = 0;
          alert('error!');
        }
      });}}

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
















      });

    }
  });
});


