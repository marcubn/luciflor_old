<?php
require(LIB_DIR."mail/phpmailer/class.phpmailer.php");

/**
 * Send Mail
 *
 * @access: public
 * @return: null
*/
function send_mail($toMail, $fromMail='', $fromName='', $message, $subject='', $files=array(), $isHTML=true)
{		
	$mail = new PHPMailer();
		
        $mail->From = ($fromMail=='')?EMAIL_FROM_ADDR:$fromMail;
        $mail->FromName = ($fromName=='')?EMAIL_FROM_NAME:$fromName;
		
	$mail->Mailer   = (defined("MAILER") ? MAILER:"smtp");
	$mail->Host     = defined("MAIL_HOST")?MAIL_HOST:"localhost";
        $mail->Port     = defined("MAIL_PORT")?MAIL_PORT:25;
        
        if(defined("MAILER") && MAILER=="smtp"){
            
            $mail->IsSMTP();
            $mail->SMTPAuth   = (defined("MAIL_USER") && defined("MAIL_PASS"))?true:false;
            $mail->Username = defined("MAIL_USER")?MAIL_USER:null;
            $mail->Password = defined("MAIL_PASS")?MAIL_PASS:null;
            $mail->SMTPDebug = defined("MAIL_DEBUG")?MAIL_DEBUG:null;;
            
        }
        

	if( is_array($toMail) ){
            foreach($toMail as $k=>$v)
                $mail->AddBCC($v, "");
	}else
            $mail->AddAddress($toMail, "");
		
	$mail->Subject = $subject;
	$mail->Body    = $message;
        $mail->MsgHTML($message);
        $mail->IsHTML($isHTML);
        
	
	if( is_array($files) && count($files)>0 ){
            while(list($key, $val) = each($files))
                $mail->AddAttachment($val, $key);
	}
		
	$status = $mail->Send();
        
        if( defined("MAIL_REPORTS") && MAIL_REPORTS==1 ){
            
        	$db = SDatabase::getInstance();
            $message = $db->getEscaped($message);
            $report = "INSERT INTO mail_report SET `from`='{$mail->From}', `email`='{$toMail}', sent_time=NOW(), `title`='{$subject}', `content`='{$message}', status='{$status}'";
            
            $db->query( $report );
        }
    
	$mail->ClearAllRecipients();
        $mail->ClearAttachments();
    
}
?>