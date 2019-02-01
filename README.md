# Braintree AIM - Craft Commerce 2 Gateway

## Installing the Gateway

This gateway utilizes the thephpleague/omnipay-braintreenet Braintree driver, and will update the Braintree AIM gateway in Craft Commerce 1.

#### Requirements
- Craft 3 (or later)
- Craft Commerce 2

This gateway is a commercial plugin for Craft 3 and can be installed using the Craft plugin store, or by updating the composer.json file to require this gateway.

```composer require digital-pros/commerce-braintree ```

## Using the Gateway

After installing the gateway, the default form fields will submit a transaction to Braintree. 

***The examples below have been simplified for illustration purposes.***
	
	<label>Card Holder</label>
	<input name="firstName" placeholder="First Name" required="required" type="text">
	<input name="lastName" placeholder="Last Name" required="required" type="text">

	<label>Card Number</label>
	<input id="number" name="number" placeholder="Card Number" required="required" type="text">

	<label>Card Expiration Date</label>
	<select id="month" name="month"><option value="12">12</option>...</select>
	<select id="year" name="year"><option value="2019">2019</option>...</select>

	<label>CVV/CVV2</label>
	<input id="cvv" name="cvv" placeholder="CVV" required="required" type="text">
	
	{{ cart.gateway.getPaymentFormHtml({})|raw }}

	<button id="submit" name="submit">Pay Now</button>

## Accept.js

If Accept.js is enabled in the plugin settings, the gateway will require two hidden fields (**token** and **tokenDescriptor**), which are sent back to Braintree after the card is validated. Accept.js also requires an adjustment to the submit button so that the payment form only submits after the card has been validated by Accept.js.

    <label>Card Holder</label>
	<input name="firstName" placeholder="First Name" required="required" type="text">
	<input name="lastName" placeholder="Last Name" required="required" type="text">

	<label>Card Number</label>
	<input id="number" name="number" placeholder="Card Number" required="required" type="text">

	<label>Card Expiration Date</label>
	<select id="month" name="month"><option value="12">12</option>...</select>
	<select id="year" name="year"><option value="2019">2019</option>...</select>

	<label>CVV/CVV2</label>
	<input id="cvv" name="cvv" placeholder="CVV" required="required" type="text">
	
	<!-- Required fields and changes for Braintree Accept.js -->
	
	<input id="token" name="token" type="hidden">
	<input id="tokenDescriptor" name="tokenDescriptor" type="hidden"> 
	
	{{ cart.gateway.getPaymentFormHtml({})|raw }}

	<button id="braintreeSubmit" name="braintreeSubmit" onclick="event.preventDefault(); sendPaymentDataToAnet();">Pay Now</button>
	
## Returns and Refunds

This gateway supports partial refunds and full refunds after the transaction has successfully settled in Braintree. In the plugin settings, there's an otpion to void the transaction if a refund fails, but the transaction will be voided entirely if the refund fails.

## Subscriptions and Saved Payment Methods

This gateway does not currently support subscriptions or saved payment methods.

## Support

Questions? Feel free to open an issue.
