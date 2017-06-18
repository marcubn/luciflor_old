<?php
//##############//
//# User class #//
//##############//

class user
{
	var $tplName 		 = "";
    var $moduleName 	 = "";
    var $pagingAction 	 = "";

    var $tableName 		 = "user";
    var $idName 		 = "user_id";
	var $flagName 		 = "user_active";	
	var $tableFields	  = array("user_userid", "user_name", "user_email", "user_tel", "user_connected", "user_active", "user_group");
	
	var $tableUPermiss 	 = "userpermiss";

	var $defaultRootPass = "root";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function user($action="")
	{		
		global $smarty;
		
		//autocreate user root if not exit
		$this->autoCreateUserRoot();
        
		
		//assign status acces update user permiss
		$smarty->assign("accessUpdatePermiss", checkAccessUpdatePermiss());	
			
		switch($action)
	  	{
			case "page_myaccount":				
				$this->page_myaccount();
      		 	break;
	  		
	  		case "page_act":	  			
				$this->page_act();
      		 	break;
      		 	
      		case "add_upd":				
				$this->add_upd();
      		 	break;
      		 	
      		case "page_login":				
				$this->page_login();
      		 	break;
      		
      		case "page_list":				
				$this->page_list();
      		 	break;      		      		
			
			case "switch_status":
				$this->switch_status();				
				$this->page_list();
				break;
      		 	 	 	
      		case "page_logs":
				$this->page_logs();
      		 	break;
      		 	
      		case "logout":
				$this->logout();
      		 	break;
      		
      		case "autolog":
				$this->autolog();
      		 	break;
	  	}
	}
	
	/**
	 * Autocreate user root if this not exist
	 *
	 * @access: public
	 * @return: null
	*/	
	function autoCreateUserRoot()
	{				
        $db = &SDatabase::getInstance();
		$id=1;
		$q = "SELECT $this->idName FROM $this->tableName WHERE user_userid = 'root' AND $this->idName = $id";
        $db->setQuery($q);
        $result = $db->loadAssoc();
        
		if( count($result)==0 )
		{
			$q = "DELETE FROM $this->tableName WHERE user_userid = 'root' OR $this->idName = $id";
			$db->setQuery($q);
            $db->query();
			
			$datec = _dtGetDate();
			
			$q = "
				INSERT INTO 
					$this->tableName 
				SET 
					$this->idName 	= $id,
					user_datec		= '$datec',
					user_owner		= 0,
					user_userid 	= 'root',
					user_pass 		= '".md5($this->defaultRootPass)."',
					user_name		= 'ROOT',
					user_active  	= 1									
			";		
			$db->setQuery($q);
            $db->query();
			
			//Delete old permiss
            $q="DELETE FROM {$this->tableUPermiss} WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
			
			//===> add user permiss
            
			$vectPermiss=$this->getPermiss();
			$q 		= "INSERT INTO $this->tableUPermiss ($this->idName, smenuadmin_id) VALUES ";
			$q_body = "";
			for($i=0;$i<count($vectPermiss["available"]["id"]);$i++)
				$q_body .= "($id, '{$vectPermiss["available"]["id"][$i]}'), ";	
			if($q_body != '')
			{
				$q .= substr($q_body, 0, (strlen($q_body)-2));
				$db->setQuery($q);
                $db->query();
			}
            
			//<===
		}		
	}
	
	/**
	 * Validate login data, session register, set logs
	 *
	 * @access: public
	 * @return: null
	*/	
	function validate_login($user, $pass, $passEcoded=0)
	{
        $db = &SDatabase::getInstance();
		if($passEcoded==1)
			$q_pass = "'$pass'";
		else
			$q_pass = md5($pass);
			
	   $q="SELECT 
				{$this->tableName}.*
			FROM 
				{$this->tableName}
			WHERE 
				{$this->tableName}.user_userid = '$user' AND 
				{$this->tableName}.user_pass = '$q_pass' AND 
				{$this->tableName}.{$this->flagName} = 1
		";        
        $db->setQuery($q);
        $record=$db->loadAssoc();
        
		if($record)
		{
			
			//session register
			unset($_SESSION[SESS_IDX]);
			
			$_SESSION[SESS_IDX]['UL']=array();
			$_SESSION[SESS_IDX]['UL']=$record;	
            $_SESSION[SESS_IDX]['UL']['auth']=1;
            unset($_SESSION[SESS_IDX]['UL']['user_pass']);
            
			$_SESSION[SESS_IDX]['UL']['permiss']=$this->getUserPermiss($record['user_id']);
            
			//<===
			
			if(isset($_POST["autologin"]) && $_POST["autologin"]==1)
			{
				setcookie(VAR_COOKIE_USER, $_SESSION[SESS_IDX]['UL']["user_userid"], time()+3600*24*365);
				setcookie(VAR_COOKIE_PASS, $_SESSION[SESS_IDX]['UL']["user_pass"], time()+3600*24*365);
			}
			
			//===> ADD USERS LOGS
			$this->addUserLogs();
			//<===
			
			return true;
		}
		else 
			return false;
	}
	
	/**
	 * Auto login
	 *
	 * @access: public
	 * @return: null
	*/
	function auto_login()
	{
		if(isset($_COOKIE[VAR_COOKIE_USER]) && isset($_COOKIE[VAR_COOKIE_PASS]) && !(isset($_GET["act"]) && $_GET["act"]=="logout"))
		{			
			$user = $_COOKIE[VAR_COOKIE_USER];
			$pass = $_COOKIE[VAR_COOKIE_PASS];
			
			if($this->validate_login($user, $pass, 1))			
				return true;
			else 
				return false;
		}
		else
			return false;
	}
	
	/**
	 * Page login
	 *
	 * @access: public
	 * @return: null
	*/
	function page_login()
	{
		global $smarty;
		
		objInitVar($this, "admin/page_login.tpl", "", "", "", "", "");
				
		if($this->auto_login())
		{
			redirect("index.php?obj=index");
			exit;
		}
		elseif(isset($_POST['user']) && isset($_POST['pass']))
		{
			//$user = _sqlEscValue($_POST['user']);
			//$pass = _sqlEscValue($_POST['pass']);
			$user = getFromRequest($_POST,'user');
			$pass = getFromRequest($_POST,'pass');
            
			if($this->validate_login($user, $pass))
			{
                
				redirect("index.php?obj=index");
				exit;
			}
			else 
				$smarty->assign("invalidLogin", 1);
		}
		
		$smarty->display($this->tplName);		
	}
	
	/**
	 * Get user permiss
	 *
	 * @access: public
	 * @return: array
	*/
	function getUserPermiss($user_id)
	{
		$list = array();
		$db = &SDatabase::getInstance();
		$q="SELECT smenuadmin_id FROM userpermiss WHERE $this->idName = $user_id";
        $db->setQuery($q);
        $result=$db->loadAssocList();	

        foreach($result as $key=>$record)  
	    	$list[$record["smenuadmin_id"]]=1;
	    
	    return $list;
	}
	
	/**
	 * Get user permiss
	 *
	 * @access: public
	 * @return: array
	*/
	function addUserLogs()
	{
		$ip = getIP();
	    $sessid = getSessId();
        $db = &SDatabase::getInstance();
	    $_SESSION[SESS_IDX]['UL']['login_time']=_dtGetTime(); 	    
	    $datetime = date("Y-m-d H:i:s", $_SESSION[SESS_IDX]['UL']['login_time']);
	    
	    $q="
	    	INSERT INTO 
	    		userlog
			SET
	    		userlog_userid		= '{$_SESSION[SESS_IDX]['UL']['user_userid']}',
	    		userlog_name		= '{$_SESSION[SESS_IDX]['UL']['user_name']}',
	    		userlog_datelogin	= '$datetime',
	    		userlog_ip 			= '$ip',
	    		userlog_sessid		= '$sessid'
	    ";
		$db->setQuery($q);
        $db->query();	
		
		//===> set flag connected for user (ON-LINE)
		$q="UPDATE {$this->tableName} SET user_connected=1 WHERE $this->idName = ".$_SESSION[SESS_IDX]['UL']['user_id'];
		$db->setQuery($q);
        $db->query();
		//<===
	}
	
	/**
	 * Logout
	 *
	 * @access: public
	 * @return: null
	*/
	function logout()
	{		
        session_regenerate_id();
		unset($_SESSION[SESS_IDX]);
        
		redirect("index.php?act=logout");
		
		exit;		
	}
	
	/**
	 * Page myaccount (update form)
	 *
	 * @access: public
	 * @return: null
	*/
	function page_myaccount()
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		objInitVar($this, "admin/{$this->tableName}_act.tpl", "page_myaccount", "", "", "", "");
			
		$form_act = array();
        
		if(isset($_SESSION[SESS_IDX]['UL']['user_id']) && $_SESSION[SESS_IDX]['UL']['user_id']!='')
			$id = $_SESSION[SESS_IDX]['UL']['user_id'];
		else
		{
			echo "Invalid ID";
			exit;
		}
				 
		$q="SELECT 
				$this->tableName.*
			FROM 
				$this->tableName
			WHERE 
				$this->tableName.$this->idName = $id
		";
        
        $db->setQuery($q);
		$form_act = $db->loadAssocList();
		$form_act[0]['pass_dec'] = base64_decode($form_act[0]['user_pass']);
		
		$form_act[0]['act'] = 'upd';
		
		//get permiss
		$vectPermiss = $this->getPermiss($id); 
        
        $q="SELECT * FROM user_group WHERE group_status = 1";
        $db->setQuery($q);
        $user_group = $db->loadAssocList();
        $smarty->assign("user_group", $user_group);
		//assign variables					
		$smarty->assign("form_act", htmlArrayFilter($form_act[0]));
		$smarty->assign("idName", $this->idName);
		$smarty->assign("vectPermiss", $vectPermiss);
		
		//display template
		$smarty->display($this->tplName);		
	}
	
	/**
	 * Page act (add/upd form)
	 *
	 * @access: public
	 * @return: null
	*/
	function page_act()
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		objInitVar($this, "admin/{$this->tableName}_act.tpl", "page_act", "", "", "", "");
				
		$form_act = array();
		
        if(isset($_GET['act']) && 'upd'==$_GET['act'])
        {			
        	if(isset($_GET[$this->idName]) && $_GET[$this->idName]!='')
				$id = $_GET[$this->idName];
			elseif(isset($_SESSION[SESS_IDX]['UL'][$this->idName]) && $_SESSION[SESS_IDX]['UL'][$this->idName]!='')
				$id = $_SESSION[SESS_IDX]['UL'][$this->idName];
			else
			{
				echo "Invalid ID";
				exit;
			}
			
			$q="SELECT 
					$this->tableName.* 
				FROM 
					$this->tableName
				WHERE 
					$this->tableName.$this->idName = $id
			";
			$db->setQuery($q);
		    $form_act = $db->loadAssocList();
			$form_act[0]['pass_dec'] = base64_decode($form_act[0]['user_pass']);
			
			$form_act[0]['act'] = 'upd';
			
			//get permiss
			$vectPermiss = $this->getPermiss($id);
        }
		else
		{
			if(isset($_POST))
				$form_act[0]=$_POST;
							
			$form_act[0]['act'] = 'add';
			
			//get permiss
			$vectPermiss = $this->getPermiss();
		}
        
        $q="SELECT * FROM user_group WHERE group_status = 1";
        $db->setQuery($q);
        $user_group = $db->loadAssocList();
        $smarty->assign("user_group", $user_group);
			//printr($vectPermiss);					
		$smarty->assign("form_act", $form_act[0]);		
		$smarty->assign("vectPermiss", $vectPermiss);
		
		$smarty->display($this->tplName);		
	}
	
	/**
	 * Get User Permiss 
	 *
	 * @access: public
	 * @return: array
	*/
	function getPermiss($user_id='')
	{
        $db = &SDatabase::getInstance();
		$list = array(); 
		$separator=" > ";
		
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

		if($user_id!="")
		{
			//===> PERMISS ASSIGNED
			$q="SELECT
	                {$smTableName}.{$smIdName}, {$smTableName}.{$smName}, 
					{$mTableName}.{$mName}
	            FROM
	            	{$this->tableUPermiss}, {$smTableName}, {$mTableName}
	            WHERE
	            	{$this->tableUPermiss}.{$smIdName} = {$smTableName}.{$smIdName} AND 
					{$smTableName}.{$mIdName} = {$mTableName}.{$mIdName} AND 			
	                {$smTableName}.{$smStatusName} = 1 AND 
					{$mTableName}.{$mStatusName} = 1 AND 
					{$this->tableUPermiss}.{$this->idName} = $user_id
	            ORDER BY 
					{$mTableName}.{$mPriorityName}, {$mTableName}.{$mIdName}, 
					{$smTableName}.{$smPriorityName}, {$smTableName}.{$smIdName}
	        ";
            
            $i=0;
            $db->setQuery($q);
            $result=$db->loadAssocList();
			
            foreach($result as $key=>$record)
			{
				$list["assigned"]["id"][$i]=$record[$smIdName];			
				$list["assigned"]["name"][$i]=$record[$mName].$separator.$record[$smName];
				$i++;
			}
			//<===
			
			//===> PERMISS AVAILABLE
			$q="SELECT
				  	{$smTableName}.{$smIdName}, {$smTableName}.{$smName}, 
					{$mTableName}.{$mName}
				FROM
					{$smTableName}
					LEFT JOIN {$this->tableUPermiss} ON {$this->tableUPermiss}.{$smIdName} = {$smTableName}.{$smIdName} AND {$this->tableUPermiss}.{$this->idName}=$user_id,
					{$mTableName}
				WHERE
					{$this->tableUPermiss}.{$this->idName} IS NULL AND 
					{$smTableName}.{$mIdName} = {$mTableName}.{$mIdName} AND 
					{$smTableName}.{$smStatusName} = 1 AND 
					{$mTableName}.{$mStatusName} = 1
				GROUP BY 
					{$smTableName}.{$smIdName}
				ORDER BY 
					{$mTableName}.{$mPriorityName}, {$mTableName}.{$mIdName}, 
					{$smTableName}.{$smPriorityName}, {$smTableName}.{$smIdName}
	        ";
            $i=0;
            
            
            $db->setQuery($q);
            $result=$db->loadAssocList();
            foreach($result as $key=>$record)
			{
				$list["available"]["id"][$i]=$record[$smIdName];			
				$list["available"]["name"][$i]=$record[$mName].$separator.$record[$smName];	
				$i++;
			}
			//<===
		}		
		else 
		{
            
			//===> ALL PERMISS AVAILABLE
		
			$q="SELECT
	                {$smTableName}.{$smIdName}, {$smTableName}.{$smName}, 
					{$mTableName}.{$mName}
	            FROM
	                {$smTableName}, {$mTableName}
	            WHERE					
					{$smTableName}.{$mIdName} = {$mTableName}.{$mIdName} AND 			
	                {$smTableName}.{$smStatusName} = 1 AND 
					{$mTableName}.{$mStatusName} = 1
	            ORDER BY 
					{$mTableName}.{$mPriorityName}, {$mTableName}.{$mIdName}, 
					{$smTableName}.{$smPriorityName}, {$smTableName}.{$smIdName}
	        ";
            
            $i=0;
                
            $db->setQuery($q);
            $result=$db->loadAssocList();
            foreach($result as $key=>$record)
			{
				$list["available"]["id"][$i]=$record[$smIdName];
				$list["available"]["name"][$i]=$record[$mName].$separator.$record[$smName];
				$i++;
			}
			//<===
		}
		return $list;
	}
	
	/**
	 * Add/Update operation
	 *
	 * @access: public
	 * @return: array
	*/
	function add_upd()
    {    	
    	$q_tmp = objPrepareTableFields($this);
    	$db = &SDatabase::getInstance();
    	//$pass = _sqlEscValue($_POST["user_pass"]);
        $pass = getFromRequest($_POST, "user_pass");
    	$pass = md5($pass); // "ENCODE('{$pass}', '".PASS_ENCODE."')";
		
		//$user_userid = _sqlEscValue($_POST['user_userid']);
		$user_userid = getFromRequest($_POST, "user_userid");
        
		$datec = _dtGetDate();
        
        if(isset($_POST[$this->idName]) && $_POST[$this->idName] == "") //add
        {
            
            //if(!_sqlCheckFieldDuplicate($this->tableName, 'user_userid', $user_userid, '', ''))
            $q="SELECT * FROM $this->tableName WHERE user_userid = '{$user_userid}' LIMIT 0,1";
            $db->setQuery($q);
            $exists = $db->loadAssoc();
        	if($exists)
            {            	
            	global $smarty;
            	$msgErr = "Add Operation - Error!\\nThe User Id \"{$user_userid}\" is already in use!";
            	$smarty->assign("msgErr", $msgErr);
            	$this->page_act();
            	return ;
            }
            
        	$q="INSERT INTO 
                    $this->tableName
                SET            		
                    user_datec 		= '$datec',
                    user_owner = {$_SESSION[SESS_IDX]['UL'][$this->idName]},
                    user_pass  = '$pass',
                    $q_tmp
            ";
            $db->setQuery($q);
            $db->query();
            $id=$db->insertid();
        }
        elseif(isset($_POST[$this->idName]) && $_POST[$this->idName] != "") //update
        {
            $id=$_POST[$this->idName];
            
            
            //if(!_sqlCheckFieldDuplicate($this->tableName, 'user_userid', $user_userid, $this->idName, $id))
            $q="SELECT * FROM $this->tableName WHERE user_userid = '{$user_userid}' AND $this->idName=$id LIMIT 0,1";
            $db->setQuery($q);
            $exists = $db->loadAssoc();
        	if(!$exists)
            {            	
            	global $smarty;            
            	$msgErr = "Edit Operation - Error!\\nThe User Id \"{$_POST['user_userid']}\" is already in use!";
            	$smarty->assign("msgErr", $msgErr);
            	$_GET['act']='upd';	$_GET[$this->idName] = $id;
            	$this->page_act();
            	return ;
            }
            
            $q="UPDATE
                    $this->tableName
                SET
                  	$q_tmp,
                  	user_pass  = '$pass'
                WHERE
                    $this->idName = '$id'
            ";
            $db->setQuery($q);
            $db->query();
            
            //delete cookie
            if(isset($_POST["del_autologin"]) && $_POST["del_autologin"]==1)
			{
				setcookie(VAR_COOKIE_USER, '', -3600);
				setcookie(VAR_COOKIE_PASS, '', -3600);
				
				unset($_COOKIE[VAR_COOKIE_USER]);
				unset($_COOKIE[VAR_COOKIE_PASS]);								
			}
        }
        
        //Delete old permiss
        $q="DELETE FROM {$this->tableUPermiss} WHERE $this->idName = $id";
        $db->setQuery($q);
        $db->query();
		
		//===>Add new permiss
		if (strtolower($user_userid)=='root')
		{
			//update status active for user root
			$q="UPDATE $this->tableName SET user_active=1 WHERE user_userid='root'";
			$db->setQuery($q);
            $db->query();
			
			$vectPermiss=$this->getPermiss();
			
			$q 		= "INSERT INTO $this->tableUPermiss ($this->idName, smenuadmin_id) VALUES ";
			$q_body = "";
			
			for($i=0;$i<count($vectPermiss["available"]["id"]);$i++)
				$q_body .= "($id, '{$vectPermiss["available"]["id"][$i]}'), ";						
		}
		else
		{
			$temp=explode(' ', $_POST['shared_str']);
			$q		= "INSERT INTO $this->tableUPermiss ($this->idName, smenuadmin_id) VALUES ";
            $q_body = "";
                        
			foreach($temp as $k=>$v)
			 	$q_body .= "($id, $v), ";
		}
		
		if($q_body != '')
		{
			$q .= substr($q_body, 0, (strlen($q_body)-2));
			$db->setQuery($q);
            $db->query();
		}
		//<===				
		
		//===>update session & cookies
		if($id==$_SESSION[SESS_IDX]['UL'][$this->idName])
        {
            
			//update session
			unset($_SESSION[SESS_IDX]);
            $q="SELECT * FROM $this->tableName WHERE $this->idName = '$id' LIMIT 0,1";
            $db->setQuery($q);
            $record=$db->loadAssoc();
			//$record = _sqlGetRowContent($this->tableName, $this->idName, $id);
			$_SESSION[SESS_IDX]['UL']=array();
			$_SESSION[SESS_IDX]['UL']=$record;	
            $_SESSION[SESS_IDX]['UL']['auth']=1;
            unset($_SESSION[SESS_IDX]['UL']['user_pass']);
			$_SESSION[SESS_IDX]['UL']['permiss']=$this->getUserPermiss($id);
			//<===
			
			//update cockie
			if(isset($_COOKIE[VAR_COOKIE_USER])  && isset($_COOKIE[VAR_COOKIE_PASS]))
			{
                $q="SELECT user_pass FROM $this->tableName WHERE $this->idName='$id' LIMIT 0,1";
				$db->setQuery($q);
                $pass_encoded=$db->loadAssoc();
                //$pass_encoded = _sqlGetFieldContent($this->tableName, 'user_pass', $this->idName, $id);
	        	
	        	setcookie(VAR_COOKIE_USER, $user_userid);
	        	setcookie(VAR_COOKIE_PASS, $pass_encoded);	
			}
        }				        
		//<===
		
		if(isset($_SESSION[SESS_IDX]['UL'][$this->idName]) && $_SESSION[SESS_IDX]['UL'][$this->idName] == $id)
        	redirect("index.php?obj={$_GET["obj"]}&action=page_myaccount");
        else 
        	redirect("index.php?obj={$_GET["obj"]}&action=page_act&{$this->idName}={$id}&act=upd");
    }
    
    
    /**
	 * Page list/edit
	 *
	 * @access: public
	 * @return: null
	*/
    function page_list()
	{
		global $smarty;
		
		objInitVar($this, "admin/{$this->tableName}_list.tpl", "user_list", "page_list", "", "", "");
		
		if(isset($_POST["act"]) && $_POST["act"]=="delete")        
			$this->deleteMItems();
			
		$smarty->assign("listRecords", $this->getList(1));
		$smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);		
		
		$smarty->display($this->tplName);
	}
	
	/**
	 * Get Users List
	 *
	 * @access: public
	 * @return: array
	*/
    function getList($withPaging)
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);
		
		$sqlSort = newSort($this->moduleName, "$this->tableName.$this->idName", 'ASC');
        $sqlLimit = paging($this->moduleName, $this->pagingAction);
		
		$myC = $_SESSION[SESS_IDX][$this->moduleName];		
		$vectSearch = (isset($myC["search"]) ? $myC["search"] : array());
		$sqlWhere="";
		if(count($vectSearch) > 0)
		{
			foreach($vectSearch as $key=>$value)
			{
				switch($key)
				{
					case "user_name":
						$sqlWhere .= "AND $this->tableName.user_name LIKE '%$value%' ";
						break;
					case "user_userid":
						$sqlWhere .= "AND $this->tableName.user_userid LIKE '%$value%' ";
						break;
					case "user_email":
						$sqlWhere .= "AND $this->tableName.user_email LIKE '%$value%' ";
						break;
					case "user_active":
						$sqlWhere .= "AND $this->tableName.$this->flagName = '$value' ";
						break;
				}
			}
		}
		if($withPaging==1)
		{
			$q="SELECT 
				{$this->tableName}.{$this->idName}
				FROM 
					$this->tableName 
				WHERE 
					1
					$sqlWhere
			";
			$db->setQuery($q);
            $db->query();
			$_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$db->getNumRows();
		}
		else 
			$sqlLimit = "";
		
		$q="SELECT 
				$this->tableName.*  
			FROM 
				$this->tableName 
			WHERE 
				1
				$sqlWhere
			ORDER BY $sqlSort
			$sqlLimit
		";
		$db->setQuery($q);
        $result=$db->loadAssocList();
		$list = array(); $i=-1;
        
        foreach($result as $key=>$record)
		{
			$i++;
			
			$list[$i]=$record;
            $q="SELECT user_userid FROM $this->tableName WHERE $this->idName='{$record['user_owner']}' LIMIT 0,1";
            $db->setQuery($q);
            $list[$i]['owner'] = $db->loadAssoc();
			//$list[$i]['owner'] = _sqlGetFieldContent($this->tableName, 'user_userid', $this->idName, $record['user_owner']);
			
			$list[$i]["permiss"]=$this->getPermiss($record[$this->idName]);
            
			$list[$i]["no_permiss"]=count($list[$i]["permiss"]["assigned"]["id"]);
			
			//$list[$i]["pass_dec"] = base64_decode($record['user_pass']);
			
			$list[$i]["del_op"] = $this->verifyDel($record[$this->idName], $record['user_owner']);
            
			$list[$i]["flag_op"] = $this->verifySwitch($record[$this->idName], $record['user_owner']);
			$list[$i]["upd_op"] = $this->verifyUpd($record[$this->idName], $record['user_owner']);
            
		}
				
		return $list;
	}
		
	/**
	 * Verify user for remove operation
	 *
	 * @access: public
	 * @return: bool
	*/
	function verifyDel($id, $owner)
	{	
        $db = &SDatabase::getInstance();
		//check if is user root
		//$user_userid = _sqlGetFieldContent($this->tableName, 'user_userid', $this->idName, $id);		
        $q="SELECT user_userid FROM $this->tableName WHERE $this->idName=$id LIMIT 0,1";
        $db->setQuery($q);
        $user_userid = $db->loadAssoc();
		
		if(strtolower(array_shift($user_userid))=='root')
			return false;
		if((strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='root' || strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='ciprian.susanu') && $_SESSION[SESS_IDX]['UL'][$this->idName]!=$id)
			return true;
		if($_SESSION[SESS_IDX]['UL'][$this->idName] == $id)
			return false;
		if($_SESSION[SESS_IDX]['UL'][$this->idName] != $owner)
			return false;
		
		return true;
	}
	
	/**
	 * Verify user switch status
	 *
	 * @access: public
	 * @return: bool
	*/
	function verifySwitch($id, $owner)
	{			
		if((strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='root' || strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='ciprian.susanu') && $_SESSION[SESS_IDX]['UL'][$this->idName]!=$id)
			return true;
		elseif($_SESSION[SESS_IDX]['UL'][$this->idName] == $id)
			return false;
		elseif($_SESSION[SESS_IDX]['UL'][$this->idName] == $owner)
			return true;
		else 
			return false;
	}
	
	/**
	 * Verify user update
	 *
	 * @access: public
	 * @return: bool
	*/
	function verifyUpd($id, $owner)
	{
		if(strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='root' || strtolower($_SESSION[SESS_IDX]['UL']['user_userid'])=='ciprian.susanu')
			return true;
		elseif($_SESSION[SESS_IDX]['UL'][$this->idName] == $id)
			return true;
		elseif($_SESSION[SESS_IDX]['UL'][$this->idName] == $owner)
			return true;
		else 
			return false;
	}
	
	/**
	 * Delte items (multiple)
	 *
	 * @access: public
	 * @return: null
	*/
    function deleteMItems()
	{
		if(isset($_POST["act"]) && $_POST["act"]=="delete")
        {
            if(isset($_POST["ids"]))
            {    				
				foreach($_POST["ids"] as $k=>$v)
                {
					$this->deleteItem($v);
                }
			}
        }	
	}
	
	/**
	 * Delte item
	 *
	 * @access: public
	 * @return: null
	*/
	function deleteItem($id)
	{		
		//$user_owner = _sqlGetFieldContent($this->tableName, 'user_owner', $this->idName, $id);
		$db = &SDatabase::getInstance();
        $q="SELECT user_owner FROM $this->tableName WHERE $this->idName=$id LIMIT 0,1";
        $db->setQuery($q);
        $user_owner = $db->loadAssoc();
        
		if($this->verifyDel($id, $user_owner))
		{			
			//delete from users_permiss table
			//_sqlDel('userpermiss', $this->idName, $id);
            $q="DELETE FROM userpermiss WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
			
			//delete from users table
			//_sqlDel($this->tableName, $this->idName, $id);
            $q="DELETE FROM {$this->tableName} WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
		}
	}
	
	/**
	 * Switch status
	 *
	 * @access: public
	 * @return: null
	*/
    function switch_status()
	{
        $db = &SDatabase::getInstance();
		if(isset($_GET[$this->idName]) && $_GET[$this->idName]!='')
        {
            //$owner = _sqlGetFieldContent($this->tableName, 'user_owner', $this->idName, $_GET[$this->idName]);
            $q="SELECT user_owner FROM $this->tableName WHERE $this->idName=$id LIMIT 0,1";
            $db->setQuery($q);
            $owner = $db->loadAssoc();
        	
            if($this->verifySwitch($_GET[$this->idName], $owner))
            {
                $q="UPDATE $this->tableName SET {$this->flagName}=({$this->flagName}+1)%2 WHERE $this->idName='{$_GET[$this->idName]}'";
                $db->setQuery($q);
                $db->query();
            }
        }
	}
	
	/**
	 * Page Logs
	 *
	 * @access: public
	 * @return: null
	*/
	function page_logs()
    {
        global $smarty;
        $db = &SDatabase::getInstance();
        objInitVar($this, "admin/{$this->tableName}log.tpl", "user_log", "page_logs", "userlog", "userlog_id", "");
                
        if(isset($_POST["act"]) && $_POST["act"]=="delete")
        {
            if(isset($_POST["ids"]))
            {    				
				foreach($_POST["ids"] as $k=>$v)
                {
					//_sqlDel($this->tableName, $this->idName, $v);
                    $q="DELETE FROM {$this->tableName} WHERE $this->idName = $v";
                    $db->setQuery($q);
                    $db->query();
                }
			}
        }
        
        if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);		
         
        $sqlSort = newSort($this->moduleName, "$this->tableName.userlog_datelogin", "DESC");
        
        $sqlLimit = paging($this->moduleName, $this->pagingAction);
		
		$myC = $_SESSION[SESS_IDX][$this->moduleName];
		$vectSearch = (isset($myC["search"]) ? $myC["search"] : array());
		
		$sqlWhere="";
		if(count($vectSearch) > 0)
		{
			foreach($vectSearch as $key=>$value)
			{
				switch($key)
				{
					case "userlog_name":
						$sqlWhere.="AND $this->tableName.userlog_name LIKE '%$value%' ";
						break;
					case "userlog_userid":
						$sqlWhere.="AND $this->tableName.userlog_userid LIKE '%$value%' ";
						break;
					case "userlog_ip":
						$sqlWhere.="AND $this->tableName.userlog_ip LIKE '%$value%' ";
						break;
					case "date11":
						$sqlWhere.=" AND $this->tableName.userlog_datelogin >= '".$value." 00:00' ";
						break;
					case "date12":						
						$sqlWhere.=" AND $this->tableName.userlog_datelogin <= '".$value." 23:59' ";						
						break;
				}
			}
		}
        
        $q="SELECT
				$this->tableName.$this->idName
			FROM
				$this->tableName
			WHERE
				1
				$sqlWhere
		";
		$db->setQuery($q);
        $db->query();
        $_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$db->getNumRows();
		
		$q="SELECT 
				$this->tableName.*
			FROM 
				$this->tableName
			WHERE
				1
				$sqlWhere
			ORDER BY 
                $sqlSort
			$sqlLimit
		";
        
		$db->setQuery($q);
        $result=$db->loadAssocList();
		$list = array(); $i=-1;
        foreach($result as $key=>$record)
        {
			$list[]=$record;
        }
				
		$smarty->assign("listRecords", $list);
		$smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);
		
        $smarty->display($this->tplName);
    }
    
    /**
	 * Autolog
	 *
	 * @access: public
	 * @return: null
	*/
	function autolog()
	{
		if(isset($_GET['param']))
		{
			$param=unserialize(gzinflate(base64_decode($_GET['param'])));

			if ($this->validate_login($param['user'], $param['pass'])) 
			{
				header("Location:".$param['link']);
			}
  			else 
  			{
  				header("Location:index.php?obj=user&action=page_login");
  			}
		}
	}
}
?>