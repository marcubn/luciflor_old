<?php
/**
 * Utilitary for sending emails
 *
 */
class SMailNotify{
    
	public $labels = array(
		"POLICY_SERIES"    =>"Policy Series",
		"POLICY_NUMBER"    =>"Policy Number",
		"POLICY_START_DATE"=>"Policy Start Date",
		"POLICY_END_DATE"  =>"Policy End Date",
		"POLICY_PRICE"     =>"Policy Price",
			
		"POLICY_NAME"      =>"Policy holder name",
		"POLICY_SURNAME"   =>"Policy holder surname",
			
		"PRODUCT"		    => "Product type", 
			
		"NAME" 				=> "",
		"SURNAME"			=> "",
		"EMAIL"				=> "",
		"PASSWORD"			=> "",
		"LOGIN_DATA"		=> "",
			
		"LINK_ACTIVATE"		=> "",
		"LINK_SITE"			=> "",

		
				
	);
	
	/**
	 * 
	 * @var stdClass
	 */
	private $data = array();
	
	/**
	 * Database record of the email notification
	 * @var DBTable
	 */
	private $mail 		= null;
	/**
	 * The customer email
	 * @var string
	 */
	private $user_mail 	= null;
	
	/**
	 * Loads from database the $mail_type notification
	 * 
	 * @param string $mail_type
	 */
	function _loadMail($mail_type)
	{
		include_once LIB_DIR."site/app.php";
		$app = SApp::getInstance();
		$smarty = $app->getTemplate();
		$db = SDatabase::getInstance();
		
		$db->setQuery("select * from mails where mails_type = '{$mail_type}'");
		$mailTypeObject = $db->loadObject();
		
		if(!isset($mailTypeObject->mails_id))
		{
			return false;
		}
		
		if( $mailTypeObject->mails_status==0 )
		{
			return false;
		}
		
		$this->mail = $mailTypeObject;
		return true; 

	}
	
	function _getLabel($label){
		if(isset($this->data[$label]))
			return $this->data[$label];
		else
			return "";
	}
	
	function addData($key, $value)
	{
		$this->data[strtoupper($key)] = $value;
	}
	
	/**
	 * 
	 * @param int $policy_id
	 * @param string $policy_type
	 */
	function _loadData($policy_id, $policy_type){
		
		$this->policy_type = $policy_type;
		/**
		 * Initiate data as empty
		 */
		foreach($this->labels as $k => $p){
			if(!isset($this->data[$k]))
				$this->data[$k] = "";
		}
		
		$this->data["LINK_SITE"] = ROOT_HOST;
		$db = SDatabase::getInstance();
		
		switch ($policy_type) {
			case "rca":
				
				$policy 	 = $db->search("rca_polite","row", "polita_id={$policy_id}");
        		$rca 		 = $db->search("rca_data", "row", "rca_id='{$policy->polita_data_id}'");
        		$member 	 = $db->search("members", "row", "member_id={$rca->user}");
        		
        		$this->user_mail = $member->member_email;
        		
        		$db->setQuery("
        		SELECT
        			DATE_SUB(DATE_ADD(STR_TO_DATE(start_date,'%d.%m.%Y'),INTERVAL polita_period MONTH), INTERVAL 1 DAY ) as end_date 
        		FROM 
        			`rca_polite`,rca_data 
        		WHERE polita_data_id = rca_id
        			and polita_id = '{$policy_id}'  
        		");
        		$res = $db->loadObject();
        		
        		$end_date = $res->end_date;
        		
        		$this->data["POLICY_SERIES"] 	 = "RO/12/S5/KX";
        		$this->data["POLICY_NUMBER"]  	 = $policy->polita_code;
        		$this->data["POLICY_START_DATE"] = $rca->start_date;
        		$this->data["POLICY_END_DATE"] 	 = $end_date;
        		$this->data["POLICY_PRICE"] 	 = number_format($policy->polita_price, 2)." RON";
        		$this->data["NAME"] 		     =  $this->data["POLICY_NAME"] 		 = $policy->polita_asigurat_prenume;
        		$this->data["SURNAME"] 		     =  $this->data["POLICY_SURNAME"] 	 = $policy->polita_asigurat_nume;
        		
        		$this->files[$policy->polita_pdf] =   UPLOAD_DIR.DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR.$policy->polita_pdf;
        		
        		$this->ip = $policy->polita_ip;

			;
			break;
			
			case "travel":
            
        		$polita_Travel = STable::tableInit("travel_order","travel_id");
        		$polita_Travel->load($policy_id);
        		$member 	 = $db->search("members", "row", "member_id={$polita_Travel->travel_user}");
        		
        		$this->user_mail = $member->member_email;
        		
        		$this->data["POLICY_SERIES"] 	 = "AMO";
        		$this->data["POLICY_START_DATE"] = $polita_Travel->travel_start_date;
        		$this->data["POLICY_END_DATE"] 	 = $polita_Travel->travel_start_date;
        		$this->data["POLICY_PRICE"] 	 = number_format($polita_Travel->travel_price, 2)." RON";
        		$this->data["NAME"] 		     =  $this->data["POLICY_NAME"] 		 = $member->member_first_name;
        		$this->data["SURNAME"] 		     =  $this->data["POLICY_SURNAME"] 	 = $member->member_last_name;
        		
        		$db->setQuery("select * from travel_persons where person_order='{$policy_id}' and person_pdf !='' ");
        		$files = $db->loadObjectList();
        		$codes = array();
        		
        		if(count($files)>0)
        			foreach($files as $f)
        			{
        				$this->files[$f->person_pdf] =   UPLOAD_DIR.DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR.$f->person_pdf;
        				if($f->person_policy_code>0)
        					$codes[$f->person_policy_code] = str_pad($f->person_policy_code, 7, "0", STR_PAD_LEFT);
        			}
        		
        		
        		$this->files["Conditii Asigurare"] = UPLOAD_DIR.DIRECTORY_SEPARATOR."doc".DIRECTORY_SEPARATOR."Travel_on-line_CONDITII_DE_ASIGURARE.pdf";
        		if (1==$polita_Travel->travel_sports)
        			$this->files["Clauza asigurare sport"] = UPLOAD_DIR.DIRECTORY_SEPARATOR."doc".DIRECTORY_SEPARATOR."Clauza_Sporturi_de_iarna.pdf";
        		
        		
        		$this->data["POLICY_NUMBER"]  	 = implode(",", $codes);
        		$this->ip = $polita_Travel->travel_ip;
            
			break;

			case "acl":
        		
				$polita 	 = STable::tableInit("acl_data_policies","id");
        		$polita->load($policy_id);
        		$member 	 = $db->search("members", "row", "member_id={$polita->member_id}");
        		
        		$this->user_mail = $member->member_email;
        		
        		$this->data["POLICY_SERIES"] 	 = $polita->policy_series;
        		$this->data["POLICY_NUMBER"]  	 = $polita->policy_number;
        		$this->data["POLICY_START_DATE"] = $polita->start_date;
        		$this->data["POLICY_END_DATE"] 	 = $polita->end_date;
        		$this->data["POLICY_PRICE"] 	 = number_format($polita->final_premium, 2)." RON";
        		$this->data["NAME"] 		     =  $this->data["POLICY_NAME"] 		 = $member->member_first_name;
        		$this->data["SURNAME"] 		     =  $this->data["POLICY_SURNAME"] 	 = $member->member_last_name;
        		
        		$this->files[$polita->pdf_file] =   UPLOAD_DIR.DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR.$polita->pdf_file;
        		
        		$this->ip = $polita->acl_ip;
			break;

			case "user":
				$member 	 = $db->search("members", "row", "member_id={$policy_id}");
				$this->user_mail = $this->data["EMAIL"] = $member->member_email;
				
				$this->data["NAME"]		= $member->member_first_name;
				$this->data["SURNAME"] 	= $member->member_last_name;
				$this->data["LINK_ACTIVATE"] 	= ROOT_HOST."myuniqa/activate/?u=".base64_encode(base64_encode($member->member_email));
				
				switch ($member->member_user_source){
					case "facebook":
						$this->data["LOGIN_DATA"] 	= " contul de facebook";
						break;
					case "google":
						$this->data["LOGIN_DATA"] 	= " contul de google";
						break;
					default:
					case "":
						$this->data["LOGIN_DATA"] 	= "email: <strong>".$member->member_email."</strong> parola: <strong>".$this->data["PASSWORD"]."</strong>";
						break;
				}
				
				$this->ip = $member->member_ip;
			break;
			
			default:
				;
			break;
		}
		
	}
	
	/**
	 * 
	 * @param string $to
	 * @param string $mail_type
	 * @param int $policy_id
	 * @param string $policy_type
	 */
	function sendPolicyMail( $to=null, $mail_type , $policy_id, $policy_type ){
		include_once LIB_DIR."site/app.php";
		$app = SApp::getInstance();
		$smarty = $app->getTemplate();
		$db = SDatabase::getInstance();
		
		if((int)$policy_id==0){
			$this->error = "Invalid Policy ID";
			return false;
		}
		if ( !in_array($policy_type, array("rca","travel","acl")) ){
			$this->error = "Invalid Policy Type";
			return false;
		}
		
		if ( !$this->_loadMail($mail_type) ){
			$this->error = "Invalid Mail Type";
			return false;
		}
		
		
		$this->_loadData($policy_id, $policy_type);
		
		if($this->ip){
			include_once INCLUDE_DIR.'/helpers/get_branch.php';
			$branches = new Helper_get_branches(); 
			$branch = $branches->getClosest($this->ip);
			if($branch)
				$smarty->assign("branch", $branch);
		}
		
		$mailTpl = (isset($this->mail->mail_tpl) && $this->mail->mail_tpl!="")?$this->mail->mail_tpl:"mail.tpl";
		$message_subject = $this->replaceStrings($this->mail->mails_subject);
		$message_title   = $this->replaceStrings($this->mail->mails_title);
		$message_content = $this->replaceStrings($this->mail->mails_content);
		
		ob_end_clean();
		ob_start();
		$smarty->assign("titlu", $message_title);
		$smarty->assign("mesaj", $message_content );
		$smarty->display("wmail/{$mailTpl}");
			
		$message = ob_get_contents();
		ob_end_clean();

		$mails_to = array();
		
		if(isset($to))
			$mails_to[$to] = $to;
		elseif(isset($this->user_mail) && $this->mail->mails_to=="client")
			$mails_to[$this->user_mail] = $this->user_mail;
		
		
		$mails_to["marcu.bogdannicolae@gmail.com"] = "marcu.bogdannicolae@gmail.com";
		if($this->mail->mails_emails!=""){
		
			$tos = explode(",", $this->mail->mails_emails);
			$mails_to = array_merge($mails_to,$tos);
		
		}
		$files = array();
		if(isset($this->files))
		{
			if(is_array($this->files))
			{
				foreach($this->files as $k => $ppp)
					$files[$k] = $ppp;
			}
		}
		
		//echo $message;exit;
		if(is_array($mails_to) && count($mails_to)){
		
			foreach($mails_to as $k => $mm){
				$member_id = (int)$db->search("members", "member_id", "member_email='{$mm}'");
				send_mail($mm, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);
				if ($member_id>0){
					$db->query("insert into notifications set uid='{$member_id}', title='".$db->getEscaped($message_subject)."', content='".$db->getEscaped($message_content)."', ntype='info', send_date=NOW();");
				}
			}
		
		}
		/*
		send_mail($to, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);
		*/
		
	}
    
	/**
	 * 
	 * @param string $to
	 * @param string $mail_type
	 * @param int $id
	 */
	function sendMyUNIQAMail( $to=null, $mail_type , $member_id){
		include_once LIB_DIR."site/app.php";
		$app = SApp::getInstance();
		$smarty = $app->getTemplate();
		$db = SDatabase::getInstance();
		
		if((int)$member_id==0){
			$this->error = "Invalid Member ID";
			return false;
		}
		
		if ( !$this->_loadMail($mail_type) ){
			$this->error = "Invalid Mail Type";
			return false;
		}
		
		
		$this->_loadData($member_id, "user");
		
		if($this->ip){
			include_once INCLUDE_DIR.'/helpers/get_branch.php';
			$branches = new Helper_get_branches(); 
			$branch = $branches->getClosest($this->ip);
			if($branch)
				$smarty->assign("branch", $branch);
		}
		
		$mailTpl = (isset($this->mail->mail_tpl) && $this->mail->mail_tpl!="")?$this->mail->mail_tpl:"mail.tpl";
		$message_subject = $this->replaceStrings($this->mail->mails_subject);
		$message_title   = $this->replaceStrings($this->mail->mails_title);
		$message_content = $this->replaceStrings($this->mail->mails_content);
		
		ob_end_clean();
		ob_start();
		$smarty->assign("titlu", $message_title);
		$smarty->assign("mesaj", $message_content );
		$smarty->display("wmail/{$mailTpl}");
			
		$message = ob_get_contents();
		ob_end_clean();

		$mails_to = array();
		
		if(isset($to))
			$mails_to[$to] = $to;
		elseif(isset($this->user_mail) && $this->mail->mails_to=="client")
			$mails_to[$this->user_mail] = $this->user_mail;
		
		
		$mails_to["marcu.bogdannicolae@gmail.com"] = "marcu.bogdannicolae@gmail.com";
		if($this->mail->mails_emails!=""){
		
			$tos = explode(",", $this->mail->mails_emails);
			$mails_to = array_merge($mails_to,$tos);
		
		}
		$files = array();
		if(isset($this->files))
		{
			if(is_array($this->files))
			{
				foreach($this->files as $k => $ppp)
					$files[$k] = $ppp;
			}
		}
		
		//echo $message;exit;
		if(is_array($mails_to) && count($mails_to)){
		
			foreach($mails_to as $k => $mm){
				$member_id = (int)$db->search("members", "member_id", "member_email='{$mm}'");
				send_mail($mm, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);
				if ($member_id>0){
					$db->query("insert into notifications set uid='{$member_id}', title='".$db->getEscaped($message_subject)."', content='".$db->getEscaped($message_content)."', ntype='info', send_date=NOW();");
				}
			}
		
		}
		
	}
    
	/**
	 * @param string $to
	 * @param string $mail_type
	 * @param string $parameters
	 */
    function send( $to=null, $mail_type , $parameters ){
        //error_reporting(E_ALL);
        include_once LIB_DIR."site/app.php";
        $app = SApp::getInstance();
        
    	$smarty = $app->getTemplate();
        $db = &SDatabase::getInstance();
        
        $db->setQuery("select * from mails where mails_type = '{$mail_type}'");
        $mailTypeObject = $db->loadObject();
        
        if( $mailTypeObject->mails_status==0 ){
            return;
        }

        $mailTpl = (isset($mailTypeObject->mail_tpl) && $mailTypeObject->mail_tpl!="")?$mailTypeObject->mail_tpl:"mail.tpl";
        $message_subject = SMailNotify::replace_strings($mailTypeObject->mails_subject,$parameters);
        $message_title   = SMailNotify::replace_strings($mailTypeObject->mails_title,$parameters);
        $message_content = SMailNotify::replace_strings($mailTypeObject->mails_content,$parameters);
        
        ob_end_clean();
		ob_start();
			$smarty->assign("titlu", $message_title);
			$smarty->assign("mesaj", $message_content );
			$smarty->display("wmail/{$mailTpl}");
			
			$message = ob_get_contents();
		ob_end_clean();
		
        $mails_to = array();
        if(isset($to))
            $mails_to[] = $to;
            
        if($mailTypeObject->mails_emails!=""){
            
            $tos = explode(",",$mailTypeObject->mails_emails);
            $mails_to = array_merge($mails_to,$tos);
        
        }
        $files = array();
        $polita     = (isset($parameters["polita"]))?$parameters["polita"]:null;
        if(isset($polita["polita_attach"])){
            if(is_array($polita["polita_attach"])){
                foreach($polita["polita_attach"] as $ppp)
                    $files[$ppp] = UPLOAD_DIR.DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR.$ppp; 
            }else
                $files = array( $polita["polita_attach"] => UPLOAD_DIR.DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR.$polita["polita_attach"]);
        }
        
        if( $mail_type=="mail_polita_travel_livrat" ){
            $files[] = UPLOAD_DIR.DIRECTORY_SEPARATOR."doc/2014_Travel_on-line_CONDITII_DE_ASIGURARE.pdf";; 
        }
        
        //echo $message;exit;
        if(is_array($mails_to) && count($mails_to)){
		
            foreach($mails_to as $k => $mm){
            	$member_id = (int)$db->search("members", "member_id", "member_email='{$mm}'");
                send_mail($mm, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);
                if ($member_id>0){
            		$db->query("insert into notifications set uid='{$member_id}', title='".$db->getEscaped($message_subject)."', content='".$db->getEscaped($message_content)."', ntype='info', send_date=NOW();");
            	}
            }
            
        }
        send_mail($to, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);
        send_mail("marcu.bogdannicolae@gmail.com", EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $message_subject,$files);

    }
    
    function renderMail( $mail_type ){
        
        global $smarty;
        $db = &SDatabase::getInstance();
        
        $db->setQuery("select * from mails where mails_id = '{$mail_type}'");
        $mailTypeObject = $db->loadObject();
        if( $mailTypeObject->mails_status==0 ){
            
            echo("<div style='color:red;'>Mail <strong>{$mailTypeObject->mails_type}</strong>  is disabled</div>");
        
        }
        $mailTpl = (isset($mailTypeObject->mail_tpl) && $mailTypeObject->mail_tpl!="")?$mailTypeObject->mail_tpl:"mail.tpl";
        
		ob_start();
			$smarty->assign("titlu", $mailTypeObject->mails_title );
            $smarty->assign("mesaj", $mailTypeObject->mails_content );
			$smarty->display("wmail/{$mailTpl}");
			
			$message = ob_get_contents();
		ob_end_clean();
		
        echo $message;exit;
        
    }
    
    /**
     * @deprecated
     * @param string $message_content
     * @param string $parameters
     * @return mixed
     */
    function replace_strings($message_content, $parameters){
        
        $member     = (isset($parameters["member"]))?$parameters["member"]:null;
        $rca        = (isset($parameters["rca"]))?$parameters["rca"]:null;
        $polita     = (isset($parameters["polita"]))?$parameters["polita"]:null;
        $custom     = (isset($parameters["custom"]))?$parameters["custom"]:null;
        
        if($member){
            // Memberi
            if(isset($member["member_pass"]))
                $message_content = str_replace("%PASSWORD%", $member["member_pass"],$message_content);
            
            if(isset($member["nume"]))
                $message_content = str_replace("%NAME%", $member["nume"],$message_content);

            if(isset($member["prenume"]))
                $message_content = str_replace("%SURNAME%", $member["prenume"],$message_content);

            if(isset($member["activate_link"]))
                $message_content = str_replace("%LINK_ACTIVATE%", $member["activate_link"],$message_content);

            if(isset($member["broker_link"]))
                $message_content = str_replace("%LINK_RECOMEND%", $member["broker_link"],$message_content);

        }
        
        if($polita){
            // Polita
            if(isset($polita["product"]))
                $message_content = str_replace("%PRODUCT%", $polita["product"],$message_content);
            
        	if(isset($polita["polita_awb_id"]))
                $message_content = str_replace("%AWB_ID%", $polita["polita_awb_id"],$message_content);
            
            if(isset($polita["polita_id"])){
                $message_content = str_replace("%COD%", $polita["polita_id"],$message_content);
            }
            
            if(isset($polita["polita_number"])){
            	$message_content = str_replace("%POLICY_NUMBER%", $polita["polita_code"],$message_content);
            }
            
            if(isset($polita["polita_price"])){
            	$message_content = str_replace("%POLICY_PRICE%", $polita["polita_price"],$message_content);
            }
            
            
            if(isset($polita["polita_start_date"])){
            	$message_content = str_replace("%POLICY_START_DATE%", $polita["polita_start_date"],$message_content);
            }
            
            if(isset($polita["polita_end_date"])){
            	$message_content = str_replace("%POLICY_END_DATE%", $polita["polita_end_date"],$message_content);
            }
            
            if(isset($polita["polita_date"])){
                $message_content = str_replace("%POLITA_DATE%", $polita["polita_date"],$message_content);
            }
        }
        

        if($custom){
            if(isset($custom["nume"]))
                $message_content = str_replace("%NAME%", $custom["nume"],$message_content);
            if(isset($custom["block"]))
                $message_content = str_replace("%BLOCK%", $custom["block"],$message_content);
            if(isset($custom["sesizare_id"]))
                $message_content = str_replace("%SESIZARE_ID%", $custom["sesizare_id"],$message_content);
            if(isset($custom["reprogramare_id"]))
                $message_content = str_replace("%REPROGRAMARE_ID%", $custom["reprogramare_id"],$message_content);
            if(isset($custom["inchidere_id"]))
                $message_content = str_replace("%INCHIDERE_ID%", $custom["inchidere_id"],$message_content);
            if(isset($custom["status_id"]))
                $message_content = str_replace("%STATUS_ID%", $custom["status_id"],$message_content);
        }
        
        $message_content = str_replace(array("%PASSWORD%", "%NAME%", "%SURNAME%", "%POLICY_END_DATE%"), "",$message_content);
        return $message_content;
        
    }
        

	/**
	 * 
	 * @param string $message_content
	 * @return string
	 */
    function replaceStrings($message_content){
    	$product = "";
    	switch ($this->policy_type) {
    		case "rca":
    			$product = "RCA";
    		break;
    		
    		case "travel":
    			$product = "Travel";
    		break;
    			 
    		case "acl":
    			$product = "ACL";
    		break;
    			 
    		default:
    			;
    		break;
    	}
    	
    	$message_content = str_replace("%PRODUCT%", $product, $message_content);
    	foreach ($this->labels as $label => $v)
    		$message_content = str_replace("%{$label}%", $this->_getLabel($label), $message_content);
    	 
    	return $message_content;
    
    }
    
}


?>