// alert("hello");

window.onload = makebuttonsclickable;


function makebuttonsclickable(){
    var usernameBut = document.getElementById("headerUsername");
    var userTopConsole = document.getElementById("userTopConsole");

    var challengeRequests = document.getElementById("challengeRequests");
    var notification = document.getElementById("notification");


    var userReq = document.getElementById("userReq");
    var yourReq = document.getElementById("yourReq");

    // event listener for top right user console display
    usernameBut.addEventListener("click", function () {
      if (userTopConsole.style.display === "none") {
        userTopConsole.style.display = "block";
      } else {
        userTopConsole.style.display = "none";
      }
    });

    userTopConsole.addEventListener("mouseleave",function(){
        this.style.display = "none";
    });

    //event listener for challenge requests

    challengeRequests.addEventListener("click",function(){
      if (notification.style.display === "none"){
        notification.style.display = "block";
      }
      else {
        notification.style.display = "none";
      }

      notification.addEventListener("mouseleave",function(){
        this.style.display = "none";
      })

    });

    // event listner for  your challenges

    userReq.addEventListener("click", function(){
      if(yourReq.style.display == "block"){
          yourReq.style.display = "none";
      }
      else{
        yourReq.style.display = "block";
      }

      yourReq.addEventListener("mouseleave",function(){
        this.style.display = "none";
      })


    });



}

