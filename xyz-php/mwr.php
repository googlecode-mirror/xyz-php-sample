<?php
/**
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @author Guang-Hong Peng <gpeng@google.com>
 * @version 1.0
 */

/**
 * Create a Masked Wallet Request and return it as a JWT.
 */

include "config.php";
include "jwt.php";
$masked_wallet_request = array(
  "aud" => "Google",
  "iat" => (int)date("U"),
  "iss" => $Config["merchant_id"],
  "typ" => "google/wallet/online/masked/v2/request",
  #"typ" => "google/wallet/online/masked/v2/request",
  "request" => array(
    "clientId" => $Config["client_id"],
    "merchantName" => $Config["merchant_name"],
    "origin" => $Config["origin"],
	  "phoneNumberRequired" => true,
    "pay" => array(
      "estimatedTotalPrice" => "150.01",
      "currencyCode" => $Config['currency']
    ),
    "ship" => array()
  )
);
$masked_wallet_request_jwt = JWT::encode($masked_wallet_request, $Config["merchant_secret"], true);
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
	window.onload = checkAuth;
</script>
</head>
<body>
  <!-- ORDER DETAIL PAGE -->  
  <div data-role="page" id="order-detail" data-theme="c">
    <div data-role="header" data-theme="b" id="header">
      <div>
        <span>XYZ, Inc.</span>
      </div>
    </div>
    <div data-role="header" data-theme="y" id="header2">
      <div class="left-header">Complete your purchase</div>
    </div>
    <div data-role="content" id="order-content">
      <p class="title-name">Shopping Cart</p>
      <table class="cart-summary">
        <td class="cart-image"><img class="display-image"
          id="camera_img" src="<?= $_REQUEST["itemImage"]?>"></td>
        <td>Quantity <?= $_REQUEST["itemQuantity"]?> <br> <br> <?= $_REQUEST["itemName"]?>
          &nbsp;&nbsp;&nbsp;
        </td>
        <td><br>
        <br> $<?= $_REQUEST["itemPrice"]?></td>
      </table>
      <table class="cart-summary">
        <tr>
          <td class="cart-summary-left">Estimated Shipping</td>
          <td class="cart-summary-right">$<?=$Config["shipping_rate"]*$_REQUEST["itemPrice"]?></td>
        </tr>
        <tr>
          <td class="cart-summary-left">Tax (CA)</td>
          <td class="cart-summary-right">$<?=$Config["tax_rate"]*$_REQUEST["itemPrice"]?></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td class="cart-summary-left"><b>Total</b></td>
          <td class="cart-summary-right"><b>$<?= $_REQUEST["itemPrice"]*(1+$Config["shipping_rate"]+$Config["tax_rate"]) ?></b></td>
        </tr>
      </table>
    </div>
    <hr />
    <div class="central-button">
      <div id="buybutton" data-enhance="false">
        <div id="gWalletDiv">
          <g:wallet jwt=<?= $masked_wallet_request_jwt ?> success="successMaskedWallet"
            failure="failureMaskedWallet"></g:wallet>
        </div>
      </div>
      <div class="order-detail-buttons">
        <button id="continue_checkout" data-theme="a"
          data-corners="false" data-mini="true" class="confirm-button">
          Continue Checkout</button>
      </div>
    </div>
    <!-- Form used to finish the purchase flow by posting maskedWallet Response to the server -->
    <form id="purchaseDetailsForm" name="purchaseDetailsForm"
      action="/xyz-php/confirm.php" method="post">
      <input type="hidden" name="maskedWallet" id="maskedWallet">
	    <input type="hidden" name="mwr" id="mwr" value= "<?= $masked_wallet_request_jwt ?>">
      <input type="hidden" name="changeJwt" id="changeJwt" value= "<?= $masked_wallet_request_jwt ?>"> <input
        type="hidden" name="gid" id="gid"> <input type="hidden"
        name="description" id="description" value="<?= $_REQUEST["itemDescription"]?>">
      <input type="hidden" name="quantity" id="quantity"
        value="<?= $_REQUEST["itemQuantity"]?>"> <input type="hidden"
        name="unitPrice" id="unitPrice" value="<?= $_REQUEST["itemPrice"]?>">
    </form>
  </div>
</body>
</html>


