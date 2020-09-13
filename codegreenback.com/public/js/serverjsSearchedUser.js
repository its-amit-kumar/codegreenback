 // Create a new WebSocket.
	    token = document.getElementById("sid").value;
            var socket  = new WebSocket('ws://www.codegreenback.ml:8090'+token);

            socket.onopen = function(e) {
            userrr = document.getElementById("username").value;
            userr = document.getElementById("profile_username").value;
            console.log("Connection established!");
            //socket.send(JSON.stringify({"user":userrr,"cmd":"getOnlineUsers"}));
            //socket.send(JSON.stringify({"user":userrr,"cmd":"userOnline"}));
            //console.log(userr);
            };

            // Define the 
            //var message = document.getElementById('message');

            //function transmitMessage() {
            //socket.send("abc");
            //}

            socket.onmessage = function(e) {
                

                data = JSON.parse(e.data);
                //console.log(data['user'].indexOf(userr));
                if (data["cmd"]=='setUserOnline'){
                  if(data['user'].indexOf(userr)!=-1){
                        $("#onlineStatus").empty();
                        $("#onlineStatus").html("<h1>Online</h1>");
                  }
                  else{
                        $("#onlineStatus").empty();
                        $("#onlineStatus").html("<h1>Offline</h1>");

                  }
                }
                if (data["cmd"]=="setOfflineUser"){
                  if(data["user"]==userr){
                    $("#onlineStatus").empty();
                    $("#onlineStatus").html("<h1>Offline</h1>");
                  }
                }
                if(data["cmd"]=="onlineUser"){
                if(data["user"]==userr){
                    $("#onlineStatus").empty();
                    $("#onlineStatus").html("<h1>Online</h1>");
                  }
                }
                if (data['cmd']=="challenge3requesterror") {
                  alert("soory, could not challenge");
                }
                if (data["cmd"]=="challenge3request"){
                  if(confirm("You have a request for Challenge 3 from "+data['from']+" with a cc bet of "+data["cc"]+"cc. Do you want to accept it?")){
                        socket.send(JSON.stringify({"cmd":"challenge3response","response":"accepted","challengeid":data["challengeid"],"to":data["from"],"cc":data["cc"]}));
                  }
                  else{
                        socket.send(JSON>stringify({"cmd":"challenge3response","response":"rejected","challengeid":data["challengeid"],"to":data["from"]}));
                  }
                }
                if(data["cmd"]=="challenge3RedirectUrl"){
                  window.location.replace(data["url"]);
                }
                if(data["cmd"]=="challenge3response"){
                  if(data["response"]=="rejected"){
                        alert("The challenge request for challenge 3 has been rejected by "+data["from"]);
                  }
                }

          }


       //     window.onbeforeunload = function(){
       //           socket.send(JSON.stringify({"user":userrr,"cmd":"userOffline"}));
//}

   
   

    /**
     * THIS CODE HAS BEEN TRANSFERRED TO challenge.js
     */
            
//             // Create a new WebSocket.
//             var socket  = new WebSocket('ws://localhost:2260');

//             socket.onopen = function(e) {
//             userrr = document.getElementById("username").value;
//             userr = document.getElementById("profile_username").value;
//             console.log("Connection established!");
//             socket.send(JSON.stringify({"user":userrr,"cmd":"getOnlineUsers"}));
//             socket.send(JSON.stringify({"user":userrr,"cmd":"userOnline"}));
//             console.log(userr);
//             };

//             // Define the 
//             //var message = document.getElementById('message');

//             //function transmitMessage() {
//             //socket.send("abc");
//             //}

//             socket.onmessage = function(e) {
//                 console.log( e );

//                 data = JSON.parse(e.data);
//                 //console.log(data['user'].indexOf(userr));
//                 if (data["cmd"]=='setUserOnline'){
//                   if(data['user'].indexOf(userr)!=-1){
//                         $("#onlineStatus").empty();
//                         $("#onlineStatus").html("<h1>Online</h1>");
//                   }
//                   else{
//                         $("#onlineStatus").empty();
//                         $("#onlineStatus").html("<h1>Offline</h1>");

//                   }
//                 }
//                 if (data["cmd"]=="setOfflineUser"){
//                   if(data["user"]==userr){
//                     $("#onlineStatus").empty();
//                     $("#onlineStatus").html("<h1>Offline</h1>");
//                   }
//                 }
//                 if(data["cmd"]=="onlineUser"){
//                 if(data["user"]==userr){
//                     $("#onlineStatus").empty();
//                     $("#onlineStatus").html("<h1>Online</h1>");
//                   }
//                 }
//                 if (data['cmd']=="challenge3requesterror") {
//                   alert("sorry, could not challenge");
//                 }
//                 if (data["cmd"]=="challenge3request"){
//                   if(confirm("You have a request for Face/Off from "+data['from']+" with a cc bet of "+data["cc"]+"cc. Do you want to accept it?")){
//                         socket.send(JSON.stringify({"cmd":"challenge3response","response":"accepted","challengeid":data["challengeid"],"to":data["from"],"cc":data["cc"],"from":userr}));
//                   }
//                   else{
//                         socket.send(JSON.stringify({"cmd":"challenge3response","response":"rejected","challengeid":data["challengeid"],"to":data["from"],"from":userr}));
//                   }
//                 }
//                 if(data["cmd"]=="challenge3RedirectUrl"){
//                   window.location.replace(data["url"]);
//                 }
//                 if(data["cmd"]=="challenge3response"){
//                   if(data["response"]=="rejected"){
//                         alert("The challenge request for challenge 3 has been rejected by "+data["from"]);
//                   }
//                 }

//           }


//        //     window.onbeforeunload = function(){
//        //           socket.send(JSON.stringify({"user":userrr,"cmd":"userOffline"}));
// //}


