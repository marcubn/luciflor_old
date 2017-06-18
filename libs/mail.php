<?
function send_mail2($type, $recipients, $from, $body, $subject, $files=array()) 
{
	
	if($from=='')
		$from=EMAIL_FROM; 
	$crlf = "\r\n";
	$hdrs = array(
       			'From'    => $from,       			
   				'Subject' => $subject
	   		);
	$mime = new Mail_mime($crlf);

	if($type)
		$mime->setHTMLBody($body);
	else
		$mime->setTXTBody($body);
	
	if(count($files) && $files!='')
		while(list($key, $val) = each($files)) 
			$mime->addAttachment($val, filetype($val), $key);	
			
	$body = $mime->get();
	$hdrs = $mime->headers($hdrs);

	$params=array();
	$params['sendmail_path'] = '/usr/sbin/sendmail';

	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory('sendmail', $params);	
	
	if(!$mail_object->send($recipients, $hdrs, $body)) 
		echo $mail_object->getMessage();
}

function send_mail($toMail, $fromMail='', $fromName='',  $message, $subject='', $files=array())
{		
	$mail = new PHPMailer();
		
	if($fromMail=="")	
		$mail->From = EMAIL_FROM_MAIL;
	else 
		$mail->From = $fromMail;
	
	$mail->FromName = EMAIL_FROM_NAME;
	
	$mail->Host     = "localhost";
	$mail->Mailer   = "smtp";

	// Now you only need to add the necessary stuff
	
	if(is_array($toMail))
	{
		//$mail->AddAddress($toMail[0], "");
		foreach($toMail as $k=>$v)
		{
			$mail->AddBCC($v, "");
		}
	}
	else
		$mail->AddAddress($toMail, "");
	
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->IsHTML(true);		
	
	if(count($files) && $files!='')
		while(list($key, $val) = each($files)) 			
			$mail->AddAttachment($val, $key);
		
	$mail->Send();
	
	$mail->ClearAllRecipients();		
    $mail->ClearAttachments();
}

?>