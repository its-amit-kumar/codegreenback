  
function setCountDown(start){
            // Set the date we're counting down to
    var countDownDate = new Date(start).getTime()+86400000;

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("demo").innerHTML ="<h2>" + hours + "h " + minutes + "m " + seconds + "s </h2>";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);

}




$(document).ready(function () {
  var editor = ace.edit("editor");
  editor.setValue("#Type your code here");
  editor.setTheme("ace/theme/monokai");
  editor.session.setMode("ace/mode/javascript");
  editor.setTheme("ace/theme/chrome");
  editor.setFontSize("20px");
  editor.resize();

//   .........................language Selection..........................//

  var selectedLang = document.querySelectorAll(".selectedLang");
  var lang = "";

  for (var i = 0; i < selectedLang.length; i++) {
    selectedLang[i].addEventListener("click", function () {
      switch (this.value) {
        case "python":
          editor.session.setMode("ace/mode/python");
          lang = "python";
          $("#showlang").html("Python");

          break;
        case "java":
          editor.session.setMode("ace/mode/java");
          lang = "java";
          $("#showlang").html("Java");
          break;
        case "cpp":
          editor.session.setMode("ace/mode/c_cpp");
          lang = "cpp";
          $("#showlang").html("CPP");
          break;
	case "c":
          editor.session.setMode("ace/mode/c_cpp");
          lang = "c";
          $("#showlang").html("C");
          break;
	case "ruby":
          editor.session.setMode("ace/mode/ruby");
          lang = "ruby";
          $("#showlang").html("Ruby");
          break;
	case "javascript":
          editor.session.setMode("ace/mode/javascript");
          lang = "javascript";
          $("#showlang").html("Javascript");
          break;
	case "swift":
          editor.session.setMode("ace/mode/swift");
          lang = "swift";
          $("#showlang").html("Swift");
          break;
	case "bash":
          editor.session.setMode("ace/mode/batchfile");
          lang = "bash";
          $("#showlang").html("Bash");
          break;
	case "erlang":
          editor.session.setMode("ace/mode/erlang");
          lang = "erlang";
          $("#showlang").html("Erlang");
          break;
	case "haskell":
          editor.session.setMode("ace/mode/haskell");
          lang = "haskell";
          $("#showlang").html("Haskell");
          break;

      }
    });
  }

//   .........................Theme Selection ............................//

  var selectedTheme = document.querySelectorAll(".themeChange");
  for (var i = 0; i < selectedTheme.length; i++) {
    selectedTheme[i].addEventListener("click", function () {
      switch (this.value) {
        case "chrome":
          editor.setTheme("ace/theme/chrome");
          break;
        case "merbivore_soft":
          editor.setTheme("ace/theme/merbivore_soft");
          break;
      }
    });
  }

//  ........................ Font Slider ..................................//
  var slider = document.getElementById("myRange");
  slider.oninput = function () {
    editor.setFontSize(this.value + "px");
  };

//  ...................Custom Checked ....................................//
var customChecked = 0;
$("#custom-input-but").click(function(){
    if(customChecked)
    {
        customChecked = 0;
        $("#custom-input-div").fadeOut();
        $("#custom-input-but").removeClass("active");
    }
    else{
        customChecked = 1;
        $("#custom-input-div").fadeIn();
        $("#custom-input-but").addClass("active");
        document.getElementById("testcase").scrollIntoView();
        $("#testcase").focus();
    }
    
});

var output_win = 0;
$("#output-window-but").click(function () {
  if (output_win) {
    output_win = 0;
    $("#output-divs").fadeOut();
    $("#output-window-but").removeClass("active");
  } else {
    output_win = 1;
    $("#output-divs").fadeIn();
    $("#output-window-but").addClass("active");
    document.getElementById("output-divs").scrollIntoView();
  }
});

$("#minimise-output-window").click(function(){
    output_win = 0;
    $("#output-divs").fadeOut();
    $("#output-window-but").removeClass("active");
});


//  ........................ Code Runner ..................................//

  $("#runcode").click(function () {

    if(lang == '')
    {
      alert('Please select a language !');
      return 0;
    }

    $(document).ajaxStart(function () {
      $("#overlay").show();
      $("#output").hide();
    });

    $(document).ajaxStop(function () {
      $("#overlay").hide();
      $("#output").show();
    });

    if (customChecked) {
      var usercode = editor.getValue();
      var userTestCase = $("#testcase").val();
      var token = $("#verify").val();
      $.ajax({
        type: "post",
        data: {
          code: usercode,
          lang: lang,
          custominput: userTestCase,
        },
        headers: {
          Authorization: "Bearer " + token,
        },
        url: "https://www.codegreenback.com/mw/challenge1_mw.php",
        dataType: "json",
        success: function (result) {
          console.log(result);
          PrintCodeOutput(result, 1);
        },
      });
    } else {
      var usercode = editor.getValue();
      var token = $("#verify").val();
      $.ajax({
        type: "post",
        data: {
          code: usercode,
          lang: lang,
        },
        headers: {
          Authorization: "Bearer " + token,
        },
        dataType: "json",
        url: "https://www.codegreenback.com/mw/challenge1_mw.php",
        success: function (result) {
          console.log(result);
          if (result == 1) {
            onCodeSuccess();
          } else {
            PrintCodeOutput(result, 0);
          }
          // $("#output").html(result);
        },
      });
    }
    console.log(editor.getValue());
    console.log(lang);
  });


  function onCodeSuccess() {
    var count = 3;
    $("#c3-model").html(`  <div class="c3-modal-content">

    <div class='c3-header'>
      <h3>Code successfully compiled !!</h3>
    </div>

    <div class='c3-body'>
      <p id="c3-body-msg">Redirecting in </p> 
    </div>

    <div class='c3-timer-div'>
      <p id="c3-timer">${count}</p>
</div>
    
  </div>`);

  $("#c3-model").fadeIn();
  

  var timer = setInterval(function(){
      if(count != 0 ){
        $("#c3-timer").text(count);
        count = count -1;
      }
      else{
        window.location.href = 'https://www.codegreenback.com/';
      }
  },1000);

  }

  function PrintCodeOutput(data, custominput) {
    if (custominput == 1) {
      if (data.error) {
        $(".msg").fadeOut();
        $("#error").fadeIn();
        $("#errormsg").html(data.error);
      } else {
        $(".msg").fadeOut();
        $("#customInputRow").fadeIn();
        $("#userinput").html(data.input);
        $("#output").html(data.output);
      }
    } else if (custominput == 0) {
      if (data.error) {
        $(".msg").fadeOut();
        $("#error").fadeIn();
        $("#errormsg").html(data.error);
      } else {
        $(".msg").fadeOut();
        $("#OutputRow").fadeIn();
        $("#input").html(data.input);
        $("#expectedOutput").html(data.expectedOutput);
        $("#YourOutput").html(data.output);
      }
    }

    document.getElementById('output-divs').scrollIntoView();
    $("#output-divs").fadeIn();
    output_win = 1;
    $("#output-window-but").addClass("active");


  }

  //ajax request for question fetching

  function FetchQuestion(id) {
    var token = $("#verify").val();
    $.ajax({
      type: "get",
      data: {
        fetchques: "true",
      },
      headers: {
        Authorization: "Bearer " + token,
      },
      url: "https://www.codegreenback.com/mw/challenge1_mw.php",
      dataType: "json",
      success: function (result) {
        console.log(result);
        if (result == -1) {
          alert("You Have Completed Your Side Of Challenge !!");
          endChallenge();
        } else {
          window.quesid = result.id;
          // $("#ques_title").text(result.title);
          // $("#ques").text(result.ques);
          displayQuestion(result);
        }
      },
    });
  }

  function displayQuestion(data)
  {
    console.log(data);
    $("#ques").html(data['ques']['problem']);
    $("#i-format-div").html(data['ques']['inputformat']);
    $("#o-format-div").html(data['ques']['outputformat']);
    $("#constrain-div").html(data['ques']['constrain']);
    $("#si-div").html(data['ques']['sampleinput']);
    $("#so-div").html(data["ques"]["sampleoutput"]);

  }

  function endChallenge() {
    var token = $("#verify").val();

    $.ajax({
      type: "get",
      url: "https://www.codegreenback.com/mw/challenge1_mw.php",
      data: {
        endchallenge: "true",
      },
      headers: {
        Authorization: "Bearer " + token,
      },
      success: function () {
        window.location.replace("https://www.codegreenback.com/Home/");
      },
    });
  }

  console.log(id); //challenge id
  FetchQuestion(id);
  var quesid = "";
  //for current question id

  $("#endchallenge").click(function () {
    if (confirm("End Challenge ? ")) {
      endChallenge();
    }
  });

  getChallengeTimeDetails(id);
});


function runtimer(x){
    var timenow = new Date().getTime();
    var startTime = new Date(x).getTime();
    var distance = timenow-startTime;
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("demo1").innerHTML ="<h1 style='margin:auto'>"+ hours + "h "
    + minutes + "m " + seconds + "s </h1>";
}



function getChallengeTimeDetails(id){
    var token = $("#verify").val();
    $.ajax({
        type:"get",
        url:"https://www.codegreenback.com/mw/challenge1_mw.php",
        data:{
            service:"challengeTimeDetails"
        },
        headers:{
            Authorization: 'Bearer '+token
        },
       dataType:"json",
        success:function(result) {
            console.log(result);
            setCountDown(result.d_e_t);
            setInterval(runtimer,1000,result.startTime);
        }

    })  ;      
}
                        


     // Get the navbar
    var time_panel = document.getElementById(
    "time-panel"
    );

    var main_content = document.getElementById("main-content");
    // Get the offset position of the navbar
    var sticky = time_panel.offsetTop;
    // When the user scrolls the page, execute myFunction
    window.onscroll = function () {
     setTimerHeader();
    };

    // Add the sticky class to the navbar when you reach its position. Remove "sticky" when you leave the scroll position
    function setTimerHeader() {
    if (window.pageYOffset >= sticky) {
        time_panel.classList.add("sticky");
        main_content.classList.add("xyz");
    } else {
        time_panel.classList.remove("sticky");
        main_content.classList.remove("xyz");
    }
    }



    $(document).ready(function () {
      $("#full_screen").on("click", function () {
        var elem = document.getElementById("main-content");
        if (
          document.fullscreenEnabled ||
          document.webkitFullscreenEnabled ||
          document.mozFullScreenEnabled ||
          document.msFullscreenEnabled
        ) {
          if (
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
          ) {
            if (document.exitFullscreen) {
              document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
              document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
              document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
              document.msExitFullscreen();
            }
          } else {
            if (elem.requestFullscreen) {
              elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
              elem.webkitRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
              elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
              elem.msRequestFullscreen();
            }
          }
        } else {
          console.log("Fullscreen is not supported on your browser.");
        }

        
      });
    });


    // Make the DIV element draggable:
dragElement(document.getElementById("output-divs"));

function dragElement(elmnt) {
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  if (document.getElementById(elmnt.id + "header")) {
    // if present, the header is where you move the DIV from:
    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
  } else {
    // otherwise, move the DIV from anywhere inside the DIV:
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    // stop moving when mouse button is released:
    document.onmouseup = null;
    document.onmousemove = null;
  }
}
