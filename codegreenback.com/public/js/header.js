

    $(document).ready(function(){
      if($("#signup-status").val() != '')
      {
        customAlert($("#signup-status").val());
      }

	const container = document.getElementById("container_model");

      $("#show_signup_but").click(function(){
	//customAlert("Excited to be a part of CodeGreenBack ? We are also excited to see you here. Wait for the launch till then happy coding.");
        $("#container_model").fadeIn();
        container.classList.add("right-panel-active");
      });

      $("#show_login_but").click(function(){
          $("#container_model").fadeIn();
          container.classList.remove("right-panel-active");
      });

	$("#get_started_but").click(function(){
		 $("#container_model").fadeIn();
        	container.classList.add("right-panel-active");
	});

      const signUpButton = document.getElementById("signUp");
      const signInButton = document.getElementById("signIn");
      
      signUpButton.addEventListener("click", () => {
	//customAlert("Excited to be a part of CodeGreenBack ? We are also excited to see you here. Wait for the launch till then happy coding.");
        container.classList.add("right-panel-active");
      });

      signInButton.addEventListener("click", () => {
        container.classList.remove("right-panel-active");
      });

      $(".close_signup_login").click(function(){
        $("#container_model").fadeOut();
      });
    });

//    $('[data-toggle="tooltip"]').tooltip();

    document.getElementById("signupButton").addEventListener("click", (e) => {
      e.preventDefault();
      //customAlert("Excited to be a part of CodeGreenBack ? We are also excited to see you here. Wait for the launch till then happy coding.");
      setCaptcha("g-token-signup", checkSignUpInputs);
    });

    document.getElementById("loginButton").addEventListener("click", (e) => {
      e.preventDefault();
      setCaptcha("g-token-login", checkLoginInput);
      // checkLoginInput();
    });

    function checkSignUpInputs() {
      const nameValue               = document.getElementById("name").value.trim();
      const usernameValue           = document.getElementById("username").value.trim();
      const emailValue              = document.getElementById("email").value.trim();
      const passwordValue           = document.getElementById("password").value.trim();
      const password_againValue     = document.getElementById("password_Again").value.trim();

      if (!isname(nameValue)) {
        //show error and add error class
        setErrorFor(document.getElementById("name"), "Invalid Name");
        return 0;
      } else {
        setSuccessFor(document.getElementById("name"));
      }


      if (emailValue == "") {
        setErrorFor(document.getElementById("email"), "Email cannot be blank");
        return 0;
      } else if (!isEmail(emailValue)) {
        setErrorFor(document.getElementById("email"), "Invalid Email Address");
        return 0;
      } else {
        setSuccessFor(document.getElementById("email"));
      }

      if (!isUserName(usernameValue)) {
        setErrorFor(document.getElementById("username"), "Invalid Username");
        return 0;
      } else {
        setSuccessFor(document.getElementById("username"));
      }


      if (!ispass(passwordValue)) {
        setErrorFor(document.getElementById("password"), "Invalid Password");
        return 0;
      } else if (passwordValue != password_againValue) {
        setErrorFor(document.getElementById("password_Again"), "Password Do Not Match");
        return 0;
      } else {
        setSuccessFor(document.getElementById("password"));
        setSuccessFor(document.getElementById("password_Again"));
      }

	if($("#accept_tnc").is(":checked") && $("#accept_pp").is(":checked"))
	{
	register(
        nameValue,
        usernameValue,
        emailValue,
        passwordValue,
        password_againValue
      );

	}else{customAlert(`please accept terms and conditions of use and privacy policy`)}

    }

    function setErrorFor(input, message) {
      const formGroup = input.parentElement;
      const small = formGroup.querySelector("small");
      small.innerText = message;

      formGroup.className = "form-group error";
    }

    function setSuccessFor(input) {
      const formGroup = input.parentElement;
      formGroup.className = "form-group success";
    }

    function isEmail(email) {
      var filter = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return filter.test(email);
    }

    function isname(name) {
      var re = /^[a-zA-Z ]{8,30}$/;
      return re.test(name);
    }

    function isUserName(name) {
      var re = /^[a-zA-z0-9 ]{5,30}$/;
      return re.test(name);
    }

    function ispass(pass) {
      var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,32}$/;
      return regularExpression.test(pass);
    }

    function register(nV, uV, eV, pV, paV) {
      token = $("#token1").val();
      g_token = $("#g-token-signup").val();
      $.ajax({
        type: "post",
        data: {
          "g-token": g_token,
          service: "signup",
          name: nV,
          username: uV,
          email: eV,
          token: token,
          password: pV,
          password_again: paV,
        },
        beforeSend:function(){
          $(".signup-loader").show();
        },
        complete:function(){
          $(".signup-loader").hide();
        },
        dataType: "json",
        url: "/mw/signup_mw.php",
        success: function (result) {
          checkRegisterStatus(result);
          console.log(result);
        },
      });
    }

    function checkRegisterStatus(result) {
      if (result.status == "1") {
        $("#signup .close").click();
        customAlert(
          `A Verification link has been sent to Your Provided Email Address: ${email.value} .  Please Verify The email to SignUp Successfully ! Check Spam if not in Inbox.`
        );
      } else {
        for (var i = 0; i < result.length; i++) {
          if (result[i].username) {
            setErrorFor(username, result[i].username);
            break;
          } else if (result[i].name) {
            setErrorFor(name, result[i].name);
            break;
          } else if (result[i].email) {
            setErrorFor(email, result[i].email);
            break;
          } else if (result[i].password) {
            setErrorFor(password, result[i].password);
            break;
          } else if (result[i].password_again) {
            setErrorFor(password_again, result[i].password_again);
            break;
          }
        }
      }
    }

    function checkLoginInput() {

      const usrnameValue    =   document.getElementById("usrname").value.trim();
      const pwdValue        =   document.getElementById("psw").value;
      const remValue        =   $("#remember").is(":checked");

      token = $("#token2").val();
      if (!isUserName(usrnameValue)) {
        setErrorFor(document.getElementById("usrname"), "Invald");
	return 0;
      } else {
        setSuccessFor(document.getElementById("usrname"));
      }

      if (pwdValue == "") {
        setErrorFor(document.getElementById("psw"), "cannot be empty");
	return 0;
      }else {
        setSuccessFor(document.getElementById("psw"));
      }
      
      //make ajax call

      g_token = $("#g-token-login").val();
      // console.log(g_token);

      $.ajax({
        type: "post",
        data: {
          "g-token": g_token,
          service: "login",
          username: usrnameValue,
          pass: pwdValue,
          remember: remValue,
          token: token,
        },
        beforeSend: function () {
          $(".login-loader").show();
        },
        complete: function () {
          $(".login-loader").hide();
        },
        url: "mw/signup_mw.php",
        success: function (result) {
          console.log(result);
          checkLoginStatus(result);
        },
      });
    }

    function checkLoginStatus(result) {
      if (result == 0) {
        $("#login_errorMsg").html(
          "<p style='font-size=20px;color=red;'>Username Or Password Incorrect !!</p>"
        );
      }
      if (result == 1) {
        window.location.replace("https://www.codegreenback.com/Home/");
      }
    }

    async function setCaptcha(id, _callback) {
      var inp = document.getElementById(id);
      await grecaptcha
        .execute("6Lf3xfcUAAAAAGA7fRf9zpv27UsdXmEmRXnsItLK", {
          action: "homepage",
        })
        .then(function (token) {
          inp.value = token;
        });

      _callback();
    }




            //  to display custom msg modify this funtion for signup success msg

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

		
		function onSignIn(googleUser) {
                // Useful data for your client-side scripts:
                var profile = googleUser.getBasicProfile();
                console.log("ID: " + profile.getId()); // Don't send this directly to your server!
                console.log("Full Name: " + profile.getName());
                console.log("Given Name: " + profile.getGivenName());
                console.log("Family Name: " + profile.getFamilyName());
                console.log("Image URL: " + profile.getImageUrl());
                console.log("Email: " + profile.getEmail());

                // The ID token you need to pass to your backend:
                var id_token = googleUser.getAuthResponse().id_token;
                console.log("ID Token: " + id_token);
                tobeSend = {
                  service: "google",
                  id_token: id_token,
                  token: document.getElementById("tokeng").value,
                };

                $.ajax({
                  url: "/mw/signup_mw.php",
                  type: "POST",
                  data: tobeSend,
                  success: function (text) {
                    text = JSON.parse(text);
                    if (text["status"] == "getUsername") {
                      $(".modal-body").empty();
                      $(".modal-body").append(
                        "<div class='form-group'><label for='username'><span><i class='far fa-hand-paper'></i></span>&nbsp; Username</label><i class='fas fa-info-circle' data-toggle='tooltip' title='Username Must AlphaNumeric i.e only Alphabets and Numbers are Allowed. Min: 5 and Max : 30 Characters'></i><input type='text' class='form-control' id='usernameGoogle' name='username' placeholder='Create username'><i class='fas fa-check-circle'></i><i class='fas fa-exclamation-circle'></i><small>Error Msg</small></div><button type='submit' class='btn btn-default btn-info' style='width: 100%;border-radius: 5px;' id='googleSign'><span class='glyphicon glyphicon-off'></span>&nbsp;Complete Login</button> &nbsp;&nbsp;"
                      );

                      $("#googleSign").click(function () {
                        tobeSend = {
                          service: "google",
                          id_token: id_token,
                          token: document.getElementById("tokeng").value,
                          username: document
                            .getElementById("usernameGoogle")
                            .value.trim(),
                        };
                        $.ajax({
                          url: "/mw/signup_mw.php",
                          type: "POST",
                          data: tobeSend,
                          success: function (text) {
                            if (text == 1) {
                              window.location.replace("home.php");
                            } else {
                              alert("error");
                            }
                          },
                        });
                      });
                    }
                    if (text["status"] == "1") {
                      window.location.replace("https://www.codegreenback.com/Home/");
                    }
                  },
                });
              }


$(document).ready(function(){
  const navBar = document.querySelector("#nav-bar");
  const navButton = document.querySelector(".nav-toggle");

  $(".nav-toggle").click(function(){
    toggleNavigation();
  });

  // Hamburger Navigation
  function toggleNavigation() {
    if (navBar.classList.contains("is-open")) {
      // this.setAttribute("aria-expanded", false);
      $("#nav-bar").attr("aria-expanded", false);

      navBar.classList.remove("is-open");
    } else {
      navBar.classList.add("is-open");
      $("#nav-bar").attr("aria-expanded",true);

    }
  }
});


    
