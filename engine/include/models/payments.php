<?php
require_once(ROOT_DIR.PUBLIC_FOLDER."app_config.php");

/**
 * 
 * Front Cart Model
 *
 * @author: Bogdan Marcu
 * @package De completat aici
 * @subpackage shop
 * 
 * */
class SPaymentForm
{
    /** Payment Inner Form HTML Content */
    public $form_data       = ""; 
    /** Payment Form Attributes */
    public $form_attributes = array(); 
    
    /** Processor */
    public $type    = "";
    
    private $order_id   = null;
    private $order_data = array();
    private $amount     = null;
    
    /** Parameter for Payment OK Return Page */
    public $param_return_ok_url    = null;
    /** Parameter for Payment Failed Return Page */
    public $param_return_error_url = null;
    /** Parameter for Payment IPN Script */
    public $param_ipn_url          = null;

	/**
	 * SPaymentForm::renderForm()
	 * 
	 * @param $order_id
	 * @param $amount_type
	 * @param $type
	 * @return boolean.
	 */
	function renderForm($order_id, $amount_type, $type)
	{
		$this->order_id   = $order_id;
		$this->order_data = SPaymentForm::fetchOrder($order_id);
		$this->amount     = SPaymentForm::setAmount($amount_type, $this->order_data);
        $this->amount_type    = $amount_type;
        switch($type){
            
            case 0:{
                $this->type = "epayment";
                $result = SPaymentForm::renderEpaymentForm();
                break;
            }
            case 1:{
                $this->type = "mobilpay";
                $result = SPaymentForm::renderMobilPayForm();
                break;
            }
            default:{
                break;
            }
        }
        
        return $result;
    
    }
    
    /**
     * 
     * @todo: in viitor.. FetchOrder from OrderModel
     * 
     */
    function fetchOrder($id_comanda)
    {
    	

        $persoana = array();
        if(TESTING==1)
        	$comanda=_sqlGetRowContent("comenzi_copy", "comanda_id", $id_comanda);
        else 	
        	$comanda=_sqlGetRowContent("comenzi", "comanda_id", $id_comanda);
        
        $facturare = SOrder::getFacturare($comanda["comanda_facturare_id"]);

        if ($facturare["tip"] == "pf") 
        {
			$persoana['nume'] = $facturare['nume'];
			$persoana['telefon'] = $facturare['telefon'];
			$persoana['email'] = $facturare['email'];
            $persoana['adresa'] = $facturare['adresa'];
            $persoana['localitate'] = $facturare['oras'];
            $persoana['judet'] = $facturare['judet'];
            $persoana['cnp'] = $facturare['cnp'];
            $persoana['cod_postal'] = $facturare['cod_postal'];
            $persoana['pret_total'] = $comanda['comanda_pret_total'];
            $persoana['avans'] = $comanda['comanda_avans'];
            $persoana['ramburs'] = $comanda['comanda_ramburs'];
        } 
        
        else 
        {
            $persoana['nume'] = $facturare['nume_companie'];
			$persoana['telefon'] = $facturare['telefon'];
			$persoana['email'] = $facturare['email'];
            $persoana['adresa'] = $facturare['adresa'];
            $persoana['localitate'] = $facturare['oras'];
            $persoana['judet'] = $facturare['judet'];
            $persoana['cui'] = $facturare['cui'];
            $persoana['cod_postal'] = $facturare['cod_postal'];
            $persoana['pret_total'] = $comanda['comanda_pret_total'];
            $persoana['avans'] = $comanda['comanda_avans'];
            $persoana['ramburs'] = $comanda['comanda_ramburs'];
        }
        
        return $persoana;
    }
    
    /**
     * @todo nu se poate plati diferenta
     * */
    function setAmount($amount_type,$comanda)
    {
    	$value = "";
    	if($amount_type==1)		//daca este selectat pret total
    		$value=$comanda['pret_total'];
    		
    	elseif($amount_type==0)		//daca este selectat avans
    		$value = $comanda['avans'];
        
        elseif($amount_type==2)     //daca este selectat ramburs
            $value = $comanda['ramburs'];
		
    	return $value;
    }
    
    /**
     * SPaymentForm::renderEpaymentForm()
     * 
     * @todo @return boolean
     */
    function renderEpaymentForm()
    {
        require_once (LIB_DIR . '/epayment/LiveUpdate.class.php');
    	
    	$ePayment['key'] = SPAY_EPAYMENT_KEY;
        $ePaymentLiveUpdate = new LiveUpdate($ePayment['key']);

        $ePayment['merchantID'] = SPAY_EPAYMENT_MERCHANT_ID;
        $ePaymentLiveUpdate->setMerchant($ePayment['merchantID']);

        $ePayment['orderDate'] = date('Y-m-d H:i:s');
        $ePaymentLiveUpdate->setOrderDate($ePayment['orderDate']);

        $ePaymentLiveUpdate->setOrderRef($this->order_id);

        $ePayment['productNames'] = array();
        $ePayment['productCodes'] = array();
        $ePayment['productPrices'] = array();
        $ePayment['productQuantities'] = array();
        $ePayment['productVAT'] = array();

        $i = 0;
        $valoare=$this->amount;

        $tva = number_format($valoare / TVA_VALUE * 0.24, 2);
        $pret = $valoare - $tva;

        $ePayment['productNames'][$i] = "Plata factura KEI 00" . $this->order_id;
        $ePayment['productCodes'][$i] = $this->order_id;
        $ePayment['productPrices'][$i] = $pret;
        $ePayment['productQuantities'][$i] = 1;
        $ePayment['productVAT'][$i] = "24";

        $ePaymentLiveUpdate->setOrderPName($ePayment['productNames']);
        $ePaymentLiveUpdate->setOrderPCode($ePayment['productCodes']);
        $ePaymentLiveUpdate->setOrderPrice($ePayment['productPrices']);
        $ePaymentLiveUpdate->setOrderQTY($ePayment['productQuantities']);
        $ePaymentLiveUpdate->setOrderVAT($ePayment['productVAT']);

        $ePaymentLiveUpdate->setOrderShipping(0);
        $ePaymentLiveUpdate->setPricesCurrency("RON");

        //===> date de facturare
        /**
         * @todo nu e corect sa ia din session. trebuie sa ia din db datele de facturare. De-asta se face FetchOrder
         * */
        $date=array();
        $comanda=$this->fetchOrder($this->order_id);
        $date['billPhone']=$comanda['telefon'];
        $date['billEmail']=$comanda['email'];
        $date['billFName']=$comanda['nume'];
        $date['billCNP']=$comanda['cnp'];
        $date['billAddress1']=$comanda['adresa'];
        $date['billCity']=$comanda['localitate'];
        $date['billState']=$comanda['judet'];
        //vd($comanda);
        //vd($date);
        //vd($_SESSION['cos']['billing']);exit;
        
        $ePaymentLiveUpdate->setBilling($date);
        //<===
		
        //===> date de livrare
        //$ePaymentLiveUpdate->setDelivery($delivery_info);
        //<===
		if(SPAY_SANDBOX==1)
        	$ePaymentLiveUpdate->setTestMode(true);

        $this->form_attributes["action"] = $ePaymentLiveUpdate->liveUpdateURL;
        $this->form_data                 = $ePaymentLiveUpdate->getLiveUpdateHTML();
        
        return true;
    }
    
    function renderMobilPayForm()    
    {	
    	
    	//require_once "../../../lib/Mobilpay/cardConfirm.php";
    	require_once LIB_DIR.'Mobilpay/Payment/Request/Abstract.php';
		require_once LIB_DIR.'Mobilpay/Payment/Request/Card.php';
		require_once LIB_DIR.'Mobilpay/Payment/Invoice.php';
		require_once LIB_DIR.'Mobilpay/Payment/Address.php';
		
		//for testing purposes, all payment requests will be sent to the sandbox server. Once your account will be active you must switch back to the live server https://secure.mobilpay.ro
		if(SPAY_SANDBOX==1)
			$paymentUrl = 'http://sandboxsecure.mobilpay.ro';
		elseif(SPAY_SANDBOX==0)
			$paymentUrl = 'https://secure.mobilpay.ro';
		
		// this is the path on your server to the public certificate. You may download this from Admin -> Conturi de comerciant -> Detalii -> Setari securitate
		$x509FilePath 	= ROOT_DIR.PUBLIC_FOLDER.'public.cer';
        //vd($this->amount_type);exit;
		try
		{
			srand((double) microtime() * 1000000);
			$objPmReqCard 						= new Mobilpay_Payment_Request_Card();
			#merchant account signature - generated by mobilpay.ro for every merchant account
			#semnatura contului de comerciant - mergi pe www.mobilpay.ro Admin -> Conturi de comerciant -> Detalii -> Setari securitate
			$objPmReqCard->signature 			= SPAY_MOBILPAY_SIGNATURE;
			#you should assign here the transaction ID registered by your application for this commercial operation
			#order_id should be unique for a merchant account
            if($this->amount_type==2)
                $this->order_id.="_r";
            //exit;
			$objPmReqCard->orderId 				= $this->order_id;
			#supply return_url and/or confirm_url only if you want to overwrite the ones configured for the service/product when it was created
			#if you don't want to supply a different return/confirm URL, just let it null
			
            /**
             * @todo astea doua trebuiesc preluate din $params_* si setate in afara clasei.
             * */ 
            $objPmReqCard->confirmUrl 			= $this->param_ipn_url; 
			$objPmReqCard->returnUrl 			= $this->param_return_ok_url; 
			
			#detalii cu privire la plata: moneda, suma, descrierea
			#payment details: currency, amount, description
			$objPmReqCard->invoice = new Mobilpay_Payment_Invoice();
			$objPmReqCard->invoice->currency	= 'RON';
			$objPmReqCard->invoice->amount		= $this->amount;
			$objPmReqCard->invoice->installments= '2,3';
			$objPmReqCard->invoice->details		= 'Plata factura 00'.$this->order_id;

			#detalii cu privire la adresa posesorului cardului
			#details on the cardholder address
			//vd($comanda);
			$billingAddress 				= new Mobilpay_Payment_Address();
			$billingAddress->type			= 'person';
			$billingAddress->firstName		= $this->order_data['nume'];
			//$billingAddress->lastName		= $_POST['billing_last_name'];
			//$billingAddress->fiscalNumber	= $_POST['billing_fiscal_number'];
			//$billingAddress->identityNumber	= $_POST['billing_identity_number'];
			//$billingAddress->country		= $_POST['billing_country'];
			$billingAddress->county			= $this->order_data['judet'];
			$billingAddress->city			= $this->order_data['localitate'];
			$billingAddress->zipCode		= $this->order_data['cod_postal'];
			$billingAddress->address		= $this->order_data['adresa'];
			$billingAddress->email			= $this->order_data['email'];
			$billingAddress->mobilePhone	= $this->order_data['telefon'];
			//$billingAddress->bank			= $_POST['billing_bank'];
			//$billingAddress->iban			= $_POST['billing_iban'];
			$objPmReqCard->invoice->setBillingAddress($billingAddress);	
			/*
			#detalii cu privire la adresa de livrare
			#details on the shipping address
			$shippingAddress 				= new Mobilpay_Payment_Address();
			$shippingAddress->type			= 'person';
			$shippingAddress->firstName		= $comanda['nume'];
			//$shippingAddress->lastName		= $_POST['shipping_last_name'];
			//$shippingAddress->fiscalNumber	= $_POST['shipping_fiscal_number'];
			//$shippingAddress->identityNumber= $_POST['shipping_identity_number'];
			//$shippingAddress->country		= $_POST['shipping_country'];
			$shippingAddress->county		= $comanda['judet'];
			$shippingAddress->city			= $comanda['localitate'];
			$shippingAddress->zipCode		= $comanda['cod_postal'];
			$shippingAddress->address		= $comanda['adresa'];
			$shippingAddress->email			= $comanda['email'];
			$shippingAddress->mobilePhone	= $comanda['telefon'];
			//$shippingAddress->bank			= $_POST['shipping_bank'];
			//$shippingAddress->iban			= $_POST['shipping_iban'];
			$objPmReqCard->invoice->setShippingAddress($shippingAddress);
			*/
			$objPmReqCard->encrypt($x509FilePath);
		}
		catch(Exception $e)
		{
            $err = var_export($e,1);
            mail("marcu.bogdannicolae@gmail.com","Eroare Mobilpay",$err);
            systemMessage::addMessage("S-a produs o eroare la plata online! Ne cerem scuze!");
            redirect(ROOT_SHOP_HOST);
		}
		
		if(!isset($e) || !($e instanceof Exception)){
            
            $this->form_attributes["action"] = $paymentUrl;
            $this->form_data = '<input type="hidden" name="env_key" value="'.$objPmReqCard->getEnvKey().'">';
	        $this->form_data .= '<input type="hidden" name="data" value="'.$objPmReqCard->getEncData().'">';
		}
		else 
		{
			$e->getMessage();
            return false;
		}
		
		return true;
    }

        
}    
?>