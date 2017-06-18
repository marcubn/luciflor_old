<?php
//###################//
//# Utile for Admin #//
//###################//

/**
 * Init Admin
 *
 * @todo
 * @access: public
 * @return: null
*/
function initAdmin()
{
	global $smarty;
		
	define("SESS_IDX", SESS_IDX_BE);
	
	if(!isset($_SESSION[SESS_IDX]))
		$_SESSION[SESS_IDX]=array();
	
	if(isset($_SESSION[SESS_IDX]['UL']['auth']) && $_SESSION[SESS_IDX]['UL']['auth']==1)
	{

		if(!isset($_SESSION[SESS_IDX]['menu']))
			$_SESSION[SESS_IDX]['menu']=getAdminMenu();
        
			
		//assign info user
		$smarty->assign("UL", $_SESSION[SESS_IDX]['UL']);
		
		//assign menu admin
		$smarty->assign("menuTop", $_SESSION[SESS_IDX]['menu']);
	}
	
	updateLogoutTime();
    
    //===>MOBILE DETECT
		    require_once( LIB_DIR . "MobileDetect/Mobile_Detect.php");
		    $detect = new Mobile_Detect;
		    $smarty->assign("IS_MOBILE", $detect->isMobile());
    //===>MOBILE DETECT
	
	$small_icons = array(
			"car" 					 => "Car",
			"care" 					 => "Care",
			"accident" 				 => "Accident",
			"health" 				 => "Health",
			"travel"				 => "Travel",
			"retire"				 => "retire",
			"home" 					 => "Home",
			
			"bk_icon UNI-icon-health-insurance-basic"	     => "UNI-icon-health-insurance-basic",
			"bk_icon UNI-icon-echipa-si-success"		     => "UNI-icon-echipa-si-success",
			"bk_icon UNI-icon-integritate-si-respect" 	     => "UNI-icon-integritate-si-respect",
			"bk_icon UNI-icon-general-third-party-liability" => "UNI-icon-general-third-party-liability",
			"bk_icon UNI-icon-optim-eficient"		         => "UNI-icon-optim-eficient",
			"bk_icon UNI-icon-ablebensversicherung"	         => "UNI-icon-ablebensversicherung",
			"bk_icon UNI-icon-dental-insurance"		         => "UNI-icon-dental-insurance",
			"bk_icon UNI-icon-Haftpflicht-insurance"		 => "UNI-icon-Haftpflicht-insurance",
			"bk_icon UNI-icon-terms-and-conditions"			 => "UNI-icon-terms-and-conditions",
			"bk_icon UNI-icon-health-insurance-plus"		 => "UNI-icon-health-insurance-plus",
			"bk_icon UNI-icon-household-plus"		         => "UNI-icon-household-plus",
			"bk_icon UNI-icon-kasko"		                 => "UNI-icon-kasko",
			"bk_icon UNI-icon-staatliche-minimumversicherung-2"	=> "UNI-icon-staatliche-minimumversicherung-2",
			"bk_icon UNI-icon-travel-insurance"			        => "UNI-icon-travel-insurance",
			"bk_icon UNI-icon-branches"				            => "UNI-icon-branches",
			"bk_icon UNI-icon-career"		                    => "UNI-icon-career",
			"bk_icon UNI-icon-claims"				            => "UNI-icon-claims",
			"bk_icon UNI-icon-sponsoring"				        => "UNI-icon-sponsoring",
			"bk_icon UNI-icon-customer-service"			        => "UNI-icon-customer-service",
			"bk_icon UNI-icon-csr"		                        => "UNI-icon-csr",
			"bk_icon UNI-icon-forms"		 		            => "UNI-icon-forms",
			"bk_icon UNI-icon-investor-relations"				=> "UNI-icon-investor-relations",
			"bk_icon UNI-icon-mobile"		                    => "UNI-icon-mobile",
			"bk_icon UNI-icon-news"				                => "UNI-icon-news",
			"bk_icon UNI-icon-our-partners"				        => "UNI-icon-our-partners",
			"bk_icon UNI-icon-uniqa-fidelity"		            => "UNI-icon-uniqa-fidelity",
			"bk_icon UNI-icon-uniqa-insurance"				    => "UNI-icon-uniqa-insurance",
	);
	$large_icons = array(
			/*
			"homeblack"					=> "Home Black",
			"mobility"					=> "Mobility",
			"travel"					=> "Travel",
			"insurance"					=> "Insurance",
            "apinsurance"				=> "Apinsurance",
			"healthblack"				=> "Health",
			"art"						=> "Art",
            */
			"bk_icon UNI-icon-health-insurance-basic"	     => "UNI-icon-health-insurance-basic",
			"bk_icon UNI-icon-echipa-si-success"		     => "UNI-icon-echipa-si-success",
			"bk_icon UNI-icon-integritate-si-respect" 	     => "UNI-icon-integritate-si-respect",
			"bk_icon UNI-icon-general-third-party-liability" => "UNI-icon-general-third-party-liability",
			"bk_icon UNI-icon-optim-eficient"		         => "UNI-icon-optim-eficient",
			"bk_icon UNI-icon-ablebensversicherung"	         => "UNI-icon-ablebensversicherung",
			"bk_icon UNI-icon-dental-insurance"		         => "UNI-icon-dental-insurance",
			"bk_icon UNI-icon-Haftpflicht-insurance"		 => "UNI-icon-Haftpflicht-insurance",
			"bk_icon UNI-icon-terms-and-conditions"			 => "UNI-icon-terms-and-conditions",
			"bk_icon UNI-icon-health-insurance-plus"		 => "UNI-icon-health-insurance-plus",
			"bk_icon UNI-icon-household-plus"		         => "UNI-icon-household-plus",
			"bk_icon UNI-icon-kasko"		                 => "UNI-icon-kasko",
			"bk_icon UNI-icon-staatliche-minimumversicherung-2"	=> "UNI-icon-staatliche-minimumversicherung-2",
			"bk_icon UNI-icon-travel-insurance"			        => "UNI-icon-travel-insurance",
			"bk_icon UNI-icon-branches"				            => "UNI-icon-branches",
			"bk_icon UNI-icon-career"		                    => "UNI-icon-career",
			"bk_icon UNI-icon-claims"				            => "UNI-icon-claims",
			"bk_icon UNI-icon-sponsoring"				        => "UNI-icon-sponsoring",
			"bk_icon UNI-icon-customer-service"			        => "UNI-icon-customer-service",
			"bk_icon UNI-icon-csr"		                        => "UNI-icon-csr",
			"bk_icon UNI-icon-forms"		 		            => "UNI-icon-forms",
			"bk_icon UNI-icon-investor-relations"				=> "UNI-icon-investor-relations",
			"bk_icon UNI-icon-mobile"		                    => "UNI-icon-mobile",
			"bk_icon UNI-icon-news"				                => "UNI-icon-news",
			"bk_icon UNI-icon-our-partners"				        => "UNI-icon-our-partners",
			"bk_icon UNI-icon-uniqa-fidelity"		            => "UNI-icon-uniqa-fidelity",
			"bk_icon UNI-icon-uniqa-insurance"				    => "UNI-icon-uniqa-insurance",
			
	);
		    
	$smarty->assign("icon_small_classes", $small_icons);
	$smarty->assign("icon_large_classes", $large_icons);
    $language = SLanguage::getInstance();                    
    $smarty->assign("APP_MESSAGE",systemMessage::renderSystemMessage());
    $smarty->assign("SITE_LANGS", $language->loadLanguages("language_id"));
    $smarty->assign("SITE_LANGS_CODES", $language->loadLanguages("language_code"));
	//===> assign SESS_IDX variales	
	$smarty->assign("DF", "%d %b %Y");	
	$smarty->assign("DTF", "%d %b %Y %H:%M:%S");
	$smarty->assign("buttonStyle", "class=\"btn\" ");
	
	//<===
}
/**
 * Get Admin menu
 *
 * @access: public
 * @return: null
*/
function getAdminMenu()
{
    global $smarty;
    $db = &SDatabase::getInstance();
    $mTableName 	= "menuadmin";
    $mIdName 		= "menuadmin_id";
    $mName 			= "menuadmin_name";
    $mPriorityName 	= "menuadmin_priority";
    $mStatusName 	= "menuadmin_active";
    
    $smTableName 	= "smenuadmin";
    $smIdName 		= "smenuadmin_id";
    $smName 		= "smenuadmin_name";
    $smLinkName 	= "smenuadmin_link";
    $smPriorityName = "smenuadmin_priority";
    $smStatusName 	= "smenuadmin_active";
        
    $list = array();$i=-1;
    
    $q="SELECT 
            {$mTableName}.{$mIdName}, {$mTableName}.{$mName}
        FROM 
            {$mTableName}
        WHERE
     		{$mTableName}.{$mStatusName} = 1
    	ORDER BY
     		{$mTableName}.{$mPriorityName}, {$mTableName}.{$mIdName}
    ";
    $db->setQuery($q);
    $resultM=$db->loadAssocList();
    
    //$resultM = _sqlQuery($q);
    //while($recordM = $resultM->fetchRow(DB_FETCHMODE_ASSOC))
    
    foreach($resultM as $key=>$recordM)
    {
    	$i++;
    	$list[$i] = $recordM;
    	
    	$list[$i]["SM"] = array();
    	
    	//===>
    	$j=-1; $tmpSM = array();
    	
    	$q="SELECT 
	            {$smTableName}.{$smIdName}, {$smTableName}.{$smName}, {$smTableName}.{$smLinkName}
	        FROM 
	            {$smTableName}
	        WHERE
	     		{$smTableName}.{$mIdName} = {$recordM[$mIdName]} AND
    			{$smTableName}.{$smStatusName} = 1
	    	ORDER BY
	     		{$smTableName}.{$smPriorityName}, {$smTableName}.{$smIdName}
	    ";
        
        $db->setQuery($q);
        $resultSM=$db->loadAssocList();
    	//$resultSM = _sqlQuery($q);    	
        //while($recordSM=$resultSM->fetchRow(DB_FETCHMODE_ASSOC))
        foreach($resultSM as $key=>$recordSM)
        {
            $j++;
            $tmpSM[$j] = $recordSM;
            if(isset($_SESSION[SESS_IDX]['UL']["permiss"][$recordSM[$smIdName]]) && $_SESSION[SESS_IDX]['UL']["permiss"][$recordSM[$smIdName]]==1)
                    $tmpSM[$j]["access"]=true;
            else
                    $tmpSM[$j]["access"]=false;
        }

        if($j>=0)
            $list[$i]["SM"] = $tmpSM;
    }
    
    //printr($list);exit;
    return $list;
}

/**
 * Check Acces Admin
 *
 * @access: public
 * @return: null
*/
function checkAccesAdmin($vectPermissAccepted=array())
{
	$flag=0;
	
	if(count($vectPermissAccepted) && isset($_SESSION[SESS_IDX]['UL']['permiss']))
	{		
		foreach($vectPermissAccepted as $key=>$value)
		{
			if($_SESSION[SESS_IDX]['UL']['permiss']==$value)
			{
				$flag=1;
				break;
			}
		}
	}
	
	if(isset($_SESSION[SESS_IDX]['UL']['user_userid']) && strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='root')
		$flag=1;
	
	if($flag==0)
	{
		global $smarty;
		
		$smarty->display("tpl_utile/page_invalid.tpl");
		exit;
	}
}

/**
 * Check Acces Update Admin
 *
 * @access: public
 * @return: null
*/
function checkAccessUpdatePermiss()
{
    $dbo = &SDatabase::getInstance();
    $q = "SELECT smenuadmin_id FROM smenuadmin WHERE smenuadmin_master = 1";
    $dbo->setQuery($q);
    
    $record = $dbo->loadAssoc();
    $smenuadmin_id = $record['smenuadmin_id'];	
    //printr($record);
    //printr($_SESSION[SESS_IDX]['UL']['permiss']);
    if(isset($_SESSION[SESS_IDX]['UL']['permiss']))
        foreach($_SESSION[SESS_IDX]['UL']['permiss'] as $k=>$v)
        {
            if($k==$smenuadmin_id && $v==1)
            {
                    return true;
                    break;
            }	
        }
    
    return false;
}

/**
 * Update Logout Time
 *
 * @access: public
 * @return: null
*/
function updateLogoutTime()
{
    $db = &SDatabase::getInstance();
    if(isset($_SESSION[SESS_IDX]['UL']['login_time']) && $_SESSION[SESS_IDX]['UL']['login_time']>0)
    {
            $perWait = 20;
            $per = 40;
            $cTime = _dtGetTime();

            $sessid = getSessId();
            $userlog_userid = $_SESSION[SESS_IDX]['UL']['user_userid'];

            if(isset($_SESSION[SESS_IDX]['UL']['lupd_time']))
                $luTime = $_SESSION[SESS_IDX]['UL']['lupd_time'];
            else
                $luTime = $_SESSION[SESS_IDX]['UL']['login_time'];

            $dTime = $cTime-$luTime;

            $r = $dTime%$per;
            if($r>$perWait || $dTime>$per) //upd
            {
                $_SESSION[SESS_IDX]['UL']['lupd_time'] = $cTime;

                $q = "SELECT userlog_sessid FROM userlog WHERE userlog_sessid = '$sessid' LIMIT 0,1";
                $db->setQuery($q);
                $exists=$db->loadAssoc();
                if($exists)
                {
                        //===> update user log
                        $userlog_datelogout = date('Y-m-d H:i:s', $cTime);
                        $q="UPDATE 
                                        userlog 
                                SET 
                                        userlog_datelogout='$userlog_datelogout'
                                WHERE
                                        userlog_userid='$userlog_userid' AND 
                                        userlog_sessid='$sessid'
                        ";

                        $db->setQuery($q);
                        //<===
                }
                else
                {
                        $ip = getIP();
                        $datetime = date("Y-m-d H:i:s", $_SESSION[SESS_IDX]['UL']['login_time']);
                        $q="
                        INSERT INTO 
                                userlog
                                SET
                                userlog_userid		= '$userlog_userid',
                                userlog_name		= '{$_SESSION[SESS_IDX]['UL']['user_name']}',
                                userlog_datelogin	= '$datetime',
                                userlog_ip 			= '$ip',
                                userlog_sessid		= '$sessid'
                    ";
                    $db->setQuery($q);
                }
                
                $db->query();
                //echo "UPD:$r<br>";
        }
        //else echo "WAIT:$r<br>";
    }
}

/**
 * Update order of lines in a table
 *
 * @parameters: $tableName = name of table | $fieldName = name of field | $filterName = name of filter field | $filterValue = value of filter field
 * @access: public
 * @return: null
*/
function updateOrders($tableName, $fieldName, $filterName='')
{
	$sqlWhere="";
	if($filterName!="")
	{
		$filterValue=isset($_POST[$filterName])?$_POST[$filterName]:'';
		$sqlWhere=" AND $filterName='$filterValue' ";
	}
	
	$orderValue = isset($_POST[$fieldName])?$_POST[$fieldName]:0;
	$minOrder = _sqlMin($tableName, $fieldName, $filterName, $_POST[$filterName]);
    $maxOrder = _sqlMax($tableName, $fieldName, $filterName, $_POST[$filterName]);
    
    if ($orderValue==0 || $orderValue=="")
    {
    	$orderValue=$maxOrder+1;
    }
    if ($orderValue<$minOrder)
    {
    	$orderValue=$minOrder-1;
    	if ($orderValue==0)
    	{
    		$q="UPDATE {$tableName} SET {$fieldName}={$fieldName}+1 WHERE 1 {$sqlWhere}";
    	}
    	else 
    	{
    		$q="UPDATE {$tableName} SET {$fieldName}={$fieldName}+1 WHERE {$fieldName}>={$orderValue} {$sqlWhere}";
    	}
    }
    elseif ($orderValue>$maxOrder)
    {
    	$orderValue=$maxOrder+1;
    }
    else 
    {
    	$q="UPDATE {$tableName} SET {$fieldName}={$fieldName}+1 WHERE {$fieldName}>={$orderValue} {$sqlWhere}";
    }
    
    if (isset($q) && $q!="") _sqlQuery($q);
    
    $_POST[$fieldName] = $orderValue;
}

?>