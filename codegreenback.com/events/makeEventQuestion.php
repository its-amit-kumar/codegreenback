<?php

$id  = $_GET["id"];

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
if(isset($_SESSION['user'])){
  
    $username = Session::get('user');
}
else{
    header("Location: index.php");
}


$mdb = new mongo("Events");
$res = $mdb->get($id,array("type" => "details"))[0];

$totalQuestions = $res["numberOfQuestions"];
$posterQuestions = count($res['questions']);

if($totalQuestions==$posterQuestions){
  header("Location: eventDetails.php?id=".$id);
}
?>

<!DOCTYPE html>
<html>

<head>
  <title></title>
  <script src="Ace/ace.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" type="text/css" href="public/css/ques.css?id=<?php echo random_int(1,10000); ?>">
  <link rel="stylesheet" type="text/css" href="public/css/trix.css">
  <script type="text/javascript" src="public/js/trix.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">



<style>
    
    /*header*/


.f-header {
    width: 100%;
    height: 12vh;
    background-color:  #18183b;
    display: flex;
}

.f-header-img-div {
    width: 8%;
    background-color: rgb(61, 236, 193);
    border-top-right-radius: 60px;
    border-bottom-right-radius: 60px;
    /* padding: 0 auto; */
    display: flex;
    align-items: center;
    justify-content: center;
}

.f-header-body {
    display: flex;
    width: 90%;
    justify-content: space-between;
}

.f-header-title {
    width: 60%;
    height: 100%;
    /* background-color: rgb(107, 233, 255); */
    /* text-align: center; */
    padding: 15px;
    color: whitesmoke;
}
    
    
    

.spinner1 {
  margin:  0 auto;
  /* width: 70px; */
  margin-top:1vh;
  text-align: center;
}

.spinner1 > div {
  width: 18px;
  height: 18px;
  background-color: #333;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.spinner1 .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner1 .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
</style>


</head>

<body>
    
        <div class="f-header">
    <div class="f-header-img-div">
        <img src="public/logo.png" alt="logo" style="width: 5vw; ">
    </div>

    <div class="f-header-body">
        <div class="f-header-title">
            <p style="font-size: 3vw">CodeGreenBack <sub style="font-size:1.5vw">...worth your while</sub></p>
            <!-- <p>...worth your while</p> -->
        </div>


        <div style="padding: 15px; height:100%">
            <a href="#" style="font-size:1.5vw; color:whitesmoke; text-decoration:none"><?php echo $_SESSION['user'] ?></a>
        </div>
    </div>
</div>
     <div style="position:absolute;;top:13%;right:2%; border:1px solid blue; padding:5px">
        <a href="logout.php" style="font-size: 1vw;text-decoration:none">Logout </a>
    </div>

     <div style="position:absolute;;top:13%;left:2%; border:1px solid blue; padding:5px">
        <a href="memberPanel.php" style="font-size: 1vw;text-decoration:none">DashBoard</a>
    </div>


    
    
  <form action="" method="post">
    
      <div class="container" >
        <h3>Questions Posted: <?php echo $posterQuestions?> out of <?php echo $res['numberOfQuestions']?></h3>
        <br>
          <div>
            <h3>Tags</h3>
          </div>

          <div  style="display:flex; justify-content: space-between; width:100%">
            <div>
                <div class="dropdown">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Select Language 
                  </button>
                  <div class="dropdown-menu" style="width:12vw">
                    <h5>
                      <li class='all-value-option' >All :   <input type="checkbox" name="lang" value="all"></li>
                      <li class='all-value-option' >Python :   <input type="checkbox" name="lang" value="Python"></li>
                      <li class='all-value-option' >C++ :  <input type="checkbox" name="lang" value="c++"></li>
                      <li class='all-value-option' >Java :  <input type="checkbox" name="lang" value="java"></li>
                      <li class='all-value-option' >C :  <input type="checkbox" name="lang" value="c"></li>
                      <li class='all-value-option' >Bash :  <input type="checkbox" name="lang" value="bash"></li>
                      <li class='all-value-option' >Erlang :  <input type="checkbox" name="lang" value="erlang"></li>
                      <li class='all-value-option' >Golang :  <input type="checkbox" name="lang" value="golang"></li>
                      <li class='all-value-option' >Haskell :  <input type="checkbox" name="lang" value="haskell"></li>
                      <li class='all-value-option' >Javascript :  <input type="checkbox" name="lang" value="javascript"></li>
                      <li class='all-value-option' >Ruby :  <input type="checkbox" name="lang" value="ruby"></li>

                      <li>Swift :  <input type="checkbox" name="lang" value="swift"></li>
                    </h5>
                  </div>
              </div>
            </div>

            <div >
              <div class="dropdown" style="margin-left: 10px;">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Level
                  </button>
                  <div class="dropdown-menu" style="width:12vw">
                    <h5>
                      <li><b>PLEASE SELECT ONLY ONE</b></li>
                      <li>Easy :   <input id="difficulty" type="checkbox" name="level" value="easy" ></li>
                      <li>Mediun :   <input id="difficulty" type="checkbox" name="level" value="medium" ></li>
                      <li>Hard :  <input id="difficulty" type="checkbox" name="level" value="hard" ></li>
                      <li>Advanced :  <input id="difficulty" type="checkbox" name="level" value="advanced" ></li>
                    </h5>
                  </div>
              </div>
            </div>

            <div >
              <div class="dropdown" style="margin-left: 10px;">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Domain
                  </button>
                  <div class="dropdown-menu" style="width:12vw">
                    <h5>
                      <li>Strings :   <input type="checkbox" name="domain" value="Strings"></li>
                      <li>Array :   <input type="checkbox" name="domain" value="Array"></li>
                      <li>DS :  <input type="checkbox" name="domain" value="DS"></li>
                      <li>Algorithm :  <input type="checkbox" name="domain" value="algo"></li>
                      <li>Operator :  <input type="checkbox" name="domain" value="operator"></li>
                      <li>Printing Pattern :  <input type="checkbox" name="domain" value="printing_pattern"></li>
                      <li>Searching :  <input type="checkbox" name="domain" value="searching"></li>
                      <li>Sorting :  <input type="checkbox" name="domain" value="sorting"></li>
                      <li>Recursion :  <input type="checkbox" name="domain" value="recursion"></li>
                      <li>Graph Theory :  <input type="checkbox" name="domain" value="graph_theory"></li>
                      <li>Regex :  <input type="checkbox" name="domain" value="regex"></li>
                      <li>Math :  <input type="checkbox" name="domain" value="math"></li>
                      
                    </h5>
                  </div>
              </div>
            </div>
      </div>

    </div>
    
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h4>Question Title </h4>
          <input type="text" class="form-control" id="QuestionTitle" name="ques_title" required>
          <h4>Question</h4>
          <form>
            <input id="questionx" type="hidden" name="question">
            <trix-editor input="questionx" id = "question" style='overflow:auto;'></trix-editor>
          </form>
          <div id="editordiv"></div>
          <h4>Input Format</h4>
          <form>
            <input id="ifx" type="hidden" name="if">
            <trix-editor id="format" input="ifx" style='overflow:auto;'></trix-editor>
          </form>
          <br>
          <br>
          <h4>Output Format</h4>
          <form>
            <input id="ofx" type="hidden" name="of">
            <trix-editor id = "format" input="ofx" style='overflow:auto;'></trix-editor>
          </form>
          <br>
          <br>
          <h4>Constrain</h4>
          <form>
            <input id="constrainx" type="hidden" name="constrain">
            <trix-editor id = "format" input="constrainx" style='overflow:auto;'></trix-editor>
          </form>
          <br>
          <br>
          <h4>Solution Code</h4>
          <br>
          <div class="dropdown row" id = "selector">
  <div class="col-12">

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

          <div id="editor"></div>

        </div>
        
      </div>
      <br>
      <br>
      <h2>Add Test Cases</h2>
      <br>
      <br>
   
      <div style='display:flex'>
          <div style='margin:10px'>
            <button type="button" id="AddTestCase" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" disabled="disabled">
                Add test case
            </button>
          </div>
          <div style='margin:10px'>
              <div>
                Public Test Case : <span id='no-of-public-tc'>0</span>

              </div>
              <div>
                  Private Test Case : <span id='no-of-private-tc'>0</span>
              </div>
                
          </div>
          
       
      </div>
      
     
      
      <br>
      <br>
    
        <input type='button' class="btn btn-info btn-lg" id="MakeQuestion" value="Make Question ">


      <br>
      <br>
      <div style='display:flex;'>
          <div style="margin:10px;">
             <input type="button" class="btn btn-info btn-lg" id="done" value="Done">
          </div>
          
          <div style='margin:10px;'>
               <div class="spinner1" style='display:none'>
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
          </div>
      </div>
      
      <br>
      <br>
      <br>
      
      

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered-lg modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Test Case</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" disabled>
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <h5>Type of Input:  (By default the test cases are private)</h5>
        </div>
        <div class="row">
          <div class="col-4">
              <input type="checkbox" name="TestCaseType" id="publicx">  Public Test Case
            </div>
        </div>
        <div class="row" id="explanationTextBox">
          <div class="col-12">
          <form>
            <p>Add an explanation</p>
            <input id="explanationx" type="hidden" name="content">
            <trix-editor id = "format" input="explanationx" style="overflow: auto;"></trix-editor>
          </form>
        </div>
        </div>
        <br>
        <p>Input</p>
        <div id="editor1"></div>
        <br>
        <p>Expected Output</p>
        <div id="editor2"></div>
      </div>
      <br>
      <div class="row">
        <div class="col-12">
        Points <input id="points" type="text" name="point" required>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="addTestCase" class="btn btn-primary" data-dismiss="modal">Add Test Case</button>
      </div>
    </div>
  </div>
</div>
        
        <br>
        <br>


      </div>
      

    </div>
    
   


  </form>

<!--  <script type="text/javascript" src="public/js/ques.js"></script>-->

<script type="text/javascript">
  
  var publicc = document.getElementById("publicx");

  var explanation = document.getElementById("explanationTextBox");

  explanation.style.visibility = "hidden";

  publicc.addEventListener("change",function(){
    if(this.checked){
      explanation.style.visibility = "visible";
    }
    else{
      explanation.style.visibility = "hidden";
    }
  });


</script>

  <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>

    var editor = ace.edit("editor");
    editor.setFontSize("18px")
    var editor1 = ace.edit("editor1");
    editor1.setFontSize("18px")
    var editor2 = ace.edit("editor2");
    editor2.setFontSize("18px")
    editor.setTheme("ace/theme/twilight");
    //editor.session.setMode("ace/mode/javascript");
</script>





<script type="text/javascript">

  function checkEntered(name){
    obj = document.getElementsByName(name);
    for(var i=0; i<obj.length; i++){
          if(obj[i].type=='checkbox' && obj[i].checked==true){
            return true;
            break;
          }
        }
        return false;

  }

  var question;
  makeQuestion = document.getElementById("MakeQuestion");
  makeQuestion.addEventListener("click",function(){
      
      if(!validateNewQuestion())
      {
          return 0;
      }
      
    if(!checkEntered('lang') || !checkEntered('domain') || !checkEntered('level')){
    
      alert('please enter all the options');
      return false;
    }
    else if(editor.getValue() == '')
    {

        alert("Please Add solution to the Problem");
        return false;
        
    }
    else{
      document.getElementById("AddTestCase").removeAttribute("disabled");
      makeQuestion.setAttribute("disabled","disabled");
    var items=document.getElementsByName('lang');
        var selectedItems=[];
        for(var i=0; i<items.length; i++){
          if(items[i].type=='checkbox' && items[i].checked==true)
            selectedItems.push(items[i].value);
        }



    var items2=document.getElementsByName('domain');
        var selectedItems1=[];
        for(var i=0; i<items2.length; i++){
          if(items2[i].type=='checkbox' && items2[i].checked==true)
            selectedItems1.push(items2[i].value);
        }

    var selectedVal = "";
    var selected = $("input[type='checkbox'][name='level']:checked");
      if (selected.length > 0) {
        selectedVal = selected.val();
      }



    //console.log(document.getElementById("typeOfQuestion").value);
    //console.log( document.getElementById("questionx").value);
    question = {
      title : document.getElementById("QuestionTitle").value,
      statement : document.getElementById("questionx").value,
      language : selectedItems,
      difficulty : selectedVal,
      domain : selectedItems1,
      inputFormat : document.getElementById("ifx").value,
      outputFormat : document.getElementById("ofx").value,
      constrain : document.getElementById("constrainx").value,
      solution : editor.getValue()
    };
    console.log(JSON.stringify(question));
  }
  });

  var numberOfTestCases = 0;
  var publicTestcases = 0;
  var privateTestCase = 0;
  var TestCase = [];
  var explanation1 = "";
  var publicss = "";

  addTestCase = document.getElementById("addTestCase");
  addTestCase.addEventListener("click",function(){
    if(checkTestCase())
    {numberOfTestCases+=1;
        publicr = document.getElementsByName("TestCaseType");
        if(publicr[0].checked == true){
            publicTestcases += 1;
          publicss ='True';
          explanation1 = document.getElementById("explanationx").value;
    
        }
        else{
          privateTestCase+=1;
          publicss ='False';
          //explanation1 = document.getElementById("explanationx").value
          
        }
    
        var input = editor1.getValue();
        var output = editor2.getValue();
        var points = document.getElementById("points").value;
        test = {
          testCaseNumber : numberOfTestCases,
          privateNo : privateTestCase,
          typeOfTestCase : publicss,
          explanationOfPublic : explanation1,
          inputgiven : input,
          expectedOutput : output,
          pointsAwarded : points
        };
        TestCase.push(test);
        $("#no-of-public-tc").text(publicTestcases);
        $("#no-of-private-tc").text(privateTestCase);
        console.log(TestCase);}
  });

done = document.getElementById("done");
done.addEventListener("click",function(){
    this.disabled = true;
     if(!validateNewQuestion())
    {
          this.disabled = false;
        return 0;
    }
    if(publicTestcases < 1)
    {
        alert("Please Provide Minimum 1 Public Test Case");
        this.disabled = false;
        return 0;
    }
    if(privateTestCase < 5)
    {
        alert("Please Provide Minimum 5 Private Test Case");
        this.disabled = false;
        return 0;
    }
    
  
    
  question.numberOfTestCases = numberOfTestCases;
  question.testcases = TestCase;
  //console.log(question);
  question['eventId'] = "<?php echo $id?>";
  question['service'] = "makeQuestion";
  $.ajax({ url: "event_mw.php",
          data : question,
          type : "post",
          beforeSend: function () {
                $(".spinner1").show();
            },
            complete: function () {
                $(".spinner1").hide();  
                document.getElementById("done").disabled = false;
            },
          dataType:"json",
          success: function(info){
              console.log(info);
              
              if(info == 1)
              {
                  location.reload(true);
              }
              else if(info == 0)
              {
                  alert("An Error Occured !! Please Try Again Or login and then try !");
              }
              else{
                alert("An Error Occured !! Please Try Again Or login and then try !"); 
              }
          },
          error : function(){
            alert("An Error Occured !! Please Try Again Or login and then try !");
          }
        });
});

// made by ayush

    function validateNewQuestion()
{
    if($("#QuestionTitle").val() == '')
    {
        alert("Questitle title is Required");
        return 0;
    }

    if($("#questionx").val() == '')
    {
        alert("Question Statement Required");
        return 0;
    }

 

             if ($("#ifx").val() == "") {
               alert("Input Format Required");
               return 0;
             }
                if ($("#ofx").val() == "") {
                  alert("Output Format Required");
                  return 0;
                }
            
            
              if ($("#constrainx").val() == "") {
                  alert("constrain Required");
                  return 0;
                }
 

    return 1;

}


function checkTestCase(){
  if(editor1.getValue() == ""){
    alert("Please enter the input.");
    return false;
  }

  if(editor2.getValue() == ""){
    alert("Please enter the output.");
    return false;
  }

  if ($("#points").val()=="" || isNaN($("#points").val())) {
    alert("Please enter valid points.");
    return false;
  }

  return true;
}

</script>




<script type="text/javascript">
  var e = document.getElementById("LanguageSelector");
  e.addEventListener("change",function(){
    var strUser = e.options[e.selectedIndex].value;
    if(strUser == "python"){
      editor.session.setMode("ace/mode/python");
    }
    if(strUser == "cpp"){
      editor.session.setMode("ace/mode/c_cpp");
    }
    if(strUser == "java"){
      editor.session.setMode("ace/mode/java");
    }
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
</script>

<script type='text/javascript'>


function check(){
  $.ajax({
    url:"Account/checkUser.php",
    type:"get",
    dataType:"json",
    success:function(json){
      // console.log(json);
      if(json.status != 1)
      {
        alert('You Are Logged Out Of Inactivity')
      }
    }

  })
}


setInterval(check, 120000);

</script>










</body>

</html>