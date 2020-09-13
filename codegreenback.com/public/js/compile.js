

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
    }
    
});


$("#runcode").click(function () {
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
          'service':"Compile_and_run"
        },
        headers: {
          Authorization: "Bearer " + token,
        },
        url: "mw/compile_mw.php",
        dataType: "json",
        success: function (result) {
          console.log(result);
          PrintCodeOutput(result, 1);
        }
      });
    }
    else{
        alert("Please Click On Custom Input");
    }

});


});

function PrintCodeOutput(data, custominput) {
  if (custominput == 1) {
    if (data.error) {
      $(".msg").fadeOut();
      $("#error").fadeIn();
      $("#errormsg").html(data.error);
    } else {
      $(".msg").fadeOut();
      $("#customInputRow").fadeIn();
      $("#userinput").text(data.input);
      $("#output").text(data.output);
    }
  } 
}



