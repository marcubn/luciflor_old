<?php
	/*
	 * example1.php
	 * 
	 * This file is provided as an example for the included Live Update class.
	 *
	 */

	require_once("LiveUpdate.class.php");

	$errOutput		= "";

	$myKey 			= "your password";						//set the secret key
	$myLiveUpdate 	= new LiveUpdate($myKey);				//instantiate the class

	$myId			= "MERCHANT";							//set your PayU merchant id
	$myLiveUpdate->setMerchant($myId);

	$myOrderDate = "2007-12-04 10:30:30";					//order date
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

	/*
	 * ==========================================================================
	 * ORDER SHIPPING - set to -1 in order to use the shipping calculation from PayU
	 * ==========================================================================
	 */
	$myLiveUpdate->setOrderShipping(-1);

	?>
	<form name="frmForm" action="<?php $myLiveUpdate->liveUpdateURL; ?>" method="post">
	<?php echo $myLiveUpdate->getLiveUpdateHTML(); 	?>
	<input type="submit">
	</form>