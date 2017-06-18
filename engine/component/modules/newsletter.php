<?php 
include_once LIB_DIR."site/module.php";

/**
 * 
 * Newsletter subscribing module
 * 
 */
class mod_newsletter extends site_module{

    function process(){
    $db=SDatabase::getInstance();   
    $act = getFromRequest($_POST, "act");
	if ("newsletter_send"==$act)
	{
		include_once(LIB_DIR."utile/validator.php");
		
		$v = new Validator($_POST);
		$v->setErrorMessage('email_address', 'Introduceti adresa de email!');

		$v->filledIn('email_address');
        //$v->filledIn('name');
			
		if (!preg_match('/^[^0-9][a-zA-Z0-9_]*([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST['email_address']))
		{
			$v->pushError("email_address", "Adresa de mail ".$_POST['email_address']." este invalida!");
		}
		
        $q="SELECT * from newsletter WHERE newsletter_email = '{$_POST['email_adress']}'";
        $db->setQuery($q);
        $intermed = $db->loadAssoc();
		if($intermed)
		{
			$v->pushError("news_email", "Adresa de mail ".$_POST['email_address']." a fost deja inscrisa!");
		}
		
		if (!$v->isValid())
		{
			$jsErrors="";
			foreach ($v->getErrors() as $k => $error)
			{
				$jsErrors .= $error."<br/>";
			}
			
			systemMessage::addMessage($jsErrors,2);
		}
		else
		{
            $q="SELECT * from newsletter WHERE newsletter_email = '{$_POST['email_adress']}'";
            $db->setQuery($q);
            $intermed = $db->loadAssoc();
			if (!$intermed)
			{
				$q="INSERT INTO
						newsletter
					SET 
						email = '".$db->getEscaped($_POST['email_address'])."',
						status = 1,
						ip = '".getIP()."',
						date = NOW() 
				";
				$db->query($q);
				
			}
			else
			{
				$q="UPDATE
						newsletter
					SET 
						status = 1,
						ip = '".  getIP()."'
					WHERE 
						email = '".$db->getEscaped($_POST['email_address'])."' 
				";
				$db->query($q);
				
				//$member_id=_sqlGetFieldContent("newsletter_member", "id", "email", _sqlEscValue($_POST['email_address']));
			}
			
			systemMessage::addMessage("Multumim! Adresa ta de e-mail a fost inregistrata cu succes.");
            redirect($_SERVER["HTTP_REFERER"]);
		}
	}
        
    }
    
}

?>