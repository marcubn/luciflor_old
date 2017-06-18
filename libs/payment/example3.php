<?php
	/*
	 * example3.php
	 * ==========================================================================
	 * +------------------------------------------------------------------------+
	 * | This file is provided as an example for you. You CAN modify it and	use	|
	 * | this content to build your Live Update string. In this example we		|
	 * | assume that you set all required fields and some optional fields.		|
	 * +------------------------------------------------------------------------+
	 * ==========================================================================
	 */

	
	require_once("LiveUpdate.class.php");						//merghe the class file

	/*
	 * ==========================================================================
	 * Call Live Update class constructor with YOUR SECRET KEY as a parameter.
	 * This is for security issues. This is the only required parameter. For more
	 * Details please see the documentation (comments) in the class definition.
	 * ==========================================================================
	 */
	$myKey 			= "wHTeaWFU2VXB5UFAPe";						//set your secret key
	$myLiveUpdate 	= new LiveUpdate($myKey);					//instantiate the class	
	
	/*
	 * ==========================================================================
	 * MERCHANT - set merchant and check for errors
	 * ==========================================================================
	 */
	$myId			= "ACC_CODE";
	$myLiveUpdate->setMerchant($myId);

	/*
	 * ==========================================================================
	 * ORDER REFERENCE NUMBER - set order refference and check for errors
	 * ==========================================================================
	 */
	$myOrderRef	= "TEST-1";
	$myLiveUpdate->setOrderRef($myOrderRef);
	
	/*
	 * ==========================================================================
	 * ORDER DATE - set order date and check for errors
	 * ==========================================================================
	 */
	$myOrderDate = "2008-08-04 10:30:30";
	$myLiveUpdate->setOrderDate($myOrderDate);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCTS NAME - set the name for each product (we have 3 products)
	 * ==========================================================================
	 */
	$PName		= array();										//products name array
	$PName[]	= 'Produs general';									//first name
	$PName[]	= 'Joc';									//second name
	$PName[]	= 'Produs oarecare';									//third name
	$myLiveUpdate->setOrderPName($PName);
	
	
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCTS GROUPS - set the GROUPS for products
	 * ==========================================================================
	 */
	$PGroup		= array();										//products name array
	$PGroup[]	= '656';									//first name
	$PGroup[]	= '684';									//second name
	$PGroup[]	= '684';									//third name
	$myLiveUpdate->setOrderPGroup($PGroup);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT CODES - set product codes and check for errors
	 * ==========================================================================
	 */
	$PCode		= array();										//products code array
	$PCode[]	= 'zaqtsx_code_1';
	$PCode[]	= 'zaqtsx_code_2';
	$PCode[]	= 'zaqtsx_code_3';
	$myLiveUpdate->setOrderPCode($PCode);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT ADDITIONAL INFO - set product additional information and
	 * check for orders. 
	 * THIS IS AN OPTIONAL FILED
	 * ==========================================================================
	 */
	 $PInfo		= array();
	 $PInfo[]	= 'info_1';
	 $PInfo[]	= 'info_2';
	 $PInfo[]	= 'info_3';
	 $myLiveUpdate->setOrderPInfo($PInfo);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT PRICES - set prices for each product and check for errors
	 * ==========================================================================
	 */
	$PPrice		= array();										//products price array
	$PPrice[]	= 100;
	$PPrice[]	= 200;
	$PPrice[]	= 300;
	$myLiveUpdate->setOrderPrice($PPrice);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT PRICE TYPES - set price type (NET or GROSS) for each product and check for errors
	 * ==========================================================================
	 */
	$PPriceType		= array();									//products price types (optional)
	$PPriceType[]	= 'NET';
	$PPriceType[]	= 'GROSS';
	$PPriceType[]	= 'NET';
	$myLiveUpdate->setOrderPType($PPriceType);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT QTY - set quantity for each product and check for errors
	 * ==========================================================================
	 */
	$PQTY		= array();										//products qty array
	$PQTY[]		= 1;
	$PQTY[]		= 2;
	$PQTY[]		= 3;
	$myLiveUpdate->setOrderQTY($PQTY);
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT VAT - set VAT for each product and check for errors
	 * ==========================================================================
	 */
	$PVAT		= array();										//products vat array
	$PVAT[]		= 19;
	$PVAT[]		= 19;
	$PVAT[]		= 19;
	$myLiveUpdate->setOrderVAT($PVAT);
	
	/*
	 * ==========================================================================
	 * ORDER SHIPPING
	 * If you don't sent order shipping cost, PayU system will calculate the 
	 * shipping price. To perform this, set the order shipping to any order less 
	 * than 0.
	 *
	 * When ordershipping is 0, you can set it to 0 or not. By default, the order
	 * shipping is 0. This means that this cost will be assumed.
	 *
	 * In any other case just set the shipping with a positive value.
	 * ==========================================================================
	 */
	
	/*
	 * EXAMPLE - order shipping is not sent 
	 *
	 *	$PShipping = -1;
	 *	$myLiveUpdate->setOrderShipping($PShipping);
	 */
	 
	//$PShipping = 0.145;
	//$myLiveUpdate->setOrderShipping($PShipping);
	$PShipping = 0;
	$myLiveUpdate->setOrderShipping($PShipping);
	
	
	/*
	 * ==========================================================================
	 * ORDER PRODUCT VER - set products version
	 * THIS IS AN OPTIONAL FIELD
	 * ==========================================================================
	 */
	$PVer	= array();
	$PVer[]	= 'ver 1';
	$PVer[]	= 2;
	$PVer[]	= 'ver 1.2';
	$myLiveUpdate->setOrderVer($PVer);
	
	/*
	 * ==========================================================================
	 * ORDER CURRENCY
	 * ==========================================================================
	 */
	 $PCurrency = "RON";
	 $myLiveUpdate->setPricesCurrency($PCurrency);
	 
	/*
	 * ==========================================================================
	 * ORDER DESTINATION CITY
	 * ==========================================================================
	 */
	$PDestinationCity = "Iasi";
	$myLiveUpdate->setDestinationCity($PDestinationCity);
	
	/*
	 * ==========================================================================
	 * ORDER DESTINATION STATE
	 * ==========================================================================
	 */
	$PDestinationState = "Iasi";
	$myLiveUpdate->setDestinationState($PDestinationState);
	
	/*
	 * ==========================================================================
	 * ORDER DESTINATION COUNTRY CODE
	 * ==========================================================================
	 */
	$PDestinantionCountryCode = 'RO';
	$myLiveUpdate->setDestinationCountry($PDestinantionCountryCode);
	
	/*
	 * ==========================================================================
	 * ORDER PAY METHOD
	 * ==========================================================================
	 */
	$PPayMethod = 'CCVISAMC';
	$myLiveUpdate->setPayMethod($PPayMethod);	
	
	/*
	 * ==========================================================================
	 * If you want to sent in the live update form the billing information, you 
	 * will have to build an array with the following keys, respecting the 
	 * order and the names. Even for an emtpy key, put it into array and set it
	 * to a blank string. ('');
	 * ==========================================================================
	 */
	$billing = array(
		"billFName"				=> 'John',
		"billLName"				=> 'Doe',
		"billCISerial"			=> 'EP',
		"billCINumber"			=> '123456',
		"billCIIssuer"			=> '',
		"billCNP"				=> '',
		"billCompany"			=> '',
		"billFiscalCode" 		=> '',
		"billRegNumber" 		=> '',
		"billBank" 				=> '',
		"billBankAccount" 		=> '',
		"billEmail" 			=> 'john@doe.com',
		"billPhone" 			=> '0243236298',
		"billFax" 				=> '0243236298',
		"billAddress1"			=> 'address 1',
		"billAddress2"			=> 'address 2',
		"billZipCode"			=> '030301',
		"billCity"				=> 'Iasi',
		"billState"				=> 'Iasi',
		"billCountryCode"		=> 'RO'			
	);
	$myLiveUpdate->setBilling($billing);
	
	/*
	 * ==========================================================================
	 * I you want to sent in the live update form delivery informatino, you will
	 * have to build an array with the following keys, respecting the order and 
	 * the names. For empty values, put the key in the array and fill the value 
	 * with a blank string. ('');
	 * ==========================================================================
	 */
	$delivery = array(
		"deliveryFName"			=> 'John',
		"deliveryLName"			=> 'Doe',
		"deliveryCompany"		=> 'Example. INC',
		"deliveryPhone"			=> '02',
		"deliveryAddress1"		=> 'address 1',
		"deliveryAddress2"		=> 'address 2',
		"deliveryZipCode"		=> '33556',
		"deliveryCity"			=> 'Bucuresti',
		"deliveryState"			=> 'Ilfov',
		"deliveryCountryCode"	=> 'EN'
	);
	$myLiveUpdate->setDelivery($delivery);
	
	/*
	 * ==========================================================================
	 * SET ORDER LANGUAGE if you don't set the language to EN or RO, which are the
	 * only possible values, this method call will set the language to the default
	 * system language.
	 * ==========================================================================
	 */
	$PLanguage = 'ro';
	$myLiveUpdate->setLanguage($PLanguage);
	
	/*
	 * ==========================================================================
	 * TEST MODE
	 * If you are in testing, call this method and the class will be in test mode.
	 * The only valid information that you have to provide is the merchant key,
	 * merchant id, and HMAC Hash.
	 * ==========================================================================
	 */
	$myLiveUpdate->setTestMode(true);
	
	/* 
	 * ==========================================================================
	 * IN THIS SECTION WE HANDLE ERRORS
	 * If we have errors we won't output any html, but use the output to debug 
	 * the script.
	 * ==========================================================================
	 */
	
	?>
	<form name="frmForm" action="https://secure.payu.ro/order/lu.php" method="post">
	<?php echo $myLiveUpdate->getLiveUpdateHTML();	?>
	<input type="submit">
	</form>