<?php
//#########################################################################//
//# Menu Admin
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 9.07.2005
//#########################################################################//

class menuadmin
{
	var $tplName 		  = "menuadmin";
    var $moduleName 	  = "menuadmin";
    var $pagingAction 	  = "page";

    var $tableName 		  = "menuadmin";
    var $idName 		  = "menuadmin_id";
	var $flagName 		  = "menuadmin_active";
	var $priorityName	  = "menuadmin_priority";
	
	var $tableFields	  = array("menuadmin_name", "menuadmin_priority", "menuadmin_active");
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
	function menuadmin($action="")
	{
		global $smarty;
		
		switch($action)
	  	{
	  		case "page":	  			
				$this->page();
      		 	break;
      		 	
      		case "add_upd":
				$this->add_upd();
      		 	break;

      		 case "delete_items":
				$this->deleteMItems();
      		 	break;
      		 	
      		case "switch":
      			if(isset($_GET[$this->idName]) && $_GET[$this->idName]!='' && isset($_GET['fieldName']) && $_GET['fieldName']!='')
                {
                    $db = &SDatabase::getInstance();
                    $q="UPDATE $this->tableName SET {$_GET['fieldName']}=({$_GET['fieldName']}+1)%2 WHERE $this->idName='{$_GET[$this->idName]}'";
                    $db->setQuery($q);
                    $db->query();
                }
					
      		 	$this->page();
				break;
	  	}
	}
		
	/**
	 * Page (list/add/upd form)
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
	function page()
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		objInitVar($this, "admin/{$this->tableName}.tpl", "menuadmin", "page", "", "", "");
		
		//delete multiple items
		if(isset($_POST["act"]) && $_POST["act"]=="delete")
			$this->deleteMItems();
		
		$form_act = array();
		
        if(isset($_GET['act']) && 'upd'==$_GET['act'] && isset($_GET[$this->idName]) && $_GET[$this->idName]!='')
        {		
			$id = $_GET[$this->idName];
            $q="SELECT * FROM $this->tableName WHERE $this->idName = $id";
            $db->setQuery($q);
            $form_act=$db->loadAssoc();
        	$form_act['act'] = 'upd';     
        }
		else
		{
			if(isset($_POST['act']) && $_POST['act']=='add')
				$form_act=$_POST;
			$form_act['act'] = 'add';
		}
		
		$smarty->assign("form_act", htmlArrayFilter($form_act));		
		$smarty->assign("listRecords", $this->getList(1));
		$smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);
		
		$smarty->display($this->tplName);		
	}		
	
	/**
	 * Add/Update operation
	 *
	 * @access: public
	 * @return: array
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
	function add_upd()
    {    	
        $db = &SDatabase::getInstance();
    	
        $q_tmp = objPrepareTableFields($this);
    	
        if(isset($_POST['act']) && $_POST['act'] == "add") //add
        {
            $q="SELECT * FROM $this->tableName WHERE menuadmin_name = '{$_POST['menuadmin_name']}'";
            $db->setQuery($q);
            $exists = $db->loadAssoc();
        	if($exists)
            {            	
            	global $smarty;
            	$msgErr = "Add Operation - Error!\\nThe field Name \"{$_POST['menuadmin_name']}\" is already in use!";
            	$smarty->assign("msgErr", $msgErr);
            	$this->page();
            	return ;
            }
            
            //get new id
            
            $q = "SELECT MAX({$this->idName}) as max FROM $this->tableName ORDER BY {$this->idName} DESC LIMIT 0,1";
            $db->setQuery($q);
            $record=$db->loadAssoc();

            if($record)
                $id = $record['max']+1;
            else 
                $id = 1;
            
        	$q="INSERT INTO
                    $this->tableName
                SET
        			$this->idName='$id',
                	$q_tmp
            ";
            $db->setQuery($q);
            $db->query();
        }
        elseif(isset($_POST[$this->idName]) && $_POST[$this->idName] != "upd") //update
        {
            //$id = _sqlEscValue($_POST[$this->idName]);
            $id = getFromRequest($_POST, $this->idName);
            
            //if(!_sqlCheckFieldDuplicate($this->tableName, "menuadmin_name", $_POST['menuadmin_name'], $this->idName, $id))
            $q="SELECT * FROM $this->tableName WHERE menuadmin_name = '{$_POST['menuadmin_name']}' AND $this->idName='{$id}' LIMIT 0,1";
            $db->setQuery($q);
            $exists = $db->loadAssoc();
        	if(!$exists)
            {            	
            	global $smarty;            
            	$msgErr = "Edit Operation - Error!\\nThe Name \"{$_POST['menuadmin_name']}\" is already in use!";
            	$smarty->assign("msgErr", $msgErr);
            	$_GET['act']='upd';	$_GET[$this->idName] = $id;
            	$this->page();
            	return ;
            }
            
            $q="UPDATE
                    $this->tableName
                SET
                  	$q_tmp
                WHERE
                    $this->idName = '$id'
            ";
            $db->setQuery($q);
            $db->query();
        }
        
        //reset session menu
        unset($_SESSION[SESS_IDX]['menu']);
        
        //exit;
       	if(isset($_POST['backToEditForm']))
        	//redirect to update from
       		redirect("index.php?obj={$_GET["obj"]}&action=page&$this->idName={$id}&act=upd");
       	else
       		//redirect to update from
       		redirect("index.php?obj={$_GET["obj"]}&action=page");
    }
	
    /**
	 * Get Items List
	 *
	 * @parameters: $withPaging (1 for paging)
	 * @access: public
	 * @return: array
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 05.04.2005 (dd.mm.YYYY)
	*/
    function getList($withPaging)
	{
		global $smarty;
        $db = &SDatabase::getInstance();
		if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);
		
		$sqlSort = newSort($this->moduleName, "{$this->tableName}.{$this->priorityName}", 'ASC');
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
					case "menuadmin_name":
						$sqlWhere .= "AND $this->tableName.menuadmin_name LIKE '%$value%' ";
						break;
					case "menuadmin_active":
						$sqlWhere .= "AND $this->tableName.menuadmin_active = '$value' ";
						break;
				}
			}
		}
		
		if($withPaging==1)
		{
			$q="				
				SELECT 
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
		
		$q="
			SELECT 				
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
		//$result=_sqlQuery($q);
        //while($record=$result->fetchRow(DB_FETCHMODE_ASSOC))
        
		$db->setQuery($q);
        $result=$db->loadAssocList();
		$list = array(); $i=-1;
        foreach($result as $key=>$record)
		{
			$i++;
			$list[$i]=$record;
			$list[$i]["del_op"] = $this->verifyDel($record[$this->idName]);
		}
		
		return $list;
	}
	
	/**
	 * Verify item for remove operation
	 *
	 * @parameters: $id = item id
	 * @access: public
	 * @return: bool
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
	function verifyDel($id)
	{			
        $db = &SDatabase::getInstance();
        $q="SELECT * FROM smenuadmin WHERE $this->idName='{$id}'";
        $db->setQuery($q);
        $exists = $db->loadAssoc();
        if($exists)
			return false;
		
		return true;
	}
	
    /**
	 * Delete items (multiple)
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
    function deleteMItems()
	{
		$ids=explode(",", $_GET['ids']);
        if(isset($ids))
        {
			foreach($ids as $k=>$v)
            {
				$this->deleteItem($v);
            }
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * Delete item
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	*/
	function deleteItem($id)
	{		
		$db = &SDatabase::getInstance();
		if($this->verifyDel($id))
		{
			//delete item			
			$q="DELETE FROM {$this->tableName} WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
		}
	}
}
?>