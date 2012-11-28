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
	window.onload = initItemClickEvent;
</script>
</head>
<body>
  <!-- ITEM DETAILS PAGE -->
  <div data-role="page" id="camera-detail" data-theme="c">
    <div data-role="header" data-theme="b" id="header">
      <div>
        <span>XYZ, Inc.</span>
      </div>
    </div>
    <div data-role="header" data-theme="y" id="header2">
      <div class="left-header">
        <span>Camera</span>
      </div>
    </div>
    <div data-role="content" id="camera-content">
      <p class="title-name"><?= $_REQUEST["itemName"]?></p>

      <table class="item-display">
        <tr>
          <td><img class="display-image" id="camera_img"
            src="<?= $_REQUEST["itemImage"]?>">
          <td><span class="item-display">Quantity</span><select
            data-inline="true" data-corners="false">
              <option value="standard"><?= $_REQUEST["itemQuantity"]?></option>
          </select> </span><br> <span class="item-display">$<?= $_REQUEST["itemPrice"]?></span>
      </table>

    </div>
    <div class="bottom-button">
      <button id="add_to_cart" data-theme="a" data-corners="false">
        Add to cart</button>
    </div>
    <div data-role="content" id="camera-content2">
      <p class="title-name">Product highlights</p>
      <ul id="item_details" class="item-details">
        <li id="camera_desc"> <?= $_REQUEST["itemDescription"]?></li>
      </ul>
      <hr />
    </div>
    <form id="generateOrderForm" name="generateOrderForm"
      action="/xyz-php/mwr.php" method="post">
      <input type="hidden" name="itemImage" id="itemImage"
        value=<?= $_REQUEST["itemImage"]?>> <input type="hidden" name="itemName"
        id="itemName" value=<?= $_REQUEST["itemName"]?>> <input type="hidden"
        name="itemPrice" id="itemPrice" value=<?= $_REQUEST["itemPrice"]?>> <input
        type="hidden" name="itemQuantity" id="itemQuantity"
        value=<?= $_REQUEST["itemQuantity"]?>><input type="hidden"
        name="itemDescription" id="itemDescription"
        value=<?= $_REQUEST["itemDescription"]?>>
    </form>
  </div>
</body>
</html>