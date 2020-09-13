// A $( document ).ready() block.
$(document ).ready(function() {
  
    var ajaxReq = 'ToCancelPrevReq'; // you can have it's value anything you like
    
    $( "#search" ).keyup(function(e) {

        var queryString = $( "#search" ).val();

        if(queryString == '')
        {
          $("#mainDiv").fadeOut();
        }
        else{
          $("#mainDiv").fadeIn();
        }

        if (
          (e.keyCode >= 65 && e.keyCode <= 90) ||
          (e.keyCode >= 48 && e.keyCode <= 57)
        ) {
          ajaxReq = $.ajax({
            url: "https://www.codegreenback.com/mw/search_mw.php",
            type: "GET",
            data: { query: queryString },
            dataType: "JSON",
            headers: {
              Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            beforeSend: function () {
              if (ajaxReq != "ToCancelPrevReq" && ajaxReq.readyState < 4) {
                ajaxReq.abort();
              }
            },
            success: function (json) {
		console.log(json);
              makeUserDiv(json);
            },
            error: function (xhr, ajaxOptions, thrownError) {
              if (thrownError == "abort" || thrownError == "undefined") return;
              alert(
                thrownError +
                  "\r\n" +
                  xhr.statusText +
                  "\r\n" +
                  xhr.responseText
              );
            },
          }); //end ajaxReq
        }

        
     
    }); //end keyup
 
     getRecommendedUser();
    setTimeout(function(){
      getRecents();
    },3000);


});//end document.ready




function getRecents()
{
  $.ajax({
        url: "https://www.codegreenback.com/mw/search_mw.php",
        type: "get",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("sid"),
        },
        data:{
          'service' : "_recents",
        },
        dataType:"json",
        success:function(data){
          
            makeRecentusers(data);
          
        }
    });

}


function makeRecentusers(data)
{
  let txt = '';
  if(data == 0)
  {
    $(".recent-users-div").html(`No Recent Challenges`);
  }
  else
  {
      for (let i = 0; i < data.length; i++) {
        txt += `<a href="#">
                <div class="SearchRecentUsers">
                  <p>${data[i].opponent}<span style="font-size: 10px;padding:5px">-${data[i].challenge}</span> </p> 
                </div>  
            </a>`;
      }

      $(".recent-users-div").html(txt);
  }

}



function makeUserDiv(data){
    console.log(data.length);
    if(data.length == '0'){
        $("#mainDiv").html("<h3>No Users Found !!</h3>"); 
    }
    else{
    var txt = '';
    var obj = document.getElementById('mainDiv');
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
          `<a href="https://www.codegreenback.com/user/${username}/">
              <div class="profileUserCard">
              
                <div>
                  <img src="https://www.codegreenback.com/${imgUrl}" alt="avatar" style="width: 50px; height:50px; border-radius:50%; ">
                </div>

                <div style="margin-left:5%; width:40%">
                  <h4 style="margin:0">` +username +`</h4>
                  <p style=" margin:0">` +name +`</p>
                </div>
                <div style="margin-left: 10%">
                  <h6 style="margin:0;">League</h6>
                  <p style="margin:0;" class='user-league'>` +league +`</p>
                </div>              
              </div>
            </a>`;
        
    obj.innerHTML += txt;
    $("#userdiv").fadeIn("slow");

    }
    
}
}



function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

$(document).ready(function () {
  var textWrapper = document.querySelector(".ml14 .letters");
  textWrapper.innerHTML = textWrapper.textContent.replace(
    /\S/g,
    "<span class='letter'>$&</span>"
  );

  anime
    .timeline({ loop: true })
    .add({
      targets: ".ml14 .line",
      scaleX: [0, 1],
      opacity: [0.5, 1],
      easing: "easeInOutExpo",
      duration: 900,
    })
    .add({
      targets: ".ml14 .letter",
      opacity: [0, 1],
      translateX: [40, 0],
      translateZ: 0,
      scaleX: [0.3, 1],
      easing: "easeOutExpo",
      duration: 800,
      offset: "-=600",
      delay: (el, i) => 150 + 25 * i,
    })
    .add({
      targets: ".ml14",
      opacity: 0,
      duration: 1000,
      easing: "easeOutExpo",
      delay: 1000,
    });
});


      document.onclick = function (e) {
        if (e.target.id !== "mainDiv" && e.target.id !== "search") {
          // hideMe.style.display = "none";
          $("#mainDiv").fadeOut();
          $("#search").val("");
        }
      };



 var slideIndex = 1;
 showSlides(slideIndex);

 function plusSlides(n) {
   showSlides((slideIndex += n));
 }

 function currentSlide(n) {
   showSlides((slideIndex = n));
 }

 function showSlides(n) {
   var i;
   var slides = document.getElementsByClassName("mySlides");
   if (n > slides.length) {
     slideIndex = 1;
   }
   if (n < 1) {
     slideIndex = slides.length;
   }
   for (i = 0; i < slides.length; i++) {
     slides[i].style.display = "none";
   }

   var idex = slideIndex - 1;
   // slides[slideIndex - 1].style.display = "block";
   $(`.mySlides:eq(${idex})`).fadeIn("slow");
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
      $("#recommended-Users-div").html(txt);
    }
