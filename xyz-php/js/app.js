/**
* Attaches click handlers to actionable HTML elements
*/
function initItemClickEvent() {
document.getElementById('add_to_cart').addEventListener('click',
function() {
	$.mobile.showPageLoadingMsg("a", "loading", false);
	document.forms["generateOrderForm"].submit();
	});
	}

/**
 * Continue Checkout button logic. Request Masked Wallet should be tied to your
 * continue checkout button. This allows you to get the maskedWalletRequest for
 * pre-authorized users with out any user interaction.
 * 
 * @param {String}
 *            oauthToken accessToken of preauthrized users
 */
function requestMaskedWallet(oauthToken) {
	google.wallet.online.requestMaskedWallet({
		jwt : $("#mwr").val(),
		success : successMaskedWallet,
		failure : failureMaskedWallet,
		token : oauthToken
	});
};

/**
 * Check if Wallet is a preauthed method of payment. If Wallet is preauthed
 * store the access token associated for future use. <code>authorize()</code>
 * automatically sets the Wallet access token so we don't need to do that here
 */
function checkAuth() {
	google.wallet.online
			.authorize({
				"clientId" : "765560632621-8pf9sqbicgb8citielj6tnh8n46i7i7s.apps.googleusercontent.com",
				"callback" : function(authorizeResponse) {
					var checkoutButton = document
							.getElementById('continue_checkout');
					// Assign different event to the button based off the
					// authorize result.
					if (authorizeResponse) {
						// Set the accessToken to enable the pre-authorized
						// purchase flow.
						google.wallet.online
								.setAccessToken(authorizeResponse.access_token);
						checkoutButton
								.addEventListener(
										'click',
										function() {
											$.mobile.showPageLoadingMsg(
													"a", "loading", false);
											requestMaskedWallet(authorizeResponse.access_token);
										});
					} else {
						checkoutButton
								.addEventListener(
										'click',
										function() {
											window
													.alert("Merchant's payment-card entry screen goes here.");
										});
					}
				}
			});
	// Attaches click handlers to show spinner for the Wallet Button
	document.getElementById("gWalletDiv").childNodes[1].addEventListener(
			"click", function() {
				$.mobile.showPageLoadingMsg("a", "loading", false);
			});
};
/**
 * Masked Wallet request success handler. This function handles success
 * maskedWallt response and post the informaiton to the server.
 * 
 * @param {object}
 *            param The MaskedWalletResponse
 */
function successMaskedWallet(param) {
	console.log("success");
	console.log(param.jwt);
	$("#gid").val(param.response.response.googleTransactionId);
	$("#maskedWallet").val(param.jwt);
	document.forms["purchaseDetailsForm"].submit();
};

/**
 * Masked Wallet Request failure handler. You should implement your error
 * handling code here.
 * 
 * @param {Object}
 *            error ErrorResponse
 */
function failureMaskedWallet(param) {
	// Hide spinner
	$.mobile.hidePageLoadingMsg();
	console.log("false");
};

/**
 * NotifyTransactionStatus is used to notify Wallet of the final transaction
 * status. You need to call this function after you've processed the one time
 * card.
 */
function notifyTransactionStatus() {
	// notify Google Wallet
	function notifyWhenAvailable() {
		window.setTimeout(function() {
			if (google.wallet.online.notifyTransactionStatus) {
				google.wallet.online.notifyTransactionStatus({
					jwt : $("#notificationJwt").val()
				});
			} else {
				notifyWhenAvailable();
			}
		}, 1000);
	}

};

/**
 * Full Wallet Request requests the one time card number from Wallet. This is
 * called whent he customer confirms the purchase. Below we're using the wallet
 * request JWT generated from the server.
 */
function requestFullWallet() {
	google.wallet.online.requestFullWallet({
		"jwt" : $("#fwr").val(),
		"success" : fullWalletSuccess,
		"failure" : fullWalletFailure
	});
};
/**
 * Calls ChangeMaskedWallet using the change JWT generated from the server.This
 * allows pops up the choose to allow the user to edit their payment or shipping
 * selection.
 */
function changeMaskedWallet() {
	google.wallet.online.changeMaskedWallet({
		"jwt" : $("#changeJwt").val(),
		"success" : successChangedWallet,
		"failure" : failureChangedWallet,
	});
}

/**
 * Masked Wallet request success handler. This function handles success
 * maskedWallt response and post the informaiton to the server.
 * 
 * @param {object}
 *            param The MaskedWalletResponse
 */
function successChangedWallet(param) {
	console.log("success");
	$("#maskedWallet").val(param.jwt);
	$("#gid").val(param.response.response.googleTransactionId);
	document.forms["updateCredentialsForm"].submit();
};
/**
 * Masked Wallet Request failure handler. You should implement your error
 * handling code here.
 * 
 * @param {Object}
 *            error ErrorResponse
 */
function failureChangedWallet(param) {
	// Hide spinner
	$.mobile.hidePageLoadingMsg();
	JSON.stringify(params)
};
/**
 * Handles the Full Wallet Request success case. The parameter passed to this
 * callback contains the credit card number and Full Wallet Response object.
 * This information will be posted to the server to complete the purchase flow.
 * 
 * @param {Object}
 *            param Full Wallet Request object
 */
function fullWalletSuccess(param) {
	$("#fullWallet").val(param.jwt);
	console.log(param.jwt);
	document.forms["placeOrderForm"].submit();
};
/**
 * Full Wallet Request failure handler. You should implement your error handling
 * code here.
 * 
 * @param {Object}
 *            param Defines the code and details of why the request failed
 */
function fullWalletFailure(param) {
	// Hide spinner
	$.mobile.hidePageLoadingMsg();
	console.log("failure triggered");
};

/**
 * Attaches click handlers to actionable HTML elements
 */
function initOrderClickEvent() {
	document.getElementById('place_order').addEventListener('click',
			function() {
				$.mobile.showPageLoadingMsg("a", "loading", false);
				requestFullWallet();
			});
	document.getElementById('change_shipping').addEventListener('click',
			function() {
				changeMaskedWallet();
			});
	document.getElementById('change_billing').addEventListener('click',
			function() {
				changeMaskedWallet();
			});
}
