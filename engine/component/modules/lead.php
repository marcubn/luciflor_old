<?php 
include_once LIB_DIR."site/module.php";

/**
 * 
 * Newsletter subscribing module
 * 
 */
class mod_lead extends site_module{

    function process(){
    $db=SDatabase::getInstance();   
    $we_call = getFromRequest($_POST, "we_call");

	if ("you"==$we_call)
	{
		//printr($_POST);exit;
		include_once(LIB_DIR."utile/validator.php");
		
		$v = new Validator($_POST);
		$v->setErrorMessage('email', 'Enter an email address!');
		$v->setErrorMessage('nume', 'Enter a name!');
		$v->setErrorMessage('phone', 'Enter a phone number!');
		$v->setErrorMessage('prenume', 'Enter a surname!');
		$v->setErrorMessage('city', 'Enter a city!');

		$v->filledIn('email');
        $v->filledIn('nume');
        $v->filledIn('prenume');
        $v->filledIn('phone');
        $v->filledIn('city');
			
		if (!preg_match('/^[^0-9][a-zA-Z0-9_]*([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST['email']))
		{
			$v->pushError("email", "Adresa de mail ".$_POST['email']." este invalida!");
		}
		
		
		if (!$v->isValid())
		{
			$jsErrors="";
			foreach ($v->getErrors() as $k => $error)
			{
				$jsErrors .= $error."<br/>";
			}
			
			systemMessage::addMessage($jsErrors,2);
			redirect($_SERVER["HTTP_REFERER"]);
		}
		else
		{
			$q="INSERT INTO
					leads
				SET 
					lead_time = NOW(),
					lead_first_name = ".$db->quote($_POST['nume']).",
					lead_last_name = ".$db->quote($_POST['prenume']).",
					lead_mobile = ".$db->quote($_POST['phone']).",
					lead_email = ".$db->quote($_POST['email']).",
					lead_city = ".$db->quote($_POST['city'])."
			";
			$db->query($q);
				
			
			systemMessage::addMessage("Thank you for the message!");
            redirect($_SERVER["HTTP_REFERER"]);
		}
	}
        
    }
    
    function display(){
		$doc = SDocument::getInstance();
        //$doc->includeJS('/js/leads.js');
    }
}

?>