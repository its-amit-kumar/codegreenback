
//get the pastchallenge request data of this user and check wheather this user can challenge the searched user 

(function(){

  /* new user object */
  const new_user_object = {
    username          : search_username,
    name              : "",
    rank              : "",
    league            : "",
    points            : "",
    userType          : "",
    totalChallenges   : "",
    pastchallenge     : "",
    friendStatus      : "",
    img               : "",
  };

  const challenge_obj  = {
    challenge_name : "",
    cc : ""
  }

const challenge_3_obj = {
    status : 0,
    count_request : 0,
    rejected : 0,
    loader : 0,
    flag    : 0,
    request_loader : 0
  }


  /**
   *................................... SOCKET PROGRAMMING STARTS .....................................
   */
              // Create a new WebSocket.
	new_token = $("#sid").val();
            const socket  = new WebSocket(`wss://www.codegreenback.com:8090?${new_token}`);

            socket.onopen = function(e) {
            userrr = document.getElementById("username").value;
            userr = document.getElementById("profile_username").value;
            };

      

            socket.onmessage = function(e) {
                data = JSON.parse(e.data);
                //console.log(data['user'].indexOf(userr));
                if (data["cmd"]=='setUserOnline'){
                  if(data['user'].indexOf(userr)!=-1){
                        // $("#onlineStatus").empty();
                        // $("#onlineStatus").html("<h1>Online</h1>");
			 $(".online-status").removeClass("ofline-active");
                        $(".online-status").addClass("online-active");
                  }
                  else{
                        // $("#onlineStatus").empty();
                        // $("#onlineStatus").html("<h1>Offline</h1>");
                        $(".online-status").removeClass("online-active");
                        $(".online-status").addClass("ofline-active");

                  }
                }
                if (data["cmd"]=="setOfflineUser"){
                  if(data["user"]==userr){
                    // $("#onlineStatus").empty();
                    // $("#onlineStatus").html("<h1>Offline</h1>");
                    $(".online-status").removeClass("online-active");
                    $(".online-status").addClass("ofline-active");
                  }
                }
                if(data["cmd"]=="onlineUser"){
                if(data["user"]==userr){
                    // $("#onlineStatus").empty();
                    // $("#onlineStatus").html("<h1>Online</h1>");
                    $(".online-status").removeClass("ofline-active");
                    $(".online-status").addClass('online-active');
                  }
                }
                if (data['cmd']=="challenge3requesterror") {
                  customAlert("Seems Like The User Is Not Online");
		$("#timeout").text("");
                    $(".process-msg-c3").text("");
                    $(".overlay-loader-c3").hide();

                    clearInterval(challenge_3_obj.loader);
                    challenge_3_obj.count_request += 1;
                    challenge_3_obj.flag = 0;

                }
                if (data["cmd"]=="challenge3request"){
		  if(challenge_3_obj.flag == 0)
                  {
                    challenge_3_obj.flag = 1;

                    $("#c3-body-msg").text(
                    `${data["from"]} has Challenged you in Face/Off`
                    );
                    $("#c3-cc-bet").text(`${data["cc"]}`);
                    $("#c3-model").show();
                    let time1 = 15;
                    

                    $("#c3-accept-but").click(function () {
                      $("#c3-accept-but").off('click');
                      $("#c3-reject-but").off('click');
                      $("#c3-body-msg").text("");
                      $("#c3-cc-bet").text("");
                      $("#c3-model").hide();
                    
                      //display progress loader

                      $(".process-msg-c3-req").text(`Processing Your Request ! Please Wait `);
                      $(".overlay-loader-c3-req").show();

                      clearInterval(challenge_3_obj.request_loader);
                      socket.send(
                        JSON.stringify({
                          cmd: "challenge3response",
                          response: "accepted",
                          _vid: data["_vid"],
                          to: data["from"],
                          cc: data["cc"],
                        })
                      );
                    });

                    // window.onbeforeunload = function (event) {
                    //   if (challenge_3_req_obj.queue == 1) {
                    //     socket.send(
                    //       JSON.stringify({
                    //         cmd: "challenge3response",
                    //         response: "rejected",
                    //         _vid: data["_vid"],
                    //         to: data["from"],
                    //       })
                    //     );
                    //   }
                    // };

                    $("#c3-reject-but").click(function () {
                      $("#c3-accept-but").off('click');
                      $("#c3-reject-but").off('click');

                      $("#c3-timer").text("");
                      $("#c3-body-msg").text("");
                      $("#c3-cc-bet").text("");
                      $("#c3-model").hide();
                      clearInterval(challenge_3_obj.request_loader);
                      challenge_3_obj.flag = 0;
                      socket.send(
                        JSON.stringify({
                          cmd: "challenge3response",
                          response: "rejected",
                          _vid: data["_vid"],
                          to: data["from"],
                        })
                      );
                    });

                    challenge_3_obj.request_loader = setInterval(function () {
                      if (time1 >= 0) {
                        $("#c3-timer").text(time1);
                        time1 = time1 - 1;
                      } else {
                        $("#c3-body-msg").text("");
                        $("#c3-cc-bet").text("");
                        $("#c3-model").hide();

                        clearInterval(challenge_3_obj.request_loader);
                        // send no response
                        $("#c3-accept-but").off('click');
                        $("#c3-reject-but").off('click');
                        challenge_3_obj.flag = 0;
                      }
                    }, 1000);
                  }
                }
		
                if(data['cmd'] == "_r_a")
                {
                  // request accepted
                  $("#timeout").text("");
                  $(".process-msg-c3").text("Challenge Request Accepted ! Processing New Challenge");
                  clearInterval(challenge_3_obj.loader);
                }

                if(data["cmd"]=="challenge3RedirectUrl"){
                  window.location.replace(data['url']);
                }

                if(data["cmd"]=="challenge3response"){
                  if(data["response"]=="rejected")
                  {
                    $("#timeout").text("");
                    $(".process-msg-c3").text("");
                    $(".overlay-loader-c3").hide();

                    clearInterval(challenge_3_obj.loader);
                    challenge_3_obj.rejected += 1;
                    challenge_3_obj.count_request += 1;
                    challenge_3_obj.flag = 0;

                    customAlert("The challenge request for Face/Off has been rejected by "+data["from"]);
                  }
                }


                if(data["cmd"] == "v_error")
                {
                  if(data['error_code'] == 801)
                  {
                    /**
                     * 
                     */
                    alert("Challenge Limit Exceeded ! Become An Elite Member Now");
                  }
                  else if(data['error_code'] == 802)
                  {
                    alert("You Don't Have Enough Code Coins ! Buy Code Coins Now");
                  }
                }

          }


  /**
   * Socket prograamming ends
   */
            function checkpastChallenge() {
              $.ajax({
                url: "https://www.codegreenback.com/mw/userRequest_mw.php",
                dataType: "json",
                headers: {
                  Authorization: "Bearer " + localStorage.getItem("sid"),
                },
                success: function (result) {
                   // result = json_decode(result);
                  console.log(result);
                  var i;
                  for (i = 0; i < result.length; i++)
                    if (result[i].accepter == search_username) {
                      $("#challengeButton").hide();
                      $("#myModal").hide();
                      $("searchedUser-msg").show();
                      $("#searchedUser-msg").html(
                        "<h3>You Have Already Challenged This User</h3>"
                      );
                      $("#searchedUser-msg").css(
                        "background-color",
                        "rgb(141, 228, 240)"
                      );
                      break;
                    }
                },
              });
            }


            getSearchedUserDetails(search_username);

            $(document).ready(function () 
            {
              $(".searchedUser-dropbtn").click(myFunctions);
              getRecommendedUser();
            });



 

 

             /* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
             function myFunctions() {
               document.getElementById("myDropdown").classList.toggle("shows");
             }

             // Close the dropdown menu if the user clicks outside of it
             window.onclick = function (event) {
               if (!event.target.matches(".searchedUser-dropbtn")) {
                 var dropdowns = document.getElementsByClassName(
                   "searchedUserdropdown-content"
                 );
                 var i;
                 for (i = 0; i < dropdowns.length; i++) {
                   var openDropdown = dropdowns[i];
                   if (openDropdown.classList.contains("shows")) {
                     openDropdown.classList.remove("shows");
                   }
                 }
               }
             };

             //for recommended user div

             $("#right-button").click(function () {
               event.preventDefault();
               $("#recommended-Users-div").animate(
                 {
                   scrollLeft: "+=500px",
                 },
                 "slow"
               );
             });

             $("#left-button").click(function () {
               event.preventDefault();
               $("#recommended-Users-div").animate(
                 {
                   scrollLeft: "-=500px",
                 },
                 "slow"
               );
             });

              function getSearchedUserDetails(username) {
                $.ajax({
                  type: "get",
                  url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
                  dataType: "json",
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("sid"),
                  },
                  data: {
                    user: username,
                    service: "getData",
                  },
                  success: function (data) {
              
                    makePage(data);
                  },
                });
              }


            class Processes {
              // all the funtions for processing will come here
              constructor() {
                this.challenge_obj_created = false;
              }

              check() {
                // this function is to verify that a challenge can happen or not

                let flag = true;
                var challengeType = "n2";
                // console.log(`the searched user type is ${searched_user_type}`);

                if (userType === "non-elite" && new_user_object.userType == 1) {
                  customAlert(
                    "You cannot challenge a Elite Member !! Become an Elite member now to challenge this player"
                  );
                  flag = false;
                } else if (userType === "elite" && new_user_object.userType == 0) {
                  customAlert("You Cannot Challenge a Non-Elite Member..");
                  flag = false;
                } else if (
                  league !== new_user_object.league &&
                  new_user_object.friendStatus.status !== "friend"
                ) {
                  customAlert(
                    "Same League Players Can Challenge Each Other !!! Or Becomes Friends To Challenge ."
                  );
                  flag = false;
                } else if (new_user_object.pastchallenge.status == "true") {
                  customAlert(
                    `Already Challenged In ${new_user_object.pastchallenge.challengeName}`
                  );
                  flag = false;
                }

                if (userType === "non-elite" && new_user_object.userType == 0) {
                  challengeType = "n2";
                } else if (userType == "elite" && new_user_object.userType == 1) {
                  challengeType = "n0";
                } else {
                  challengeType = "0"; //error in challenge type;
                }

                if (flag) {
                  if (!this.challenge_obj_created) {
                    var challengeObj = new challengeUser(challengeType);
                    this.challenge_obj_created = true;
                  }

                  $("#myModal").modal("show");
                }
              }

              unblockUser() {
                $.ajax({
                  type: "get",
                  url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
                  data: {
                    searched: search_username,
                    service: "unblockUser",
                  },
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("sid"),
                  },
                  dataType: "json",
                  success: function (result) {
                    // console.log(result);
                    if (result.status == 1) {
                      new_user_object.friendStatus = result.data;
                      console.log(new_user_object);
                    }
                    let obj = new Processes();
                    obj.updateFriendStatus();
                  },
                });
                
              }

              blockUser() {
                $.ajax({
                  type: "get",
                  url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
                  data: {
                    searched: search_username,
                    service: "blockUser",
                  },
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("sid"),
                  },
                  dataType: "json",
                  success: function (result) {
                    // console.log(result);
                    if (result.status == 1) {
                      new_user_object.friendStatus = result.data;
                    }
                    let obj = new Processes();
                    obj.updateFriendStatus();
                  },
                });
              
              }

              sendRequest() {
                //disable the button first
                $.ajax({
                  type: "post",
                  url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
                  data: {
                    searched: search_username,
                    service: "sendRequest",
                  },
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("sid"),
                  },
                  dataType: "json",
                  success: function (result) {
                 
                    if (result.status == 1) {
                      new_user_object.friendStatus = result.data;
                     
                    }
                    let obj = new Processes();
                    obj.updateFriendStatus();
                  },
                });
              }

              deleteRequest() {
                $.ajax({
                  type: "get",
                  url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
                  data: {
                    searched: search_username,
                    service: "deleteRequest",
                  },
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("sid"),
                  },
                  dataType: "json",
                  success: function (result) {
                      if (result.status == 1) 
                      {
                        new_user_object.friendStatus = result.data;
                        
                      }
                      let obj = new Processes();
                      obj.updateFriendStatus();
                  },
                });
              }

              updateFriendStatus() {
                if (new_user_object.friendStatus.status == "Not Friends") {
                  $("#friendStatus").html(
                    `<button type="button" class="btn btn-info btn-lg" id="sendfriendReq">Add Friend</button>`
                  );
                  $("#sendfriendReq").click(this.sendRequest);
                } else if (new_user_object.friendStatus.status == "requested") {
                  $("#friendStatus").html(
                    `<button type="button" class="btn btn-info btn-lg" id="undofriendReq">Unsend FriendRequest</button>`
                  );
                  $("#undofriendReq").click(this.deleteRequest);
                } else if (new_user_object.friendStatus.status == "friend") {
                  $("#friendStatus").html(
                    `<h3>Friends<span style="margin-left:10px"><i class="fa fa-check"></i></span></h3>`
                  );
                }

                if (
                  new_user_object.friendStatus.this_user_has_blocked == "true"
                ) {
                  $("#myDropdown").html(
                    `<button type="button" class="btn btn-info btn-lg" id="unblock">Unblock</button>`
                  );

                  $("#unblock").off("click");
                  $("#unblock").click(this.unblockUser);
                } else if (
                  new_user_object.friendStatus.this_user_has_blocked == "false"
                ) {
                  $("#myDropdown").html(
                    `<button type="button" class="btn btn-info btn-lg" id="block">Block User</button>`
                  );

                  $("#block").off("click");
                  $("#block").click(this.blockUser);
                }
              }


              start_timeout_c3()
              {
                $(".overlay-loader-c3").show();
                  $(".process-msg-c3").text("Challenge Request Sent. Waiting For Response");


                let time1 = 30;
                challenge_3_obj.loader = setInterval(function () {
                  if (time1 >= 0) {
                    // document.getElementById("c").innerHTML = time;
                    $("#timeout").text(time1);
                    time1 = time1 - 1;
                  } else {

                     $("#timeout").text("");
                    $(".process-msg-c3").text("");
                    $(".overlay-loader-c3").hide();
                    clearInterval(challenge_3_obj.loader);
                    customAlert("Request Timed Out ! No Response From The Opponent ");
                    challenge_3_obj.flag = 0;
                  
                  }
                }, 1000);
              }

            }

      
            class newUser extends Processes {
              constructor() {
                super();
                this.processButtons();
                this.makeSearchedUserProfile();
              }

              getDetails() {
                return `the name of the searched user is ${new_user_object.name}`;
              }

              processButtons() 
              {
                //  alert("hello");
                $("#challengeButton").off("click");
                $("#challengeButton").click(this.check);
              }

              makeSearchedUserProfile()
              {
                  $("#searchedUser-username").text(
                    `${new_user_object.username}`
                  );

                    $("#searchedUser-name").html(
                      `<h4 style='font-size:1vw'>${new_user_object.name}</h4>`
                    );
                    
                  $("#searchedUser-Challenges").text("" + new_user_object.totalChallenges);
                  $("#searchedUser-League").text("" + new_user_object.league);
                  $("#searchedUser-Rank").text("" + new_user_object.rank);
                  $("#searchedUser-Points").text("" + new_user_object.points);
                  $("#searched_user_image").attr({
                    src: `https://www.codegreenback.com/${new_user_object.img}`,
                 });


                  
                if (new_user_object.friendStatus.status == "Not Friends") {
                  $("#friendStatus").html(
                    `<button type="button" class="btn btn-info btn-lg" id="sendfriendReq">Add Friend</button>`
                  );

                  $("#sendfriendReq").off("click");
                  $("#sendfriendReq").click(this.sendRequest);

                } else if (new_user_object.friendStatus.status == "requested") {
                  $("#friendStatus").html(
                    `<button type="button" class="btn btn-info btn-lg" id="undofriendReq">Unsend FriendRequest</button>`
                  );

                  $("#undofriendReq").off("click");
                  $("#undofriendReq").click(this.deleteRequest);
                } else if (new_user_object.friendStatus.status == "friend") {
                  $("#friendStatus").html(
                    `<h3>Friends<span style="margin-left:10px"><i class="fa fa-check"></i></span></h3>`
                  );
                }


                if (new_user_object.friendStatus.this_user_has_blocked == "true") {
                  $("#myDropdown").html(
                    `<button type="button" class="btn btn-info btn-lg" id="unblock">Unblock</button>`
                  );

                  $("#unback").off("click");
                  $("#unblock").click(this.unblockUser);
                } else if (
                  new_user_object.friendStatus.this_user_has_blocked == "false"
                ) {
                  $("#myDropdown").html(
                    `<button type="button" class="btn btn-info btn-lg" id="block">Block User</button>`
                  );

                  $("#block").off("click");
                  $("#block").click(this.blockUser);
                }




            }



          }


          class challengeUser {
            // class for sending a challenge request etc;
            constructor(type) {
              this.type = type;
              this.cc = 0;

              if (type != "0") {
                if (type == "n0") {
                  //i.e. both elite member
                  this.lowerLimit = 15;
                  this.upperLimit = 200;
                  this.initial = 25;
                  $(".msg_001").text(``);
                } else if (type == "n2") {
                  //i.e. both non-elite member
                  this.lowerLimit = 10;
                  this.upperLimit = 10;
                  this.initial = 10;
                  $(".msg_001").text(
                    `Non-Elite Cannot Challenge With More Than 10 CC`
                  );
                }
                this.setCodeCoinLimit();
                this.makebuttons();
              }
            }

            setCodeCoinLimit() {
              $("#ccrange").attr({
                min: this.lowerLimit,
                max: this.upperLimit,
                value: this.initial,
              });
            }

            makebuttons()
            {
              // $("#back").click({obj: this},function(e){
              //   e.data.obj.displaymsg();
              // });
              
              $("#challenge1").off("click");
              $("#challenge1").click({obj: this}, function(e){
                e.data.obj.setChallengeName("TurnYourTurn");
              });

                $("#challenge2").off("click");
                $("#challenge2").click({obj: this}, function(e){
                e.data.obj.setChallengeName("Rushour");
              });

                $("#challenge3").off("click");
                $("#challenge3").click({obj: this}, function(e){
                e.data.obj.setChallengeName("Face/Off");
              });

              
                    $("#back").off("click");
                    $("#back").click({obj: this},function(e){
                      e.data.obj.unsetChallengeName();
                    });

              $("#sendChallengeRequest").off("click");
              $("#sendChallengeRequest").click({obj: this}, function(e){
                        e.data.obj.validateChallengeParams();
              });
            }
          

            setChallengeName(name) 
            {
              // raange slider javascript

              var slider = document.getElementById("ccrange");
              var output = document.getElementById("ccValue");
              output.innerHTML = slider.value; // Display the default slider value

              // Update the current slider value (each time you drag the slider handle)
              slider.oninput = function () {
                output.innerHTML = this.value;
                output.style.backgroundColor = `rgba(235, 52, 52 ,${
                  this.value / 100
                })`;
              };

              if (name != "") {
                $("#selectedChallenge").html(`<h3>${name}</h3>`);
                $("#selectChallenge").hide();
                $("#selectCC").fadeIn();
                this.challengeName = name;

                challenge_obj.challenge_name = name;                              //update challenge_obj (global)
              }
              

            }

            displaymsg()
            {
              alert("hello");
            }

            unsetChallengeName()
            {
              $("#selectCC").hide();
              $("#selectChallenge").fadeIn();
              this.challengeName = "";
              this.cc = 0;

              challenge_obj.challenge_name = '';                                    //update challenge_obj (global)
              challenge_obj.cc = 0;

            }

            validateChallengeParams() {
              $(".modal-close-button").click();
              this.cc = $("#ccrange").val();

              // console.log(`the selected challenge is ${this.challengeName}`);
              // console.log(`the selected cc is ${this.cc}`);
              let flag = true;

              if (this.cc < this.lowerLimit || this.cc > this.upperLimit) {
                flag = false;
                customAlert(
                  "Code Coins Set Is InValid !!! Warning: Don't Play With The Code You May Lose Data"
                );
              }              
              else if(this.challengeName != "TurnYourTurn" && this.challengeName != "Rushour" && this.challengeName != "Face/Off"){
                flag = false;
                customAlert(
                  "Invalid Challenge Selected !!! Warning: Don't Play With The Code You May Lose Data"
                );
              }
              else if (parseInt(user_cc) < parseInt(this.cc)) {
                  flag = false;
                  customAlert("You Don't Have Enough Code Coins !!!");
              }
              if(flag)
              {
                challenge_obj.cc = this.cc;                                          //updating global challenge object

                if(this.challengeName == "Face/Off")
                {
                    Confirm(
                      `Challenge ${search_username}`,
                      `Challenge ${search_username} in ${this.challengeName} with CodeCoins :  ${this.cc}`,
                      "Challenge",
                      "Discard",
                      this.challenge_3
                    );
                }
                else{
                    Confirm(
                      `Challenge ${search_username}`,
                      `Challenge ${search_username} in ${this.challengeName} with CodeCoins :  ${this.cc}`,
                      "Challenge",
                      "Discard",
                      this.challenge_1_2
                    );
                }

              
    
              }


              
              
            }

            challenge_1_2()
            {
              // alert("callback worked");

    

              $.ajax({
                type: "post",
                url: "https://www.codegreenback.com/mw/sendNotification_mw.php",
                beforeSend: function(){
                  $(".overlay-loader").show();
                },
                complete: function(){
                  $(".overlay-loader").hide();
                },
                data: {
                  user: user,
                  challengedUser: search_username,
                  challenge: challenge_obj.challenge_name,
                  cc: challenge_obj.cc,
                  _cs_to : $("#_cs_to").val()
                },
                dataType: "json",
                success: function (result) {
                 
                  if(result.status == 1)
                  {
		     /**
                     * set challenge flag  to 1
                     * so that c3 request are not recieved by the client
                     */
                    challenge_3_obj.flag = 1;
                    new_user_object.pastchallenge = result.challengeStatus;
                    customAlert(result.msg);
                   
                    
                  }
                  else if(result.status == 0)
                  {
			/**
                     * set challenge flag to 0
                     * so the request for c3 can be accepted now
                     * 
                     */
                    challenge_3_obj.flag = 0;
                    customAlert(result.msg);
                  }

                },
              });
            }

            challenge_3()
            {
              // alert("challenge3 callback worked");

              $.ajax({
                type: "post",
                url: "https://www.codegreenback.com/mw/challenge3mw.php",
                dataType: "json",
                beforeSend: function () {
                  $(".overlay-loader").show();
                },
                data: {
                  user: user,
                  challengedUser: search_username,
                  challenge: challenge_obj.challenge_name,
                  cc: challenge_obj.cc,
                  _cs_to: $("#_cs_to").val(),
                },
                success: function (result) {

                  if (result.status == 1) 
                  {
		     /**
                     * set challenge flag = 1
                     * so that challenge3 request cannot be recievd to the client
                     */

                    challenge_3_obj.flag = 1;
                    $(".overlay-loader").hide();
                    socket.send(
                      JSON.stringify({
                        cmd: "challenge3request",
                        opponent: search_username,
                        _vid: result._vid,
                        cc: challenge_obj.cc,
                      })
                    );
                    let obj = new Processes();
                    obj.start_timeout_c3();
                  } 
                  else if (result.status == 0) 
                  {

			/**
                     * set challenge flag = 0
                     * so that challenge 3 request can be recieved
                     */
                    challenge_3_obj.flag = 0;
                    $(".overlay-loader").hide();
                    customAlert(result.msg);
                  }
                },
              });
            }
          }

            function makePage(data)
            {
              let searched_user_league = "";
              let friendStatus = false;
              if (data.status == 1) {
                console.log(data);
                update_new_user_object(data);
                let searchedUser = new newUser();
              } else {
                customAlert(`user not found`);
		$("#challengeButton").click(function(){customAlert(`Invalid user`); window.location.replace('https://www.codegreenback.com/Home');})
              }
            }


            /* function to update new user data */
            function update_new_user_object(data)
            {
              new_user_object.username         =   data.username;
              new_user_object.name             =   data.name;
              new_user_object.rank             =   data.rank;
              new_user_object.league           =   data.league;
              new_user_object.points           =   data.points;
              new_user_object.userType         =   data.user_type;
              new_user_object.totalChallenges  =   data.totalChallenges;
              new_user_object.pastchallenge    =   data.challenge;
              new_user_object.friendStatus     =   data.friendStatus;
              new_user_object.rank             =   data.rank;
              new_user_object.img              =   data.img;
              console.log(new_user_object);
            }


  

            //  to display custom msg

            function customAlert(msg) {
                // Get the modal
                let modal = document.getElementById(
                  "customAlert"
                );

                $("#customAlertMsg").text(msg);
                // modal.style.display = "block";

                $("#customAlert").fadeIn();

                $(".customClose").click(function()
                                            {
                  modal.style.display = "none";
                });
                                          

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function (event) {
                  if (event.target == modal) {
                    modal.style.display = "none";
                  }
                };
              };



    //............................ custom confirm box ..................................
    function Confirm(title, msg, $true, $false, $callback) {
      /*change*/
    
      var $content =
        "<div class='dialog-ovelay'>" +
        "<div class='dialog'><header>" +
        " <h3> " +
        title +
        " </h3> " +
        "<i class='fa fa-close'></i>" +
        "</header>" +
        "<div class='dialog-msg'>" +
        " <p> " +
        msg +
        " </p> " +
        "</div>" +
        "<footer>" +
        "<div class='controls'>" +
        " <button class='button button-danger doAction'>" +
        $true +
        "</button> " +
        " <button class='button button-default cancelAction'>" +
        $false +
        "</button> " +
        "</div>" +
        "</footer>" +
        "</div>" +
        "</div>";

      $("body").prepend($content);

      $(".doAction").click(function () {
        
        /**
         * set challenge_3_obj to 1
         * so that no other challenge request can come or go
         */

        challenge_3_obj.flag = 1;

        $(this)
          .parents(".dialog-ovelay")
          .fadeOut(500, function () {
            $(this).remove();
            $callback();
          });
          

        
      });
      $(".cancelAction, .fa-close").click(function () {
	
	/**
         * set challenge_3_obj to 0
         * so that request can come now
         */
        challenge_3_obj.flag = 0;
        $(this)
          .parents(".dialog-ovelay")
          .fadeOut(500, function () {
            $(this).remove();
          });
      });
      
    }

    function getRecommendedUser()
    {
      $.ajax({
        url: "https://www.codegreenback.com/mw/searchedUser_mw.php",
        type: "get",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("sid"),
        },
        data:{
          'service' : "_rec"
        },
        dataType:"json",
        success:function(data){
          // console.log(data);
          makeRecommendedUserDiv(data);
        }
      });
    }

    function makeRecommendedUserDiv(data)
    {
      let len = data.length;
      let txt = ``;
      for(let i = 0 ; i < len; i++)
      {
        if(data[i].username != new_user_object.username)
        {
                  txt += `
        <div class="recommended-user-card">
          <a href='https://www.codegreenback.com/user/${data[i].username}/'>
              <div class="recommended-user-img">
                <div>
                  <img src="https://www.codegreenback.com/${data[i].img}" style="height: 80px; width:80px; border-radius:50%; margin:0 auto;" alt="">
                </div>
                
              </div>

              <div class="recommended-user-info">
                <div  class="recommended-user-username">
                  <p style="font-size: 1.2vw;">${data[i].username}</p>
                </div>
                <div  class="recommended-user-name">
                  <p style="font-size: 1vw;">${data[i].name}</p>
                </div>

              </div>
          </a>
          </div>`;
        }

      }
      $("#recommended-Users-div").html(txt);
    }



})();

