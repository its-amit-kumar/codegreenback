(function(){

    
            try {
	      token = document.getElementById("sid").value;
              var socket = new WebSocket("wss://www.codegreenback.com:8090?"+token);
            } catch (err) {
              alert(err);
            }
            onlineUser = [];

            const challenge_3_req_obj = {
              queue: 0,
              accepted : 0,
              request_loader : 0
            }

   
            socket.onopen = function (e) {
              userr = document.getElementById("username").value;
              //console.log("Connection established!");
              //socket.send(JSON.stringify({"user":userr,"cmd":"getOnlineUsers"}));
              //socket.send(JSON.stringify({"user":userr,"cmd":"userOnline"}));
            };

            socket.onmessage = function (e) {
              //console.log( JSON.parse(e.data) );
              data = JSON.parse(e.data);
              //console.log(data);
              if (data["cmd"] == "setUserOnline") {
                data["user"].forEach(function (item, index) {
                  if (item != userr) {
                    $(".onlineUsers").append(
			 `<div name=${item} id=${item} class="col-lg-6 col-md-12 onlineUser"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044"></i>&nbsp; ${item}</div>`
                    );
                    onlineUser.push(item);
                  }
                });
                $(".onlineUser").click(function (e) {
                  user = $(this).attr("name");
                  window.location = `/user/${user}`;
                });
              }
              if (data["cmd"] == "setOfflineUser") {
                if (onlineUser.includes(data["user"])) {
                  $("#" + data["user"]).remove();
                  index = onlineUser.indexOf(data["user"]);
                  onlineUser.splice(index);
                }
              }
              if (data["cmd"] == "onlineUser") {
                if (document.getElementsByName(data['user']).length == 0) {
                  if (userr != data["user"]) {
                    $(".onlineUsers").append(
                      `<div name=${data['user']} id=${data['user']} class="col-lg-6 col-md-12 onlineUser"><i class="fa fa-wifi" aria-hidden="true" style="color: #002044"></i>&nbsp; ${data['user']}</div>`
                    );

                    onlineUser.push(data["user"]);
                  }
                }
                $(".onlineUser").click(function (e) {
                  user = $(this).attr("name");
                  window.location = `/user/${user}`;
                });
              }
              if (data["cmd"] == "challenge3requesterror") {
                alert("soory, could not challenge");
              }



              if (data["cmd"] == "challenge3request") {

                if(challenge_3_req_obj.queue == 0)                                   //only one request at a time
                {

                  challenge_3_req_obj.queue = 1;

                  $("#c3-body-msg").text(
                    `${data["from"]} has Challenged you in Face/Off`
                  );
                  $("#c3-cc-bet").text(`${data["cc"]}`);
                  $("#c3-model").show();
                  let time1 = 10;
                  

                  $("#c3-accept-but").click(function () {
                    $("#c3-accept-but").off();
                    $("#c3-reject-but").off();
                    $("#c3-body-msg").text("");
                    $("#c3-cc-bet").text("");
                    $("#c3-model").hide();
                    
                    //display progress loader

                    $(".process-msg-c3").text(`Processing Your Request ! Please Wait `);
                    $(".overlay-loader-c3").show();

                    clearInterval(challenge_3_req_obj.request_loader);
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
                    clearInterval(challenge_3_req_obj.request_loader);
                    challenge_3_req_obj.queue = 0;
                    socket.send(
                      JSON.stringify({
                        cmd: "challenge3response",
                        response: "rejected",
                        _vid: data["_vid"],
                        to: data["from"],
                      })
                    );
                  });

                  challenge_3_req_obj.request_loader = setInterval(function () {
                    if (time1 >= 0) {
                      $("#c3-timer").text(time1);
                      time1 = time1 - 1;
                    } else {
                      $("#c3-body-msg").text("");
                      $("#c3-cc-bet").text("");
                      $("#c3-model").hide();

                      clearInterval(challenge_3_req_obj.request_loader);
                      // send no response
                      $("#c3-accept-but").off('click');
                      $("#c3-reject-but").off('click');
                      challenge_3_req_obj.queue = 0;
                    }
                  }, 1000);
                }


              }




              if (data["cmd"] == "challenge3RedirectUrl") {

                $(".process-msg-c3").text("");
                $(".overlay-loader-c3").hide();
                window.location.replace(data["url"]);
                
              }
              if (data["cmd"] == "challenge3response") {
                if (data["response"] == "rejected") {
                  alert(
                    "The challenge request for challenge 3 has been rejected by " +
                      data["from"]
                  );
                }
              }

              if (data["cmd"] == "v_error") {
                $(".process-msg-c3").text("");
                $(".overlay-loader-c3").hide();
                if (data["error_code"] == 801) {
                  /**
                   *
                   */
                  alert(
                    "Challenge Limit Exceeded ! Become An Elite Member Now"
                  );
                } else if (data["error_code"] == 802) {
                  alert(
                    "You Don't Have Enough Code Coins ! Buy Code Coins Now"
                  );
                }
              }
            };

            socket.onclose = function () {
              alert(
                "You have been disconnected from the websocket on this particular webpage.\n \n The possible reason for your disconnection might be:\n\n1. You might have exceeded the limit of 100 WS requests/min. This also inlcudes malicious activities.\n 2. Your tried to connect to the websockets more than one time in a second. \n 3. You can only open one instance of the websocket on your system. If this is the reason, then you must have another instance of this website running. \n 4. Unauthorized connection. \n 5. Any malicious activity \n 6. The browser might be old and does not support WS technology.\n\n The possible solutions might be:\n\n 1. Refresh the page. \n 2. Close any other instance of websocket running and refresh the page. \n\n You can continue using the website but you'll be deprived of some functionality. If the above solution do not work then this means that we've detected some malicious activity on your connection. Try contacting the admins."
              );
            };
    


})();
          

