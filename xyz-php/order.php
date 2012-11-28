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
          id="camera_img" src="$itemImage"></td>
        <td>Quantity $itemQuantity <br> <br> $itemName
          &nbsp;&nbsp;&nbsp;
        </td>
        <td class="cart-summary-right"><br>
        <br> $$itemPrice</td>
      </table>
      <table class="cart-summary">
        <tr>
          <td class="cart-summary-left">Estimated Shipping</td>
          <td class="cart-summary-right">$9.99</td>
        </tr>
        <tr>
          <td class="cart-summary-left">Tax (CA)</td>
          <td class="cart-summary-right">$8.00</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td class="cart-summary-left"><b>Total</b></td>
          <td class="cart-summary-right"><b>$$totalPrice</b></td>
        </tr>
      </table>
    </div>
    <hr />
    <div class="central-button">
      <div id="buybutton" data-enhance="false">
        <div id="gWalletDiv">
          <g:wallet jwt=$mwr success="successMaskedWallet"
            failure="failureMaskedWallet"></g:wallet>
        </div>
      </div>
      <div class="order-detail-buttons">
        <button id="continue_checkout" data-theme="a"
          data-corners="false" data-mini="true" class="confirm-button">
          Continue Checkout</button>
      </div>
    </div>
    <!-- Form used to finish the purchase flow by posting makedWallet Response to the server -->
    <form id="purchaseDetailsForm" name="purchaseDetailsForm"
      action="/xyz-php/confirm.php" method="post">
      <input type="hidden" name="maskedWallet" id="maskedWallet">
      <input type="hidden" name="changeJwt" id="changeJwt"> <input
        type="hidden" name="gid" id="gid"> <input type="hidden"
        name="description" id="description" value="$itemDescription">
      <input type="hidden" name="quantity" id="quantity"
        value="$itemQuantity"> <input type="hidden"
        name="unitprice" id="unitprice" value=$itemPrice>
    </form>
  </div>
</body>
</html>