<?php
/**
 * 1. Decode the JWT response and return the validation result.
 * 2. Generate the JWT for notification.
 * @copyright 2011 Google Inc. All right reserved.
 * @author Guang-Hong Peng <gpeng@google.com>
 */

// Include necessary configuration file and library for handling the JWT
include "config.php";
include "jwt.php";

// Decode the JWT response and return fail result if catch exceptions.
	$wallet_response = (array)JWT::decode($_REQUEST["fullWallet"], $Config["merchant_secret"]);
	//$wallet_response = get_object_vars($wallet_response);
	$response = (array)$wallet_response["response"];
	$receiptEmail = $response["email"];
	$receiptPrice = $_REQUEST["unitPrice"];
	$totalPrice = $_REQUEST["unitPrice"]+9.99+8.00;
	
/*****************************/

/**
 * Create a Transaction Status Notification and return it as an encoded JWT (JSON Web Token)  
   *    object.
 */

//This array object represents the notification response of a transaction status 
$transaction_status_notification = array(
    "iss" => $Config["merchant_id"],
    "aud" => "Google",
    "iat" => (int)date("U"),
    "typ" => "google/wallet/online/transactionstatus/v2",
    "request" => array(
      "googleTransactionId" => $_REQUEST["gid"],
      "merchantTransactionId" => "merchant123123",
      "status" => "SUCCESS"
    )
);

//Encode the notification response as a JWT
$transaction_status_notification_jwt = JWT::encode($transaction_status_notification, $Config["merchant_secret"]);

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
<script type="text/javascript">
	window.onload = notifyTransactionStatus;
	</script>

</head>
<body>
  <!-- RECEIPT PAGE -->
  <div data-role="page" id="receipt" data-theme="c">
    <div data-role="header" data-theme="b" id="header">
      <div>
        <span>XYZ, Inc.</span>
      </div>
    </div>
    <div data-role="header" data-theme="y" id="header2">
      <div class="left-header">Thanks for shopping at XYZ, inc</div>
    </div>
    <div data-role="content" data-theme="c" id="receipt-content">
      <p class="title-name">Order summary</p>
      <table class="order-summary">
        <tr>
          <td>Item Subtotal</td>
          <td class="padding-right" >$<?= $receiptPrice ?></td>
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
          <td class="padding-right"><b>$<?= $totalPrice ?></b></td>
        </tr>
      </table>
      <p class="title-name">Confirmation Details</p>
      <table class="receipt">
        <tr>
          <td>Secured by <img src="img/GreyLogo124_26.png"></img>
          </td>
        </tr>
        <tr>
          <td>Your order confirmation number is AH1234567890 .Your
            purchase will be shipped within two business days, your
            tracking number will be sent to you via email at
            <?= $receiptEmail?></td>
        </tr>
        <tr></tr>
      </table>
    </div>
  </div>
<form id ="notificationForm"> 
<ibput type="hidden"  name = "notificationJwt" id="notificationJwt" value = "<?= $transaction_status_notification_jwt?>" ></input>
</form>
</body>
</html>