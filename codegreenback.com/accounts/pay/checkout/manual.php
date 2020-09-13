

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style>

    *{
        margin:0;
        padding: 0;
        box-sizing: border-box;
    }
    .orders-main-div {
        position: absolute;
        width: 50vw;
        height: 70vh;
        background-color: white;
        top:20%;
        left:10%;
    }

    .order-header {
        width: 100%;
        /* height: 10vh; */
        background-color: rgb(227, 250, 250);
        padding: 15px;
    }

    .order-body{
        display: flex;
        flex-direction: column;
    }
    .product-div , .payment-div {
        width: 100%; 
        display:flex; 
        justify-content:space-between;
        margin-top:10px;
        padding: 10px;
        border-top: 0.5px solid rgba(27, 60, 110, 0.3);
    }

    .payment-div {
        margin-top:20px;
 
    }
    .product-title{
        /*  */
        width: 70%;
        padding: 10px;
    }

    .product-price {
        width: 30%;
        padding: 10px;
        text-align: center;
    }

    #rzp-button1 {
        width: 12vw;
        padding: 5px;
        font-size: 1.2vw;
        border-radius: 20px;
        background-color: #18183b ;
        color:white;
    }


</style>


<div class='orders-main-div'>
    <div class='order-header'>
        <div>
            <h1 id="order-pack" style="text-transform: capitalize;"></h1>
        </div>
        <div>
            <h3 style="display:inline-block">Order : </h3>
            <h4 id="order-id" style="display: inline-block;"></h4>
        </div>
    </div>

    <div class="order-body">
        <!-- <div class="product-div">
            <div class="product-title">
                <h2>Basic Pack</h2>
            </div>

            <div class="product-price">
                <h2><span>&#8377;</span>232.00</h2>
            </div>
        </div> -->

    
    </div>

    <div class="payment-div">
        <div>
            <h1>Total(INR)</h1>
        </div>
        <div class="product-price">
            
            <h2><span>&#8377;</span> <span id="total-amount"></span></h2>
            <div>
                <button id="rzp-button1">Pay</button>
            </div>
        </div>
    </div>
</div>


<form name='razorpayform' action="verify.php" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature">
    <input type="hidden" name="csrf" value="<?php echo $csrfToken ?>">
</form>



<script>
// Checkout details as a json
var options = <?php echo $json?>;

$(document).ready(function(){
    $("#order-pack").text(order.package);
    $("#order-id").text(options.receipt);
    if(order.orders === 2)
    {
        txt = `<div class="order-body">
            <div class="product-div">
                <div class="product-title">
                    <h2 style='text-transform: capitalize;'>${order['order-1']['order-name']}</h2>
                </div>

                <div class="product-price">
                    <h2><span>&#8377;</span>${order['order-1']['price'].toFixed(2)}</h2>
                </div>
            </div>
            </div>`;

        txt += `<div class="order-body">
            <div class="product-div">
                <div class="product-title">
                    <h2>${order['order-2']['order-name']}</h2>
                </div>

                <div class="product-price">
                    <h2><span>&#8377;</span>${order['order-2']['price'].toFixed(2)}</h2>
                </div>
            </div>
            </div>`;

        $(".order-body").html(txt);
        $("#total-amount").text(order.totalPrice.toFixed(2));
    }
    else if(order.orders === 1){
            txt = `<div class="order-body">
            <div class="product-div">
                <div class="product-title">
                    <h2 style='text-transform: capitalize;'>${order['order-1']['order-name']}</h2>
                </div>

                <div class="product-price">
                    <h2><span>&#8377;</span>${order['order-1']['price'].toFixed(2)}</h2>
                </div>
            </div>
            </div>`;

        $(".order-body").html(txt);
        $("#total-amount").text(order.totalPrice.toFixed(2));
    }

});



/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = true;

options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>
