<?php
/**
 * 1. Decode the JWT response and generate the validation result.
 * 2. Create the Full Wallet Request JWT
 * @copyright 2011 Google Inc. All right reserved.
 * @author Guang-Hong Peng <gpeng@google.com>
 */

// Include necessary configuration file and library for handling the JWT
include "config.php";
include "jwt.php";


// Decode the JWT response and return fail result if catch exceptions.
	$wallet_response = (array)JWT::decode($_REQUEST["maskedWallet"], $Config["merchant_secret"]);
	//$wallet_response = get_object_vars($wallet_response);
	$response = (array)$wallet_response["response"];
	$email = $response["email"];
	$pay = (array)$response["pay"];
	$buyerBilling = (array)$pay["description"];
	$ship = (array)$response["ship"];
	$shippingAddress = (array)$ship["shippingAddress"];
	$shippingName = $shippingAddress["name"];
	$shippingAddress1 = $shippingAddress["address1"];
	$shippingCity = $shippingAddress["city"];
	$shippingState = $shippingAddress["state"];
	$shippingPost = $shippingAddress["postalCode"];
	$itemPrice = $_REQUEST["unitPrice"];
	$changeJwt = $_REQUEST["changeJwt"];
/***************************************/

//Tax for the entire order. Need to change to variables
$tax = "8.00";

//Shipping fees for the entire order
$shipping = "9.99"; 

//Get the total price by using parameteres acquired from HTTP POST/GET requests
$totalPrice = floatval($_REQUEST["unitPrice"]) * floatval($_REQUEST["quantity"]);

//This array object represents the Full Wallet Request
$full_wallet_request = array(
  "iat" => (int)date("U"),
  "typ" => "google/wallet/online/full/v2/request",
  "iss" => $Config["merchant_id"],
  "aud" => "Google",
  "exp" => (int)date("U")+3600,
  "request" => array(
    "googleTransactionId" => $_REQUEST["gid"],
    "merchantTransactionId" => "merchant123123",
    "merchantName" => $Config["merchant_name"],
    "origin" => $Config["origin"],
    "cart" => array(
      "totalPrice" => $totalPrice + floatval($tax) + floatval($shipping),
      "currencyCode" => "USD",
        "lineItems" => array(
          array(
            "description" => $_REQUEST["description"],
            "quantity" => 1,
            "unitPrice" => $_REQUEST["unitPrice"],
            "totalPrice" => $totalPrice,
            "currencyCode" => $Config["currency"],
            "isDigital" => false
            ),
          array(
            "description" => "Sales tax",
            "totalPrice" => $tax,
            "currencyCode" => $Config["currency"],
            "quantity" => 1,
            "unitPrice" => $tax
            ),
          array(
            "description" => "Overnight Shipping",
            "totalPrice" => $shipping,
            "currencyCode" => $Config["currency"],
            "quantity" => 1,
            "unitPrice" => $shipping             
            )
          )
       )
    )
);

//Encode the Full Wallet Request object as a JWT
$full_wallet_request_jwt = JWT::encode($full_wallet_request, $Config["merchant_secret"]);
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1.0">
<title>XYZ Inc</title>

<link rel="stylesheet" href="css/jquery.mobile-1.1.1.min.css" />
<link rel="stylesheet" href="css/jquery.mobile.theme-1.1.1.min.css" />
<link rel="stylesheet" href="css/style.css" />

<script type="text/javascript"
  src="https://wallet-web.sandbox.google.com/online/v2/merchant/merchant.js"></script>
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</head>
<body>
  <!-- ORDER CONFIRMATION PAGE -->
  <div data-role="page" id="confirmation-page" data-theme="c">
    <div data-role="header" data-theme="b" id="header">
      <div>
        <span>XYZ, Inc.</span>

      </div>
    </div>
    <div data-role="header" data-theme="y" id="header2">
      <div class="left-header">Review order</div>
    </div>
    <div data-role="content" id="confirmation-content">
      <p class="title-name">Order summary</p>
      <table class="order-summary">
        <tr>
          <td>Item Subtotal</td>
          <td class="padding-right"><div id="confirm-subtotal">$<?=$itemPrice?></div></td>
        </tr>
        <tr>
          <td>Estimated Shipping</td>
          <td class="padding-right">$9.99</td>
        </tr>
        <tr>
          <td>Tax (CA)</td>
          <td class="padding-right">$8.00</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td><b>Total</b></td>
          <td class="padding-right"><b>$<?= $_REQUEST["unitPrice"]+9.99+8.00?></b></td>
        </tr>
      </table>
      <hr />
      <p class="title-name">Payment Information</p>
    </div>
    <div data-role="content">
      <p class="subtitle">
        Buy with: <img src="img/GreyLogo124_26.png"></img>
      </p>
      <table class="payment-summary">
        <tr>
          <td class="padding-left"><div id="conbilling">
			  <?= $email?>
              <br /> <?= $buyerBilling[0] ?>
            </div></td>
          <td class="button-right" >
            <button id="change_billing" data-corners="false">Change</button>
          </td>
      </table>
      <hr />
      <p class="subtitle">Ship to:</p>
      <table class="payment-summary">
        <tr>
          <td class="padding-left" id="conshipping"><div id="conshipping"><?= $shippingName ?> <br /><?= $shippingAddress1 ?>
            <br /><?= $shippingCity ?>, <?= $shippingState ?> <br /> <?= $shippingPost ?>
</div>          
</td>
          <td class="button-right">
            <button id="change_shipping" data-corners="false">Change
            </button>
          </td>
      </table>
    </div>
    <div class="bottom-button">
      <button id="place_order" data-theme="a" data-corners="false"
        onclick="requestFullWallet">Place order</button>
    </div>
    <!-- Form used to continue the purchase flow by posting fullWallet response to the server -->
    <form id="placeOrderForm" name="placeOrderForm" action="/xyz-php/receipt.php"
      method="post">
      <input type="hidden" name="fwr" id="fwr" value ="<?=$full_wallet_request_jwt?>"> 
<input type="hidden" name="fullWallet" id="fullWallet">
<input
        type="hidden" name="unitPrice" id="unitPrice" value="<?=$itemPrice?>">
    </form>

    <!-- Form used to change the maskedWallet information -->
    <form id="updateCredentialsForm" name="updateCredentialsForm"
      action="/xyz-php/confirm.php" method="post">
      <input type="hidden" name="maskedWallet" id="maskedWallet">
      <input type="hidden" name="changeJwt" id="changeJwt" value= "<?= $changeJwt?>"> <input
        type="hidden" name="gid" id="gid"> <input type="hidden"
        name="description" id="description"
        value="Description for Camera XY001"> <input
        type="hidden" name="quantity" id="quantity" value="1"> <input
        type="hidden" name="unitPrice" id="unitPrice" value="<?=$itemPrice?>">
    </form>
  </div>

</body>
<script type="text/javascript">
	window.onload = initOrderClickEvent;
</script>
</html>