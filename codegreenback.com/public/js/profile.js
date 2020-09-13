$(document).ready(function () {
  profileMainContent();
});


const master_con = {
  get_friend : 0
}
function disable_all_side_but() {

  $("#profileChallenge1But").attr("disabled", true);
  $("#profileChallenge2But").attr("disabled", true);
  $("#profileChallenge3But").attr("disabled", true);
  $("#profileGeneralBut").attr("disabled", true);
  $("#profileFriendBut").attr("disabled", true);

  $("#profileChallenge1But").addClass('addwait');
  $("#profileChallenge2But").addClass('addwait');
  $("#profileChallenge3But").addClass('addwait');
  $("#profileGeneralBut").addClass('addwait');
  $("#profileFriendBut").addClass('addwait');

  
}

function enable_all_side_but() {

  $("#profileChallenge1But").attr("disabled", false);
  $("#profileChallenge2But").attr("disabled", false);
  $("#profileChallenge3But").attr("disabled", false);
  $("#profileGeneralBut").attr("disabled", false);
  $("#profileFriendBut").attr("disabled", false);

    $("#profileChallenge1But").removeClass("addwait");
    $("#profileChallenge2But").removeClass("addwait");
    $("#profileChallenge3But").removeClass("addwait");
    $("#profileGeneralBut").removeClass("addwait");
    $("#profileFriendBut").removeClass("addwait");
  
}


function profileMainContent() {
    $("#profileGeneralBut").click(function(){
        // alert("hello world");
        $(".p-side-nav-but").removeClass("active-but");
        $("#profileGeneralBut").addClass("active-but");
        $(".profileMainContent").hide();
        $("#profileGeneral").show();

    });

      $("#profileChallenge1But").click(function (event) {
        disable_all_side_but();

        $(".p-side-nav-but").removeClass('active-but');
        $("#profileChallenge1But").addClass('active-but');
        if(!event.detail || event.detail == 1)
        {
                  $(".profileMainContent").hide();
                  $("#profileChallenge1").show();
                  $("#challenge1Table").hide();
                  $(".loader").show();

                  setTimeout(getChallenge1Data, 3000);
        }

    
      });

          $("#profileChallenge2But").click(function () {
            
                    disable_all_side_but();
                    $(".p-side-nav-but").removeClass("active-but");
                    $("#profileChallenge2But").addClass("active-but");

                    if (!event.detail || event.detail == 1) {
                      $(".profileMainContent").hide();
                      $("#profileChallenge2").show();
                      $("#challenge2Table").hide();
                      $(".loader").show();

                      setTimeout(getChallenge2Data, 3000);
                    }

          
          });


          $("#profileChallenge3But").click(function () {
                
            disable_all_side_but();
            $(".p-side-nav-but").removeClass("active-but");
            $("#profileChallenge3But").addClass("active-but");
              if (!event.detail || event.detail == 1) {
                $(".profileMainContent").hide();
                $("#profileChallenge3").show();
                $("#challenge3Table").hide();
                $(".loader").show();

                setTimeout(getChallenge3Data, 2000);
              }
          });

          $("#profileFriendBut").click(function () {
            // alert("hello world");

            $(".profileMainContent").hide();

            $(".p-side-nav-but").removeClass("active-but");
            $("#profileFriendBut").addClass("active-but");


            getProfileUserFriends();
            $("#profileFriends").show();
          });

}

getProfileGeneralStats();

function getProfileGeneralStats(){
  $.ajax({
    type: "get",
    url: "https://www.codegreenback.com/mw/profile_mw.php",
    headers: {
      Authorization: "Bearer " + localStorage.getItem("sid"),
    },
    data: {
      'service':"GeneralStats"
    },
    dataType: 'json',
    success:function(json)
    {
      makeProfileGeneralStats(json);
    }
  });
}

function makeProfileGeneralStats(data){
    $("#profileGlobalRank").text(data.overallRank);
    $("#profileLeague").text(data.league);
    $("#profileOverAllPoints").text(data.overallPoints);
    $("#profileTotalChallenges").text(data.totalChallenges);
    $("#profileTotalQues").text(data.totalQues);
    $("#profileTotalChallengesWon").text(data.totalChallenges);
	if(data.img_url == null)
	{
		data.img_url = 'public/img/avatar.png';
	}
    $("#profile_user_image").attr("src",'https://www.codegreenback.com/'+data.img_url);
    var url = `url("https://www.codegreenback.com/public/img/${data.league}.jpeg")`;
    console.log(url);
    $("#profile_background").css("background-image", url);

}



function getProfileUserFriends()
{
  if(master_con.get_friend == 0)
  {
    $.ajax({
      type: "get",
      url: "https://www.codegreenback.com/mw/profile_mw.php",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      data: {
        service: "UserFriends",
      },
      dataType: "json",
      success: function (json) {
        makeProfileUserFriends(json);

        master_con.get_friend = 1;
      },
    });
  }

}

function makeProfileUserFriends(data)
{
  
    if(data.length == '0'){
        $("#profileFriendsBody").html("<h3>No Friends Found</h3>"); 
    }
    else{
    var txt = '';
    var obj = document.getElementById('profileFriendsBody');
    obj.innerHTML = '';
    for(var i=0;i < data.length ; i++){
        var username = data[i].username;
        var name = data[i].name;
        var rank = data[i].rank;
        var league = data[i].league;
        var imgUrl = data[i].user_image;
        if(imgUrl == null){
            imgUrl = "public/img/avatar.png";
        }
        txt =
          `<div class='friend-main-div'><a href="https://www.codegreenback.com/user/${username}/">
              <div class="profileUserCard">
              
                <div>
                  <img src="https://www.codegreenback.com/`+imgUrl+`" alt="avatar" style="margin:0 auto;width: 60px; height:60px; border-radius:50%; ">
                </div>

                <div style="margin-left:5%; width:40%">
                  <h3 style="margin:0" class='friend-username'>`+username+`</h3>
                  <p style=" margin:0">`+name+`</p>
                </div>
                <div style="margin-left: 20%">
                  <h3 style="margin:0;">League</h3>
                  <p style="margin:0;">`+league+`</p>
                </div>
              
              </div>
            </a>
            </div>`;
        
    obj.innerHTML += txt;
    // $("#userdiv").fadeIn("slow");
      }

      var totalFrnd = obj.childElementCount;
      $("#no-of-friends").text(totalFrnd);
    }

}


const ajax_req = {
  c1 : 0 ,
  c2 : 0 ,
  c3 : 0
}

function getChallenge1Data()
{
  if(ajax_req.c1 == 0)
  {
    ajax_req.c1 = 1;
        $.ajax({
          type: "get",
          url: "https://www.codegreenback.com/mw/profile_mw.php",
          headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
          },
          beforeSend: function () {
            $(".profileMainContent").hide();
            $("#profileChallenge1").show();
            $(".loader").show();
          },
          complete: function () {
            $(".loader").hide();
            ajax_req.c1 = 0;
          },
          data: {
            service: "Challenge1",
          },
          dataType: "json",
          success: function (json) {
            console.log(json);
            //  makeProfileUserFriends(json);
            makeChallengeTable(json, "challenge1Table", "c1");
          },
        });
    
  }
  enable_all_side_but();
 
}

function getChallenge2Data() {

  if(ajax_req.c2 == 0)
  {
    ajax_req.c2 = 1;
      $.ajax({
        type: "get",
        url: "https://www.codegreenback.com/mw/profile_mw.php",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("sid"),
        },
        beforeSend: function () {
          $(".profileMainContent").hide();
          $("#profileChallenge2").show();
          $(".loader").show();
        },
        complete: function () {
          $(".loader").hide();
          ajax_req.c2 = 0;
        },
        data: {
          service: "Challenge2",
        },
        dataType: "json",
        success: function (json) {
          console.log(json);
          //  makeProfileUserFriends(json);
          makeChallengeTable(json, "challenge2Table", "c2");
        },
      });
    }
    enable_all_side_but();
}

function getChallenge3Data() {

  if(ajax_req.c3 == 0)
  {
    ajax_req.c3 = 1;
    $.ajax({
      type: "get",
      url: "https://www.codegreenback.com/mw/profile_mw.php",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      beforeSend: function () {
        $(".profileMainContent").hide();
        $("#profileChallenge3").show();
        $(".loader").show();
      },
      complete: function () {
        $(".loader").hide();
        ajax_req.c3 = 0;
      },
      data: {
        service: "Challenge3",
      },
      dataType: "json",
      success: function (json) {
        console.log(json);
        //  makeProfileUserFriends(json);
        makeChallengeTable(json, "challenge3Table", "c3");
      },
    });
  }
  enable_all_side_but();
}



function makeChallengeTable(data,table,ref)
{
  txt = `    <tr>
                <th></th>
                <th>Opponent</th>
                <th>Date</th>
                <th>CC</th>
                <th>Status</th>
            </tr>`;
  for (var i = 0 ; i < data.length ; i++)
  {
    if(data[i].outGoingRequest)
    {
      trc1 = `<tr>
              <td><i class="fa fa-arrow-up" style='color:green; font-size:1.3vw;'></i></td>`;
    }
    else{
      trc1 = `<tr>
              <td><i class="fa fa-arrow-down" style='color:red; font-size:1.3vw;'></i></td>`;
    }

    trc2 = `<td style='font-size:1vw;'>${data[i].opponent}</td>`;
    trc3 = `<td>${data[i].date}</td>`;
    trc4 = `<td>${data[i].cc}</td>`;
    if(data[i].status == 'Completed')
    {
      trc5 = `<td style='text-align:center'><i class="fa fa-check" style='color:green; font-size:1.2vw'></i></td>`;
    }
    else if(data[i].status == 'Running')
    {
      trc5 = `<td><div class="spinner1">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
              </div></td>`;
      
      
    }

    if(data[i].won == true)
    {
      if(data[i].redeemStatus)
      {
        trc6 = `<td class='forwon'>+ ${data[i].ccWon}</td>`;
      }
      else{
        trc6 = `<td><button type = 'submit' class='redeemButton' id='${data[i].challengeid}' value='${data[i].challengeid}' data-value='${ref}' data-value1 = ${data[i].ccWon}>Redeem <span> + ${data[i].ccWon}</span></button></td></tr>`;
      }
    }
    else if(data[i].won == false)
    {
      trc6 = `<td class='forlose'> -${data[i].cc}</td>`;
    }
    else if(data[i].won == "NA")
    {
       trc6 = `<td class='forwon'> -- </td>`;
    }

    txt += trc1 + trc2 + trc3 + trc4 + trc5 + trc6;
    // console.log(txt);
  

  }

  $("#"+table).html(txt);
  $("#"+table).show();
  RedeemCodeCoins();
}


function RedeemCodeCoins()
{
  var but = document.querySelectorAll('.redeemButton');
  for(var i = 0; i < but.length ; i++)
  {
    but[i].addEventListener("click", function(){
      processRedeemCodeCoins(this.value, this.getAttribute("data-value"));
    });
  }
}

function processRedeemCodeCoins(id , challenge)
{
  if(challenge == 'c1' || challenge == 'c2' || challenge == 'c3')
  {
    $.ajax({
      type: "POST",
      url: "https://www.codegreenback.com/mw/profile_mw.php",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("sid"),
      },
      data:{
        service:"RC",
        cid:id,
        challenge:challenge,
        token:$("#csrf").val()
      },
      dataType:"json",
      success:function(json){ 
        console.log(json);
        if(json.status == 1)
        {
          
          var cc = document.getElementById(id).getAttribute('data-value1');
          $("#"+id).parent().addClass("forwon");
          $("#"+id).parent().html(`+${cc}`);
          
        }
        
      }
      
    });
  }
}


function myFunction() {

  var input, txtValue;
  input = document.getElementById("myInput").value;

  $(".friend-username").each(function (i, obj) {
    
    txtValue = $(".friend-username").eq(i).text();
      if (txtValue.indexOf(input) > -1) {
        $(".friend-username").eq(i).parents(".friend-main-div").css({
          display: ""
        });
      } else {
        $(".friend-username").eq(i).parents(".friend-main-div").css({
          display: "none",
          
        });
      }
  });
  
}

