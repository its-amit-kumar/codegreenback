$(document).ready(function(){
    // alert("ayush");
    // setting_load("profile", "s-profile");
 
    profile();
    $("#s-profile-but").click(profile);

    $("#s-notification-but").click(NotificationSett);
    $("#s-pass-but").click(changePass);





});


var request = null;
function setting_load(service, cont)
{

    $.ajaxSetup({ 
        cache: false,
    }); 
    if (request != null) {
    request.abort();
    request = null;
    }

    $request = $.ajax({
      url: "https://www.codegreenback.com/accounts/get_account_settings.php",
      type: "post",
      dataType: "json",
      data: {
        service: service,
      },
      async:true,
      beforeSend: function () {
        $(".s-content").hide();
        $("#loader").show();
      },
      complete: function(){
        $(".s-content").hide();
        $("#loader").hide();
        $("#"+cont).show();

      },

      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      success: function (data) {
        console.log(data);
        if (data.status == 1) {
          switch (service) {
            case 'profile':
                makeProfileSettings(data);
              break;
            case 'Notification':
                makeNotificationSettings(data);
                break;
            case 'Code-Coins-transaction':
                makeCodeCoinSetting(data);
          }
        } else {
          
        }
      },
    });

    return true;
}


function makeProfileSettings(data)
{
  $("#s-username").text(data.username);
  $("#s-name").text(data.name);
  $("#s-user-image").attr('src','https://www.codegreenback.com/'+data.img);
  $("#change-name").val(data.name);
  $("#website").val(data.details.website);
  $("#about").val(data.details.about);
  var gender = data.details.gender
  if(gender != '')
  {
    $("#"+gender).prop("checked", true).trigger("click");
  }

  $("#s-email").text(data.email);
}

function changePass(){
  var flag = false;
   $(".s-content").hide();
   $("#loader").hide();
   $("#s-changePassword").show();
   $(".s-panel-but").removeClass("s-active");
   $("#s-pass-but").addClass("s-active");
   //  changePass();

   var currentpass = "";
   var newpass = "";
   var re_newpass = "";
   $("#current-pass").keyup(function () {
     currentpass = $("#current-pass").val();
   });

  

   $("#password").keyup(function () {
     newpass = $("#password").val();
     if (re_newpass != '' && re_newpass == newpass) {
       $("#icon-correct").show();
       $("#icon-incorrect").hide();
       $("#save-changes-cp").removeClass("cursor-non");
       $("#save-changes-cp").addClass("cursor-pointer");
       flag = true;
     } else {
       $("#icon-correct").hide();
       $("#icon-incorrect").show();
       $("#save-changes-cp").addClass("cursor-non");
       $("#save-changes-cp").removeClass("cursor-pointer");
       flag = false;
     }

      if (newpass == "" && re_newpass == "") {
        $("#icon-correct").hide();
        $("#icon-incorrect").hide();
        $("#save-changes-cp").addClass("cursor-non");
        $("#save-changes-cp").removeClass("cursor-pointer");
        flag = false;
      }
   });

   $("#password-again").keyup(function () {
     re_newpass = $("#password-again").val();
     if (re_newpass != ''  && re_newpass == newpass) {
       $("#icon-correct").show();
       $("#icon-incorrect").hide();
       $("#save-changes-cp").removeClass("cursor-non");
       $("#save-changes-cp").addClass("cursor-pointer");
       flag = true;
     } else {
       $("#icon-correct").hide();
       $("#icon-incorrect").show();
       $("#save-changes-cp").addClass("cursor-non");
       $("#save-changes-cp").removeClass("cursor-pointer");
       flag = false;
     }

      if (newpass == "" && re_newpass == "") {
        $("#icon-correct").hide();
        $("#icon-incorrect").hide();
        $("#save-changes-cp").addClass("cursor-non");
        $("#save-changes-cp").removeClass("cursor-pointer");
        flag = false;
      }
   });

   $("#save-changes-cp").click(function(){
      if(flag)
      {
        // process and validate
        // flag = false;
        changePassValidate(currentpass);
      
      }
      
   });

}

function changePassValidate(currentpass){
  // var forms = document.getElementById("changePassForm");
  // var data = new FormData($("#changePassForm"));
  // console.log(data);
  if(currentpass == ''){
    $("#s-cp-errorMsg").text("Current Password Required");
    $("#s-cp-errorMsg-div").fadeIn();
    setTimeout(function () {
      $("#s-cp-errorMsg-div").fadeOut();
    }, 3000);
    return false;
  }

  const formData = new FormData(document.getElementById("changePassForm"));

  $.ajax({
    url: "https://www.codegreenback.com/accounts/changePassword.php",
    type: "POST",
    data: formData,
    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    processData: false, // NEEDED, DON'T OMIT THIS
    dataType: "json",
    headers: {
      Authorization: "Bearer " + localStorage.getItem("sid"),
    },
    beforeSend: function () {
      $("#loader-ajax").show();
    },
    complete: function () {
      $("#loader-ajax").hide();
    },
    success: function (data) {
      // console.log(data);
      if(data.status == 1)
      {
           $("#s-cp-errorMsg").text(""+data.msg);
           $("#s-cp-errorMsg-div").fadeIn();
           setTimeout(function () {
             $("#s-cp-errorMsg-div").fadeOut();
           }, 5000);
      }
      else if(data.status == 0)
      {
          $("#s-cp-errorMsg").text("" + data.error);
          $("#s-cp-errorMsg-div").fadeIn();
          setTimeout(function () {
            $("#s-cp-errorMsg-div").fadeOut();
          }, 3000);
      }
    },
  });

}

var request2 = null;
function profile()
{
  $(".s-panel-but").removeClass("s-active");
  $("#s-profile-but").addClass("s-active");
  setting_load("profile", "s-profile");
  var name = '';
  var website = '';
  var about = '';
  var gender = ''
  $("#change-name").keyup(function(){
    name = $("#change-name").val();
    $("#s-name").text(name);
    $("#save-changes-profile").removeClass("cursor-non");
    $("#save-changes-profile").addClass("cursor-pointer");
    if(name == '')
    {
      $("#error-icon").show();
    }
    else{
      $("#error-icon").hide();
    }
  });

  $("#website").keyup(function(){
    website = $("#website").val();
    $("#save-changes-profile").removeClass("cursor-non");
    $("#save-changes-profile").addClass("cursor-pointer");
  });

  $("#about").keyup(function(){
    about = $("#about").val();
    $("#save-changes-profile").removeClass("cursor-non");
    $("#save-changes-profile").addClass("cursor-pointer");
  });

  var genderButtons = document.querySelectorAll(".s-profile-input-radio");
  for( var i = 0; i<genderButtons.length ;i++)
  {
    genderButtons[i].addEventListener("click", function(){
      gender = this.value;
      $("#save-changes-profile").removeClass("cursor-non");
      $("#save-changes-profile").addClass("cursor-pointer");
    });
  }

 
  var status = true;
  $("#save-changes-profile").click(function(){

    if(status = true)
    {
      if(validate('change-name'))
      {
         $("#error-icon").hide();
        const dataform = new FormData(
          document.getElementById("profileForm")
        );
        dataform.append("service", "updateProfile");

        $.ajaxSetup({
          cache: false,
        });
        if (request2 != null) {
          request2.abort();
          request2 = null;
        }

        $request2 = $.ajax({
          url: "https://www.codegreenback.com/accounts/get_account_settings.php",
          type: "post",
                 // dataType: "json",
          contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
          processData: false, // NEEDED, DON'T OMIT THIS
          data: dataform,

          headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
          },
          beforeSend: function () {
            status = false;
            $("#loader-ajax-profile").show();
          },
          complete: function () {
            $("#loader-ajax-profile").hide();
            status = true;
          },
          success: function (json) {
            console.log(json);
          },
        });
      }
      else{
        $("#error-icon").show();
      }

    }
      



  

  });
}

function validate(id) {                               //Name Validation
  var regex = /^[a-zA-Z ]{2,30}$/;
  var ctrl = document.getElementById(id);

  if (regex.test(ctrl.value)) {
    return true;
  } else {
    return false;
  }
}


function NotificationSett()
{
    setting_load("Notification", "s-notification");
    $(".s-panel-but").removeClass("s-active");
    $("#s-notification-but").addClass("s-active");
    var inp = document.querySelectorAll(".notificationInputs");
    for(var i = 0; i< inp.length ; i++)
    {
      inp[i].addEventListener('click',function(){

      });
    }
     var status = true;
     request2 = null;
    $("#savechanges-not-but").click(function(){
     
      if(status = true)
      {
        const dataform = new FormData(document.getElementById("NotificationForm"));
        dataform.append("service", "updateNotificationSetting");

        $.ajaxSetup({
          cache: false,
        });
        if (request2 != null) {
          request2.abort();
          request2 = null;
        }

        $request2 = $.ajax({
          url: "https://www.codegreenback.com/accounts/get_account_settings.php",
          type: "post",
          dataType: "json",
          contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
          processData: false, // NEEDED, DON'T OMIT THIS
          data: dataform,

          headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
          },
          beforeSend: function () {
            status = false;
            $("#loader-ajax-Notification").show();
          },
          complete: function () {
            $("#loader-ajax-Notification").hide();
            status = true;
          },
          success: function (json) {
            console.log(json);
            if(json.status = 1)
            {
              $("#s-not-Msg").text("" + json.msg);
              $("#s-not-Msg-div").fadeIn();
              setTimeout(function () {
                $("#s-not-Msg-div").fadeOut();
              }, 4000);
            }
            else if(json.status = 0)
            {
              $("#s-not-Msg").text("" + json.msg);
              $("#s-not-Msg-div").fadeIn();
              setTimeout(function () {
                $("#s-not-Msg-div").fadeOut();
              }, 5000);
            }
          }

        });

      }
    });
  
}


function makeNotificationSettings(data)
{
  
  if(data.general == "1")
  {
    $("#not-set-general").prop("checked", false).trigger("click");
  }else{
    $("#not-set-general").prop("checked", true).trigger("click");
  }

  if (data.challengeRequests == "1") {
    $("#not-set-challengeRequest").prop("checked", false).trigger("click");
  } else {
    $("#not-set-challengeRequest").prop("checked", true).trigger("click");
  }

  if (data.challengeAccept == "1") {
    $("#not-set-challengeAccept").prop("checked", false).trigger("click");
  } else {
    $("#not-set-challengeAccept").prop("checked", true).trigger("click");
  }

  if (data.push == "1") {
    $("#not-set-push").prop("checked", false).trigger("click");
  } else {
    $("#not-set-push").prop("checked", true).trigger("click");
  }

}








// image uploade 

$(document).ready(function(){

  $("#s-upload-but").click(function(){
    $("#upload-image").trigger("click");
  });

   $image_crop = $("#image_demo").croppie({
     enableExif: true,
     viewport: {
       width: 200,
       height: 200,
       type: "circle", //circle
     },
     boundary: {
       width: 400,
       height: 400,
     },
   });

  $("#upload-image").on("change", function(){
     var reader = new FileReader();
     reader.onload = function (event) {
       $image_crop
         .croppie("bind", {
           url: event.target.result,
         })
         .then(function () {
           console.log("jQuery bind complete");
         });
     };
     reader.readAsDataURL(this.files[0]);
     $("#uploadimageModal").modal("show");
  });

    $(".crop_image").click(function (event) {
      $image_crop
        .croppie("result", {
          type: "canvas",
          size: "viewport",
        })
        .then(function (response) {
          $.ajax({
            url: "https://www.codegreenback.com/accounts/get_account_settings.php",
            type: "POST",
            dataType:'JSON',
            data: {
              image: response,
              service: "uploadNewImg",
            },
            headers: {
              Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            success: function (data) {
              //console.log(data);
              $("#uploadimageModal").modal("hide");
	      if(data.status)
              {
                $("#s-user-image").attr("src",`https://www.codegreenback.com/${data.imguri}`);
              }
              else
              {
                alert(data.msg);
              }
              // $("#s-user-image").attr('src',data);
            },
          });
        });
    });


 $("#s-remove-but").click(function(){
      if ($("#s-user-image").attr("src") == 'https://www.codegreenback.com/public/img/avatar.png')
      {
        
        return 0;
      }
        $.ajax({
          url: "https://www.codegreenback.com/accounts/get_account_settings.php",
          type: "POST",
          dataType: "JSON",
          beforeSend: function () {
            $("#changeProfilePic").modal("hide");
          },
          data: {
            service: "rmi",
          },
          headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
          },
          success: function (data) {
            if (data.status) {
              $("#s-user-image").attr(
                "src",
                "https://www.codegreenback.com/public/img/avatar.png"
              );
            } else {
              alert("Cannot Process Request !! Please try again");
            }
          },
        });
    })

})



