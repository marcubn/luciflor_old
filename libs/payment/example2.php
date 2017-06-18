<?php
	/*
	 * example2.php
	 * 
	 * This file is provided as an example for the included Live Update class.
	 *
	 */


	require_once("LiveUpdate.class.php");

	$myKey 			= "your password";							//set the secret key
	$myLiveUpdate 	= new LiveUpdate($myKey);					//instantiate the class

	$myId			= "MERCHANT";
	$myLiveUpdate->setMerchant($myId);

	$myOrderRef	= "TEST-1";
	$myLiveUpdate->setOrderRef($myOrderRef);

	$myOrderDate = "2005-09-10 10:30:30";
	$myLiveUpdate->setOrderDate($myOrderDate);

	$PName		= array();										//products name array
	$PName[]	= 'produs_1';									//first product name
	$PName[]	= 'produs_2';									//second product name
	$PName[]	= 'produs_3';									//third product name
	$myLiveUpdate->setOrderPName($PName);

	$PCode		= array();										//products code array
	$PCode[]	= 'code_1';
	$PCode[]	= 'code_2';
	$PCode[]	= 'code_3';
	$myLiveUpdate->setOrderPCode($PCode);

	$PInfo		= array();
	$PInfo[]	= 'info_1';
	$PInfo[]	= 'info_2';
	$PInfo[]	= 'info_3';
	$myLiveUpdate->setOrderPInfo($PInfo);

	$PPrice		= array();										//products price array
	$PPrice[]	= 100;
	$PPrice[]	= 200;
	$PPrice[]	= 300;
	$myLiveUpdate->setOrderPrice($PPrice);
	
	$PPriceType		= array();									//products price types (optional)
	$PPriceType[]	= 'NET';
	$PPriceType[]	= 'GROSS';
	$PPriceType[]	= 'NET';
	$myLiveUpdate->setOrderPType($PPriceType);

	$PQTY		= array();										//products qty array
	$PQTY[]		= 1;
	$PQTY[]		= 2;
	$PQTY[]		= 3;
	$myLiveUpdate->setOrderQTY($PQTY);

	$PVAT		= array();										//products vat array
	$PVAT[]		= 19;
	$PVAT[]		= 19;
	$PVAT[]		= 19;
	$myLiveUpdate->setOrderVAT($PVAT);

	//$PShipping = 0.145;
	//$myLiveUpdate->setOrderShipping($PShipping);
	$PShipping = 0;
	$myLiveUpdate->setOrderShipping($PShipping);

	$PVer	= array();
	$PVer[]	= 'ver 1';
	$PVer[]	= 2;
	$PVer[]	= 'ver 1.2';
	$myLiveUpdate->setOrderVer($PVer);

	 $PCurrency = "RON";
	 $myLiveUpdate->setPricesCurrency($PCurrency);

	$PDestinationCity = "Iasi";
	$myLiveUpdate->setDestinationCity($PDestinationCity);

	$PDestinationState = "Iasi";
	$myLiveUpdate->setDestinationState($PDestinationState);

	$PDestinantionCountryCode = 'RO';
	$myLiveUpdate->setDestinationCountry($PDestinantionCountryCode);

	$PPayMethod = 'CCVISAMC';
	$myLiveUpdate->setPayMethod($PPayMethod);

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

	$PLanguage = 'ro';
	$myLiveUpdate->setLanguage($PLanguage);

	$myLiveUpdate->setTestMode(true);

	?>
	<form name="frmForm" action="<?php $myLiveUpdate->liveUpdateURL?>" method="post">
	<?php echo $myLiveUpdate->getLiveUpdateHTML(); ?>
	<input type="submit">
	</form>