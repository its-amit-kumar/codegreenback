<?php

require('config.php');
require('razorpay-php/Razorpay.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(Session::exists(Config::get('session/session_name')))
{
    $user = Session::get(Config::get('session/session_name'));
}
else{
    header("Location: http://www.codegreenback.com");                            
    exit;

}
require_once "../../after_login_header.php";


if(isset($_GET['pack']) && isset($_GET['_tid']))
{
    if(Token::check($_GET['_tid'] , 'csrf'))
    {
        $obj = new CodeCoins();
        $order = $obj->getPack($_GET['pack']);

    }
    else{
        echo "Bad Request";                                         //redirect user to error page;
        exit;
    }
}

// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//


$csrfToken = Token::generate('csrf');



$orderData = [
    'receipt'         => Hash::generateOrderId(),
    'amount'          => $order['totalPrice'] * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

// echo $razorpayOrderId;

// $_SESSION['razorpay_order_id'] = $razorpayOrderId;
// $_SESSION['cc_to_transfer'] = $order['total-cc'];                        //might be a bad practise //alternate : make a user cart table and identify each order with order id
// $_SESSION['package_to_buy'] = $order['package'];
// $_SESSION['amount_to_paid'] = $order['totalPrice'];

// remove previous order 
Session::removeOrder();
// make new order 
Session::setOrder($razorpayOrderId, $order['total-cc'], $order['package'], $order['totalPrice'], $orderData['receipt']);

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'manual';



$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $user,
    "description"       => $order['package'],
    "image"             => "http://www.codegreenback.com/public/img/1.png",
    "prefill"           => [
    "name"              => $_SESSION['user'],
    "email"             => $_SESSION['email'],
    "contact"           => "9999999999",
    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
    'receipt'           => $orderData['receipt'],
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

require("checkout/{$checkout}.php");
?>

<script type="text/javascript">
    order = <?php echo json_encode($order) ?>
</script>
