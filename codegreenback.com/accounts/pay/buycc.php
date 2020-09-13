<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


if(!Session::exists(Config::get('session/session_name')))
{   
    Redirect::to('index');
}
include_once '../../after_login_header.php';

$csrf_token = Token::generate('csrf');


?>

<link rel="stylesheet" href="public/css/buycc.css">
<div class="row" id="buycc-div">


  <div id="wrap" class="cards-one">
  


  </div>
  
  <!-- partial -->
    
  <div id="wrap" class="cards-two">


  
  </div>
</div>

<div id='footer-div'>
<?php  require_once dirname(__DIR__,2).'/footer.php' ?>
</div>




  <input type="hidden" name="cs" id="csrf" value="<?php echo $csrf_token; ?>">
<div class="spinner1">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){


        $.ajax({
	url:"https://www.codegreenback.com/accounts/pay/gateway.php",
          type:"GET",
          headers:{
              Authorization: "Bearer " + localStorage.getItem("sid"),
          },
          dataType:"JSON",
          beforeSend: function(){
            $(".spinner1").show();
          },

          complete: function(){
              $(".spinner1").hide();
              $("#buycc-div").show();
            },
          data:{
              "service" :"get_offers",
              "token" :$("#csrf").val()
            },

            success:function(json){
              console.log(json);
              txt1 = '';
              txt2 = '';
              

                txt1 += `<div class="card">
                            <div class="thumb" id="one"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Starter</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.starter.totalPrice}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='starter'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;

                txt1 += `<div class="card">
                            <div class="thumb" id="two"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Basic</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.basic.totalPrice}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='basic'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;

                txt1 += `<div class="card">
                            <div class="thumb" id="three"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Standard</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.standard.totalPrice}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='standard'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;

                txt2 += `<div class="card">
                            <div class="thumb" id="one1"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Plus</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.plus.totalPrice}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='plus'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;



                txt2 += `<div class="card">
                            <div class="thumb" id="two2"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Pro</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.solid.totalPrice}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='solid'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;

                if(json.userType == "non-elite")
                {
                            txt2 += `<div class="card">
                            <div class="thumb" id="three3"></div>
                            <div class="option"></div>
                            <div style="width: 100%; text-align:center; position:absolute; top:49%; display:flex; justify-content:center; flex-direction:column">
                              <div>
                                <h3>Elite MemberShip</h3>
                              </div>
                              <div>
                                <hr/>
                                <p class="price">&#8377;${json.eliteMemberShip}</p>
                                <hr/>
                              </div>

                              <div style="margin: 0 auto; width:100%">
                                  <button class="buy buy-but" value='eliteMemberShip'>Buy Now</button>
                              </div>
      
                            </div>
                      </div>`;
                }

        
                    $(".cards-one").html(txt1);
                    $(".cards-two").html(txt2);
              
           


              var but = document.querySelectorAll(".buy-but");
        const token = $("#csrf").val();

        for(var i = 0 ; i< but.length ; i++)
        {
          but[i].addEventListener("click", function(){
            const redirectUrl = 'https://www.codegreenback.com/accounts/pay/pay.php?pack='+ this.value +'&_tid='+token;
            window.location.replace(redirectUrl);
          });
        }
            }
        })


        



    });

</script>



