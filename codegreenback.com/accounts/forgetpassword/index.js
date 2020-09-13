$(document).ready(function(){
    //
  $("#f-send-but").click(function(){
    $("#f-send-but").prop("disabled", true);
    setCaptcha('g-token', ProcessForgetPass);
  });

        
});


function displayMsg(msg)
{
    $("#f-msg").text(msg);
    $(".f-footer-msg").fadeIn();

        setTimeout( function(){
            $(".f-footer-msg").fadeOut();
        }, 3500);

}

function ProcessForgetPass()
{
  const email = $("#f-email").val();
  if(email === '')
  {
    displayMsg('Email Address Required !')
    $("#f-send-but").prop("disabled", false);
  }


  if(email !== '')
  {
    if(isEmail(email))
    {
      processRequest(email);
    }
    else{
      displayMsg("Invalid Email Address :(");
      $("#f-send-but").prop("disabled", false);
    }
  }
}

async function setCaptcha(id, _callback) {
  var inp = document.getElementById(id);
  await grecaptcha
    .execute("6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK", { action: "homepage" })
    .then(function (token) {
      inp.value = token;
    });

  _callback();
}


function isEmail(email) {
  var filter = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return filter.test(email);
}

request = null;
function processRequest(email)
{
  if(!isEmail(email))
  {
    return 0;
  }
  const g_token = $("#g-token").val();
  const token = $("#f-token").val();

  request = $.ajax({
    url:"processRequest.php",
    type:"POST",
    dataType:"json",
    data:{
      "email" : email,
      "g-token" : g_token,
      "token" : token,
      "service": "F_P"
    },
    beforeSend: function(){
        if (request != null) {
          request.abort();
          request = null;
        }
      
      $(".loader").show();
      
    },

    complete: function(){
      $(".loader").hide();
      $("#f-send-but").prop("disabled" , false);
    },
    success: function(json){
      console.log(json);
      if(json.status == 1)
      {
        displayMsg(json.msg);
      }
      else{
        displayMsg(json.error);
      }
    }
  })
}