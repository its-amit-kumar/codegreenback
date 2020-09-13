<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


if(!Session::exists(Config::get('session/session_name')))
{   
    Redirect::to('index');
}
include_once '../../after_login_header.php';

$csrf_token = Token::generate('csrf');



?>


<link rel="stylesheet" href="public/css/style.css?id=001">
<script src="public/js/plugins/chartjs/dist/Chart.js"></script>



<div style="position: absolute; width:100%; height: 100%; background-color:white; z-index:10000;display:flex; justify-content:center; align-items:center" id="w-loader"> 

  <img src="public/White_Loading.gif" alt="loader" style="position:absolute; top:20%">

</div>

<div class='container-redeem' style="display: none;">

<div>
    <div class="chart-container" style="position: relative; width:35vw; background-color: rgb(255, 255, 255);margin-right: 30px;padding-right: 30px; top:10%; border-right:2px solid rgba(0, 0, 0, 0.418)">
    <div>
      <h3>CodeCoins Statistics</h3>
    </div>

    <canvas id="myChart" width="70px" height="35px"></canvas>

  </div>

  <div style="position: relative; top:15%; display:flex; justify-content:center; align-items:center; flex-direction:column">
    <div style="border-bottom: 1px solid black;text-align:center; width:60%;border-bottom: 3px inset rgba(28,110,164,0.79);
border-radius: 40px 40px 40px 40px;">
      <h3>Pending Settlement</h3>
    </div>

    <div id="display-setlement">
      <h3 style="margin-top: 30px;font-size:20px">No Pending Settlement</h3>
    </div>
  </div>



</div>
<input type="hidden" name="" id="token_csrf" value = "<?php echo $csrf_token; ?>">

<script src="public/js/redeem.js?id=001"></script>

<div style="display: flex; flex-direction: column; " style="display: none;">

    <div>
      <h1>
        Withdraw CodeCoins
      </h1>

      <p style="margin-left: 10px">Transfer Money Directly To Your Bank Account</p>
    </div>
  
    <div class='window'>
        <div class='credit-info'>
          <div class='credit-info-content'>
            <h1 style="padding-left: 5em;margin-top: 1em; font-size: 25px;">CodeGreenBack</h1>
            <img src='public/gcb.png' height='100' class='card-image' id='card-image' style="margin-top: -4.5em; margin-left: 2em; margin-bottom: 3em;"></img>
            Account Number
            <input type="password" class='input-field' id="w-acc-no"></input>
            Re-Enter Account Number
            <input type="text" class='input-field' id="w-r-acc-no"></input>
            <table class='half-input-table'>
              <tr>
                <td> IFSC CODE
                  <input  type="text" class='input-field' id="w-ifsc"></input>
                </td>
                <td>Phone Number
                  <input type="text" class='input-field' id="w-phone"></input>
                </td>
                
              </tr>
            </table>
            <div class="slider">
              <input type="range" min="100" max="1000" value="100"  id="w-cc-slider">
              
              <p id="rangeValue" style="margin-left: 1em;">100</p>
              
            </div>
            <button class='pay-btn'>Redeem</button>

          </div>

        </div>
<div class='order-info'>
      <div class='order-info-content'>
        <h2>Order Summary</h2>
                <div class='line-re'></div>
        <table class='order-table'>
          <tbody>
            <tr>
              <td><img src='public/man.png' class='full-width' id="w-userimg" style="border-radius: 50%;"></img>
              </td>
              <td>
                <br> <span class='thin' id="w-name"></span>
                <br> <br> <span class='thin small' id="w-email"><br><br></span>
              </td>

            </tr>
            <tr>
              <td>
              </td>
            </tr>
          </tbody>

        </table>
        <div class='line-re'></div>
        <table class='order-table'>
          <tbody>
            <tr>
              <td><img src='public/codecoin.gif' class='full-width'></img>
              </td>
              <td>
                <br> <span class='thin'>CODE COINS</span>
                <span class='thin small'></span><br><br>Remaining :
              </td>
            </tr>
            <tr>
              <td>
                <div class='price' id="w-usercc"></div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class='line-re'></div>
        <table class='order-table'>
          <tbody>
            <tr>
              <td><img src='public/timer.png' class='full-width'></img>
              </td>
              <td>
                <br> <span class='thin'>Transaction Details</span><br>
                <span class='thin small'>Processing ID : <span id="w-new-processing-id"></span> </span> <br><br>New Settlement<br><br> 
              </td>

            </tr>
            <tr>
              <td>
              </td>
            </tr>
          </tbody>
        </table>
        <div class='line-re'></div>
        <div class='total'>
          <span style='float:left;'>
            <div class='thin dense'>CC Exchange</div>
            <div class='thin dense'>Service Charge</div>
            Redeem
          </span>
          <span style='float:right; text-align:right;'>
            <div class='thin dense' id="w-cc-exchange">&#8377;47.00</div>
            <div class='thin dense' id="w-service-charge">- &#8377;2.50</div>
            <span id="w-redeem-cc">&#8377;45.00</span>
          </span>
        </div>
</div>
</div>
    
      </div>


<p>Need Assistance ? Reach out to us at support@codegreenback.com</p>
</div>

    </div>
<!-- partial -->

<!-- custom alert divs -->
<div id="customAlert" class="customModal">

  <!-- Modal content -->
  <div class="customModal-content">
    <span class="customClose">&times;</span>
    <p id="customAlertMsg"></p>
  </div>

</div>



<!-- ..................loader................ -->
<div class="overlay-loader-w" style="display: none;">
    <div class="overlay__inner-loader-w">
        <div class="overlay__content-loader-w">
          <div>
            <span class="spinner-loader-w"></span>
          </div>
          
          <div>
            <p class='process-msg-w'>Processing Your Request ! Please Wait</p>
          </div>
        </div>
    </div>
</div>



<!-- ........................pin modal...................... -->

<div id="wrapper" style="display: none;">
  <div id="dialog">
    <h3>Please enter a 6-digit verification code sent to your registered email</h3>
    <div id="form">
      <div style="display: flex;">
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
        <input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" class='pin-inputs' />
      </div>
      <div>
        <button class="btn btn-primary btn-embossed" id="w-verify">Verify</button>
        <div class="spinner1" style="display: none;">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
      </div>

      
      
    </div>
    
    <div>
      <p style="font-size: 15px; margin:0; padding:0;">Didn't receive the code?</p>
      <p style="font-size: 20px; margin:0; padding:0;" id="w-send-code-again">Send code again</p>
    </div>
  </div>

  <div  id="w-pin-msg-div" style="display: none;">
    <h3 id="w-pin-msg"></h3>
  </div>
</div>


<div id="re-footer" style="position: absolute; bottom:0; width:100%; height:2%;">
  <?php require_once '../../footer.php'; ?>
</div>

</body>
</html>
