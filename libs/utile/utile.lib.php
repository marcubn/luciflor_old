<?php
/**
 * Get IP
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function getIP()
{
	return $_SERVER["REMOTE_ADDR"];	
}

/**
 * Get's a random ID - used in generating form id's
 * 
 * @return string
 */
function getNoSpoofID(){
    
    return time().rand(0,100).md5(getIP());
}

/**
 * Get Session Id
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function getSessId()
{
	return session_id();
}

function validare_nr_tel($nr)
{
	return (strlen($nr)>=9 && strlen($nr)<=13);
	//return preg_match('/^\(?07\d{2}\)?[-\s]?\d{3}[-\s]?\d{3}$/', $nr) ? true : false;
}

/**
 * Redirect
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function redirect($url)
{	
	global $db;
	
	//if(is_object($db)) $db->disconnect();
	
	header("Request-URI: $url"); 
	header("Content-Location: $url"); 
	header("Location: $url");
	exit;
}

/**
 * Init class variables
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 05.04.2005 (dd.mm.YYYY)
*/	
function objInitVar(&$obj, $tplName, $moduleName, $pagingAction, $tableName, $idName, $flagName)
{
	global $smarty;
	
	if($tplName!='') 		
		$obj->tplName = $tplName;
	if($moduleName!='' && isset($obj->moduleName))
		$obj->moduleName = $moduleName;
	if($pagingAction!='') 
		$obj->pagingAction = $pagingAction;
	if($tableName!='' && isset($obj->tableName))
		$obj->tableName	= $tableName;
	if($idName!='' && isset($obj->idName))
		$obj->idName = $idName;		
	if($flagName!='' && isset($obj->flagName))
		$obj->flagName = $flagName;
	
	if(isset($obj->tableName))
		$smarty->assign("tableName", $obj->tableName);
	if(isset($obj->idName))
		$smarty->assign("idName", $obj->idName);
	if(isset($obj->flagName))
		$smarty->assign("flagName", $obj->flagName);
	if(isset($obj->priorityName))
		$smarty->assign("priorityName", $obj->priorityName);
	
	if(isset($obj->uploadDir))
		$smarty->assign("uploadDir", (defined('UPLOAD_DIR') ? str_replace(UPLOAD_DIR, '', $obj->uploadDir) : $obj->uploadDir));	
		
	if(isset($obj->arrFileFields))
		$smarty->assign("arrFileFields", $obj->arrFileFields);
}

/**
 * Prepare Table Fields
 *
 * @access: public
 * @return: q_tmp
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 02.07.2005 (dd.mm.YYYY)
*/	
function objPrepareTableFields(&$obj)
{
	$db = SDatabase::getInstance();
	if(count($obj->tableFields)>0)
	{
    	$q_tmp = "";
    	foreach($obj->tableFields as $k=>$filedName)
    	{
    		$filedValue = $db->getEscaped(isset($_POST[$filedName]) ? $_POST[$filedName]:'');
    		$q_tmp .= "$filedName = '$filedValue',";
    	}
    	
    	$q_tmp = substr($q_tmp, 0, strlen($q_tmp)-1);
    	
    	return $q_tmp;
	}
    else 
    	redirect("admin.php?obj=index&action=page_invalid");
}

/**
 * File Fields Remove Processing
 *
 * $arrFileFields = array(
 *						"sample2_file1"=>array("name"=>"Image Upl 1", "type"=>"image", "processArgs"=>array("scale", 500)),
 *						"sample2_file2"=>array("name"=>"Image Upl 2", "type"=>"nonimage")
 *					);
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 09.04.06
*/
function fileRemProcessing($tableName, $idName, $idValue, $uploadDir, $arrFileFields)
{
	$uploadDir=UPLOAD_DIR.$uploadDir.'/';
	
	if(isset($arrFileFields) && is_array($arrFileFields) && count($arrFileFields)>0)
	{
		$sqlSel = implode(",", array_keys($arrFileFields));
		
		$q="SELECT {$sqlSel} FROM {$tableName} WHERE {$idName}='{$idValue}'";
		$result = _sqlQuery($q);
		while($record=$result->fetchRow(DB_FETCHMODE_ASSOC))
		{
			foreach($record as $fileField=>$fileName)
			{
				_ffRemoveFile($uploadDir.$fileName);
				
				$q="UPDATE {$tableName} SET {$fileField}='' WHERE {$idName}='{$idValue}'";
				_sqlQuery($q);
			}
		}
	}
}

/**
 * Remove file fromimgupl dir
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 24.06.06
*/
function imgUplRemove($owner, $owner_id)
{
	$uploadDir=UPLOAD_DIR.'imgupl/';
	$q="SELECT file FROM imgupl WHERE owner='$owner' AND owner_id='$owner_id'";
	$result = _sqlQuery($q);
	while($record=$result->fetchRow())
	{
		_ffRemoveFile($uploadDir.$record[0]);
	}
	$q="DELETE FROM imgupl WHERE owner='$owner' AND owner_id='$owner_id'";
	_sqlQuery($q);
}

/**
 * File Fields Upl Processing
 * $arrFileFields = array(
 *						"sample2_file1"=>array("name"=>"Image Upl 1", "type"=>"image", "processArgs"=>array("scale", 500)),
 *						"sample2_file2"=>array("name"=>"Image Upl 2", "type"=>"nonimage"),
 *						"sample3_file3"=>array("name"=>"Image Upl 2", "filePrefix"=>"", "type"=>"nonimage") 						
 *					);
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 09.04.06
*/
function fileUplProcessing($tableName, $idName, $idValue, $uploadDir, $arrFileFields)
{
	$uploadDir=UPLOAD_DIR.$uploadDir.'/';
	
	//===> upload files
    if(isset($arrFileFields) && is_array($arrFileFields) && count($arrFileFields)>0)
    {
    	//include this only if you use file upl
		include_once(LIB_DIR.'upload/upload.class.php');
		
    	foreach($arrFileFields as $fileUpl=>$uplInfo)
    	{
	    	$oldFile = _sqlGetFieldContent($tableName, $fileUpl, $idName, $idValue);
	    	
    		if(isset($_POST[$fileUpl.'_del']) && $oldFile!='')
	    	{
	    		_ffRemoveFile($uploadDir.$oldFile);
				
				$q="UPDATE {$tableName} SET {$fileUpl}='' WHERE {$idName}={$idValue}";
		        _sqlQuery($q);
	    	}
	    	elseif(!isset($_POST[$fileUpl.'_del']))
	    	{
	    		$strArgs="";
	    		if(isset($uplInfo['processArgs']) && is_array($uplInfo['processArgs']) && count($uplInfo['processArgs'])>0)
        		{
        			foreach($uplInfo['processArgs'] as $k=>$arg)
        				$strArgs .= "'{$arg}', ";
        			if($strArgs!="")
        				$strArgs=substr($strArgs, 0, strlen($strArgs)-2);
        		}
        		
        		if(isset($uplInfo['filePrefix']) && $uplInfo['filePrefix']!='')
        			$filePrefix=$uplInfo['filePrefix'];
        		else 
        			$filePrefix="{$fileUpl}_{$idValue}";
        			    		
	    		$obj = new upload($fileUpl, $uploadDir, $filePrefix);
		    	$obj->addExtForDst();
		    	eval('$objUplBool=$obj->process('.$strArgs.');');
		    	if($objUplBool)
		    	{
		        	$fileDst = $obj->getFileDst();
		        	unset($obj);
		        	
					if($oldFile!='')
					{
						if($oldFile!=$fileDst) _ffRemoveFile($uploadDir.$oldFile);
					}
					
					$q="UPDATE {$tableName} SET {$fileUpl}='{$fileDst}' WHERE {$idName}={$idValue}";
		        	_sqlQuery($q);
		    	}
		    	eval('if(is_object($objUplBool)) uset($objUplBool);');
	    	}
    	}
    }
    //<===
}

/**
 * Convert array values to htmlentities(values)
 *
 * @access: public
 * @return: array
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 02.07.2005 (dd.mm.YYYY)
*/	
function htmlArrayFilter($arr)
{
	$arrRet = array();
	if(is_array($arr))
	{
		if(defined("CHARSET") && CHARSET=="utf-8")
		{
			foreach($arr as $k=>$v)
			{
				if(is_string($v))
					$arrRet[$k] =  $v;
				else 
					$arrRet[$k] = $v;
			}
		}
		else 
		{
			foreach($arr as $k=>$v)
			{
				if(is_string($v))
					$arrRet[$k] =  htmlentities2($v);
				else 
					$arrRet[$k] = $v;
			}
		}
	}
	return $arrRet;
}

/**
 * Convert str values to htmlentities
 *
 * @access: public
 * @return: string
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 02.07.2005 (dd.mm.YYYY)
*/	
function htmlentities2($myHTML) 
{
  $translation_table=get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
  $translation_table[chr(38)] = '&';
  return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&amp;" , strtr($myHTML, $translation_table));
}


/**
 * Get currency from  bnr //echo getCurrency();//echo getCurrency('euro');//echo getCurrency('gold');
 *
 * @param: $currency
 * @access: public
 * @return: value or array
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function getCurrency($currency='')
{
	$currencyAssociation = array('euro' => 0, 'usd' => 1, 'gold' => 2);
	$bnr = @file_get_contents('http://www.bnro.ro/Ro/Info/');
	preg_match_all("/<TD class=\"bold\">([0-9,]+)<\/TD>/i", $bnr, $matches);
	//echo "<pre>";print_r($matches);echo "</pre>";
	
	if(isset($matches[1]) && is_array($matches[1]) && count($matches[1])>=3)
	{
		if($currency!='')
			return $matches[1][$currencyAssociation[$currency]]; 
		else 
		{
			$ret=array();
			foreach($currencyAssociation as $k=>$v)
			{
				$ret[$k]=$matches[1][$v];
			}
			
			return $ret;
		}
	}
	else 
		return false;
}

/**
 * Sess cookies destroy
 *
 * @access: public
 * @return: string
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function sess_cookie_destroy()
{
	$CookieInfo = session_get_cookie_params();
	if ( (empty($CookieInfo['domain'])) && (empty($CookieInfo['secure'])) ) 
	{
		setcookie(session_name(), '', time()-3600, $CookieInfo['path']);
	} 
	elseif (empty($CookieInfo['secure'])) 
	{
		setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain']);
	} 
	else 
	{
		setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
	}
	
}

/**
 * Get smarty var in php
 *
 * @access: public
 * @return: string
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function getSmartyVar($var)
{
	global $smarty;
	
	return $smarty->get_template_vars($var);
}

/**
 * Check email address
 *
 * @access: public
 * @return: string
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function checkEmailAddress($email) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!@ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!@ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
			return false;
		}
	}
	  
	if (!@ereg("^\[?[0-9\.]+\]?$", $email_array[1]))// Check if domain is IP. If not, it should be valid domain name
	{
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) 
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!@ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
			{
				return false;
			}
		}
	}
	
	return true;
}


/**
 * returns from param REQUEST index value
 * 
*/
function getFromRequest($SUPERGLOBAL, $index, $default=''){
    $db = &SDatabase::getInstance();
    return strip_tags(mysqli_real_escape_string($db->_resource, isset($SUPERGLOBAL[$index]) ? $SUPERGLOBAL[$index]:$default));
}

class systemComPipe{
    
    public static function setCode($code){
        
        // 0 - mesaj; 1 - notificare importanta; 2 eroare; 3 ok
        $codes = array( 0 => "info",1 => "info", 2 => "error",3 => "success" );
        
        // To DO: If debug mode throw wtf message
        if( !isset($codes[$code]) )
            $code = 0;
            
        $_SESSION[SESS_IDX]["code"] = $codes[$code];
    }
    
    public static function getCode(){
        
        if(isset($_SESSION[SESS_IDX]["code"])){
            $code = $_SESSION[SESS_IDX]["code"];
            $_SESSION[SESS_IDX]["code"] = null;
            return $code;
        }
        
        return null;
    
    }
    
}

class systemMessage{

    public static function addTrackEvent( $js_code){
        $_SESSION[SESS_IDX]["js_events"][] = $js_code;
    }

    public static function renderTrackEvents(){
        if(
            isset($_SESSION[SESS_IDX]["js_events"]) && is_array($_SESSION[SESS_IDX]["js_events"]) && 
            count($_SESSION[SESS_IDX]["js_events"])>0 
        ){
           $output = implode(PHP_EOL,$_SESSION[SESS_IDX]["js_events"]);
           unset($_SESSION[SESS_IDX]["js_events"]);
           return $output;
        }
    }
    
    public static function addMessage( $message, $code = 0 ){
        if(!isset($_SESSION[SESS_IDX]["message_queue"]))
            $_SESSION[SESS_IDX]["message_queue"]=array();
        systemComPipe::setCode($code);
        if(is_array($message))
            $_SESSION[SESS_IDX]["message_queue"] = array_merge($_SESSION[SESS_IDX]["message_queue"],$message);
        else
            $_SESSION[SESS_IDX]["message_queue"][] = $message;
    
    }
    
    public static function setMessage( $message, $code = 0 ){
        systemComPipe::setCode($code);
        $_SESSION[SESS_IDX]["message_queue"] = array($message);
    }
    public static function clearMessages(){
        $_SESSION[SESS_IDX]["message_queue"] = null;
    }
    
    public static function renderSystemMessage( $separator = PHP_EOL ){
        if(
            isset($_SESSION[SESS_IDX]["message_queue"]) && is_array($_SESSION[SESS_IDX]["message_queue"]) && 
            count($_SESSION[SESS_IDX]["message_queue"])>0 
        ){
           $output = implode(PHP_EOL,$_SESSION[SESS_IDX]["message_queue"]);
           systemMessage::clearMessages(); 
           return $output;
        }
    }
}

//obtain visitor IP even if is under a proxy
function getVisitorIP(){
   $ip = $_SERVER['REMOTE_ADDR'];
   if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
   }
   return $ip;
}

?>