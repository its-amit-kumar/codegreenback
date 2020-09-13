(function(){

  const data_ = {
    totalRequest: 0,
    totalChallenges: 0,
    generalNotification: 0,
    friendRequests: 0
  }

  if(user_type === "non-elite")
  {
    $("#user-type").text("non-elite");
  }
  else if(user_type === 'elite')
  {
    $("#user-type").html("<img src='https://www.codegreenback.com/public/img/elitelogo.png' alt='elitelogo' style='width:70px;height:70px'>");
  }

  window.onload = makebuttonsclickable;

  function makebuttonsclickable() {
var challengeRequests = document.getElementById("challengeRequests");
    var notification = document.getElementById("notification");

    var userReq = document.getElementById("userReq");
    var yourReq = document.getElementById("yourReq");

    var friendReqBut = document.getElementById("friendReqBut");
    var friendRequests = document.getElementById("friendRequests");

    var generalNotificationBut = document.getElementById(
      "generalNotificationBut"
    );
    var generalNotification = document.getElementById("generalNotification");


    // event listner for  your challenges

    userReq.addEventListener("click", function () {
      userChallenges();
    });

    // event listener for friend requests

    friendReqBut.addEventListener("click", function () {
      getFriendReq();

    });

    // event listener for general Notification;

    generalNotificationBut.addEventListener("click", function () {

      getGeneralNotification();

    });



    getnot();
  }

getnot();

  // user past challenges info  will be retrieved from backend through ajax

  function getnot() {
    $.ajax({
      url: "https://www.codegreenback.com/mw/userchallenges_mw.php",
      dataType: "json",

      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      success: function (result) {
        makenotification(result);
      },
    });
  }

  //function to build the notification
  function makenotification(result) {
    var allowedlen = 0;

    if (result.length != 0) {
      var len = result.length;

      data_.totalRequest = len;

      not = "";
      a = $("#numChallengeReq").html();
      if (len != a) {
        $("#numChallengeReq").html("" + len);
        $("#numChallengeReq").show();

        for (var i = 0; i < len; i++) {
          var id = result[i].id;
          var challengerName = result[i].challenger;
          var challenge = result[i].challengeName;
          var cc = result[i].cc;
          var time = gettimestamp(result[i].t_o_r);
          var a_status = result[i].a_status;
          var cookie = getcookiedata();
          // console.log(cookie);                                     //cookie as an object key:value
          var usercc = cookie.cc;
          var status = result[i].status;
          if (parseInt(usercc) < parseInt(cc) && status != 1) {

            not += `
            <div style="display: flex;justify-content: center;align-items: center;">
              <div>
								<img class="round" src="https://www.codegreenback.com/${result[i].challenger_img}" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>${challengerName}</h3>
								<p>wants to challange you in <br><p1 style="font-weight: bold;">${challenge}</p1></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<span class="red" >${cc}</span>Code Coins
								<p  style="margin-top: 13px;">Buy Cc to Accept </p>
              </div>
              </div>`;

          } else if (status == -1) {
            not += `
            <div style="display: flex;justify-content: center;align-items: center;">
              <div>
								<img class="round" src="https://www.codegreenback.com/${result[i].challenger_img}" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>${challengerName}</h3>
								<p>wants to challange you in <br><p1 style="font-weight: bold;">${challenge}</p1></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<span class="red" >${cc}</span>Code Coins
								<button class='accept' value='${id}' style='background-color:white;border:none' ><i class="fa fa-check-circle accept" value='${id}' style="font-size:38px;color:#12c46b;"></i></button>
								<button class='decline' value='${id}' style='background-color:white;border:none' ><i class="fa fa-times-circle"  style="font-size:38px;color:red;"></i></button>
              </div>
              </div>`;

            allowedlen++;

          } else if (status == 1 && a_status == 1) {


            not += `
            <div style="display: flex;justify-content: center;align-items: center;">
              <div>
								<img class="round" src="https://www.codegreenback.com/${result[i].challenger_img}" alt="user" />
							</div>
							
							<div class="adjusttext">
								<h3>${challengerName}</h3>
								<p>wants to challange you in <br><p1 style="font-weight: bold;">${challenge}</p1></p>
							</div>
							<div class="coinbadge" style="font-size: 16px;">
								<span class="red" >${cc}</span>Code Coins
								<button class='accept' value='${id}' style='background-color:white;border:none' ><i class="fa fa-check-circle accept" value='${id}' style="font-size:15px;color:#12c46b;">Resume</i></button>
              </div>
              </div>`;

            allowedlen++;
          } else if (status == 1 && a_status == 2) {
            not +=
              '<li style="display: flex; justify-content: center; align-content: center; flex-wrap: wrap;"><div style="width: 70%; display: flex; align-self: center; ">' +
              challengerName +
              " has challenged you in " +
              challenge +
              " with a cc of " +
              cc +
              " <span id='time' style='width: 25%; padding-top: 4%;'>" +
              time +
              " </span></div><div>Check Challenge Status</div></li>";
          }

          // console.log(cc);
        }
        document.getElementById("notification").innerHTML = not;
        // $("#notification").html(not);
        make(allowedlen, result);
      } else {
        $("#numChallengeReq").show();
      }
    } else {
      $("#numChallengeReq").html("");
      $("#numChallengeReq").hide();

      var not =
        "<h3 style='margin: 0 auto; padding:20px; text-align:center'>No Challenge Requests !</h3>";
      // $("#notification").html(not);
      document.getElementById("notification").innerHTML = not;
    }
  }

  

  function make(len, result) {
    var accept_buttons = document.querySelectorAll(".accept");
    var decline_buttons = document.querySelectorAll(".decline");


    for (var i = 0; i < len; i++) {
      accept_buttons[i].addEventListener("click", function () {
        
        if (confirm("Start Challenge Now ? ")) {
          //alert("you will be redirected to challenge page shortly !!! work in progress")
          // $url = "http://localhost/texteditor_c1.php?id="+this.value;
          $.ajax({
            type: "POST",
            url: "https://www.codegreenback.com/mw/challenge_mw.php",
            data: {
              request: "accept",
              data: result,
              id: this.value,
              user: "accepter",
            },
            dataType: "json",
            headers: {
              Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            success: function (json) {
              
              if (json.status == 1) {
                window.location.replace(json.url);
              } else if (json.status == 0) {
                alert(json.msg);
              }
            },
          });
        }

        /*now ask for confirmation . if confirmed make an ajax request to another middleware which will set a session
                   and redirect this user to the text editor page using javascript             */
      });
    }

    for (var i = 0; i < decline_buttons.length; i++) {
      decline_buttons[i].addEventListener("click", function () {
       
        if (confirm("Are You Sure You Want To Delete This Request ? ")) {
          //alert("Work In Progress !!!")
          $.ajax({
            type: "POST",
            url: "https://www.codegreenback.com/mw/challenge_mw.php",
            data: {
              request: "decline",
              data: result,
              id: this.value,
              user: "accepter",
            },
            headers: {
              Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            success: function (a) {
              alert("success");
             
            },
          });
        }

        /*now ask for confirmation . if confirmed make an ajax request to another middleware which will set a session
                   and redirect this user to the text editor page using javascript             */
      });
    }
  }

  //function to get cookie data as key value pair

  function getcookiedata() {
    var cookie = document.cookie;
    
    cookie = cookie.split("; ");
   
    var len = cookie.length;
    var data = {};

    for (var i = 0; i < len; i++) {
      cookie[i] = cookie[i].split("=");
      data[cookie[i][0]] = cookie[i][1];
    }
    return data;
  }

  // function to get timestamps of the notifications

  function gettimestamp(d) {
    var date = new Date(d);
    var currentTime = new Date();
    time_in_sec = Math.floor((currentTime.getTime() - date.getTime()) / 1000);
    if (time_in_sec < 60) {
      return time_in_sec + "sec Ago";
    } else if (Math.floor(time_in_sec / 60) < 60) {
      return Math.floor(time_in_sec / 60) + "Min Ago";
    } else {
      return Math.floor(time_in_sec / 3600) + "H Ago";
    }
  }

  setInterval(checkNewNotification, 20000);

  function checkNewNotification()                                          //send 1 request to the server for all t
  {
    $.ajax({
	    url: "https://www.codegreenback.com/mw/makeMehappen.php",
      type: "GET",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      data: {
        _ser: "_challenge",
        _tr: data_.totalRequest,
        _tc: data_.totalChallenges,
      },
      dataType : "JSON",
      success : function(data)
      {
       
        if(data.status == 1)
        {
         
          if(data["_cr"]["status"] == 1) {
            makenotification(data._cr.data);
          }

          if (data["_uc"]["status"] == 1) {
            make_user_request_status(data._uc.data);
          }
        }
      }
    });
  }

  //   for user requests status  //////////////////////////////////////////////////////////////////////////////////////////////////

  userChallenges();

  function userChallenges() {

    $.ajax({
      url: "https://www.codegreenback.com/mw/userRequest_mw.php",
      dataType: "json",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      success: function (result) {
      
        if (result == 0) {
          //  you dont have requested any challenges
          $("#yourReq").html(
            "<h3 style='margin: 0 auto; padding:20px; text-align:center'>You did Not Challenge anyone !</h3>"
          );
          $("#numUserReq").html("");
          $("#numUserReq").hide();
          data_.totalChallenges = 0;
        } else {
          make_user_request_status(result);
        }
      },
    });
  }

  function make_user_request_status(result) {
    var len = result.length;
    var not = "";

    data_.totalChallenges = 0;

    // a = $("#numUserReq").html();
    a = 0;
    if (len != 0) {
      $("#numUserReq").html("" + len);
      $("#numUserReq").show();
      for (var i = 0; i < len; i++) {
        var id = result[i].id;
        var challengestatus = result[i].status;
        var c_status = result[i].c_status;
        //challengerstarted means you
        if (challengestatus == 1 && c_status == 0) {
          //ask the user to start the challenge from where he/she left
          $time = gettimestamp(result[i].time);

          not +=
            `<div style="display: flex;justify-content: center;align-items: center; padding: 10px;">
            <div style="width: 70%; text-align: center;">
								<p>${result[i].accepter} Accepted Your Challenge <br><p1 style="font-weight: bold;">${result[i].challengeName}</p1></p>
							</div>
							<div style="text-align: center;width: 30%;">
								<button class='cgbbtn btn-accept user_req_id' data-value='${result[i].challengeName}' value='${id}'> Start </button>
							</div></div>`;

        } else if (challengestatus == 1 && c_status == 1) {

                    not +=
            `<div style="display: flex;justify-content: center;align-items: center; padding: 10px;">
            <div style="width: 70%; text-align: center;">
								<p>${result[i].accepter} Accepted Your Challenge <br><p1 style="font-weight: bold;">${result[i].challengeName}</p1></p>
							</div>
							<div style="text-align: center;width: 30%;">
								<button class='cgbbtn btn-accept user_req_id' data-value='${result[i].challengeName}' value='${id}'> Continue </button>
							</div></div>`;

        } else if (challengestatus == 1 && c_status == 2) {

           not += `<div style="display: flex;justify-content: center;align-items: center; padding: 10px;">
            <div style="width: 70%; text-align: center;">
								<p>${result[i].accepter} Accepted Your Challenge <br><p1 style="font-weight: bold;">${result[i].challengeName}</p1></p>
							</div>
							<div style="text-align: center;width: 30%;">
								<p>Completed, Check Challenge Status !!</p>
              </div></div>`;

        } else if (challengestatus == -1) {
          // tell the user that the opponent has not accepted you request
          not += `<div style="display: flex;justify-content: center;align-items: center; padding: 10px;"><div style="width: 70%; text-align: center;">
								<p>You Have Challenged ${result[i].accepter} In <br><p1 style="font-weight: bold;">${result[i].challengeName}</p1></p>
							</div>
							<div style="text-align: center;width: 30%;">
								<p>5 Aug 8:32</p>
							</div></div>`;

          
        }
      }
      
      document.getElementById("yourReq").innerHTML = not;

      var buttons = document.querySelectorAll(".user_req_id");

      for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function () {
         
          if (confirm("Start Challenge Now ? ")) {
            $.ajax({
              type: "get",
              data: {
                request: "start",
                id: this.value,
                user: "challenger",
                challenge: this.getAttribute("data-value"),
              },
              headers: {
                Authorization: "Bearer " + localStorage.getItem("sid"),
              },
              url: "https://www.codegreenback.com/mw/challenge_mw.php",
              dataType: "json",
              success: function (re) {
               
                if (re.status == "1") {
                  window.location.replace(re.url);
                }
              },
            });
          }
        });
      }
    } else {
      $("#numUserReq").hide();
    }
  }

  /*   ............................................user Friend Requests .............................................. */

  // $("#friendReq").mouseenter(getFriendReq);
  getFriendReq();

  function getFriendReq() {
    $.ajax({
      type: "get",
      url: "https://www.codegreenback.com/mw/userFriendReq_mw.php",
      data: {
        service: "getReq",
      },
      dataType: "json",
      success: function (json) {
        
        makeFriendReq(json);
      },
    });
  }

  function makeFriendReq(data) {

    data_.friendRequests = data.length;
    if (data.length == 0) {
      $("#numFriendReq").html("");
      $("#numFriendReq").hide();
      $("#friendRequests").html("<li><h3>No Friend Request !</h3></li>");
    } else {
      a = $("#numFriendReq").html();
      b = data.length;
      if (b != a) {
        txt = "";
        for (var i = 0; i < data.length; i++) {
          txt +=
            `<li style="display: flex; align-content: center; flex-wrap: wrap; padding:10px"><div  style=" width: 70%; display: flex; align-self: center;">` +
            data[i] +
            ` sent You A friend Request</div><div  style="display: flex; width: 30%;"> <button class='cgbbtn btn-accept acceptfrndReq' style='margin-right:5px' value='` +
            data[i] +
            `'> accept </button> <button class='cgbbtn btn-decline declinefrndReq' value='` +
            data[i] +
            `'>Decline</button></div></li>`;
        }

        $("#friendRequests").html(txt);
        $("#numFriendReq").html("" + b);
        $("#numFriendReq").show();
        makeFriendReqButtons();
      } else {
        $("#numFriendReq").show();
      }
    }
  }

  function makeFriendReqButtons() {
    var accept_fr_butt = document.querySelectorAll(".acceptfrndReq");
    var decline_fr_butt = document.querySelectorAll(".declinefrndReq");

    //add event listener for accept friend request buttons

    for (var i = 0; i < accept_fr_butt.length; i++) {
      accept_fr_butt[i].addEventListener("click", function () {
        $.ajax({
          url: "https://www.codegreenback.com/mw/userFriendReq_mw.php",
          type: "post",
          dataType: "json",
          data: {
            service: "accept",
            user: this.value,
          },
          success: function (result) {
            
            getFriendReq();
          },
        });
      });

      decline_fr_butt[i].addEventListener("click", function () {
        $.ajax({
          url: "https://www.codegreenback.com/mw/userFriendReq_mw.php",
          type: "post",
          dataType: "json",
          data: {
            service: "decline",
            user: this.value,
          },
          success: function (result) {
            
            getFriendReq();
          },
        });
      });
    }
  }

  /*   ....................... for general Notifications.....................................................         */

  function getGeneralNotification() {
    $.ajax({
      url: "https://www.codegreenback.com/mw/userFriendReq_mw.php",
      type: "get",
      dataType: "json",
      data: {
        service: "getGenNotification",
      },
      success: function (json) {
        
        makeGeneralNotification(json);
      },
    });
  }

  function makeGeneralNotification(data) {

    data_.generalNotification = data.length;
    if (data.length !== 0) {
      var a = 9; // $("#numGeneralReq").html();
      var b = data.length;
      if (b != a) {
        $("#numGeneralReq").show();
        $("#generalNotification-content").html("");
        $("#clearGeneralNotification").show();
        $("#numGeneralReq").html("" + data.length);
        for (var i = 0; i < b; i++) {
          $("#generalNotification-content").append(
            `<li style='border-bottom:0.5px solid grey;'>` + data[i].notification + `</li>`
          );
        }

        var clearGeneralNotification = document.getElementById(
          "clearGeneralNotification"
        );

        clearGeneralNotification.addEventListener(
          "click",
          deleteGeneralNotification
        );
      } else {
        $("#numGeneralReq").show();
        $("#clearGeneralNotification").show();
      }
    } else {
      $("#numGeneralReq").html("0");
      $("#numGeneralReq").hide();
      $("#clearGeneralNotification").hide();
      $("#generalNotification").html("<li style='padding:15px'><h3>No Notification</h3></li>");
    }
  }

  function deleteGeneralNotification() {
    $.ajax({
      url: "https://www.codegreenback.com/mw/userFriendReq_mw.php",
      type: "get",
      dataType: "json",
      data: {
        service: "deleteGenNotification",
      },
    });

    getGeneralNotification();
  }

  getGeneralNotification();
  
})();


