<?php
//##################//
//# Sub-Menu Admin #//
//##################//

class smenuadmin
{
	var $tplName 		  = "smenuadmin";
    var $moduleName 	  = "smenuadmin";
    var $pagingAction 	  = "page";

    var $tableName 		  = "smenuadmin";
    var $idName 		  = "smenuadmin_id";
	var $flagName 		  = "smenuadmin_active";
	var $priorityName	  = "smenuadmin_priority";
	
	var $tableFields	  = array("menuadmin_id", "smenuadmin_name", "smenuadmin_link", "smenuadmin_priority", "smenuadmin_master", "smenuadmin_active");
	
	var $joinTableName	  = "menuadmin";
	var $joinIdName		  = "menuadmin_id";
	var $joinFieldName	  = "menuadmin_name";
	var $joinPriorityName = "menuadmin_priority";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function smenuadmin($action="")
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
	*/
	function page()
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		objInitVar($this, "admin/{$this->tableName}.tpl", "smenuadmin", "page", "", "", "");
		
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
        
        $q="SELECT {$this->joinIdName}, {$this->joinFieldName} FROM {$this->joinTableName} ORDER BY {$this->joinPriorityName}";
        $db->setQuery($q);
        $result=$db->loadAssocList();
        $ret=array();
        foreach($result as $key=>$record)
	    {
	        $ret[0][]=$record[$this->joinIdName];
	        $ret[1][]=$record[$this->joinFieldName];
	    }        
        
		$smarty->assign("{$this->joinTableName}List", $ret);
		$smarty->display($this->tplName);		
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
        if(isset($_POST['act']) && $_POST['act'] == "add") //add
        {
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
					case "menuadmin_id":
						$sqlWhere .= "AND $this->tableName.menuadmin_id = '$value' ";
						break;
					case "smenuadmin_name":
						$sqlWhere .= "AND $this->tableName.smenuadmin_name LIKE '%$value%' ";
						break;
					case "smenuadmin_active":
						$sqlWhere .= "AND $this->tableName.smenuadmin_active = '$value' ";
						break;
					case "menuadmin_active":
						$sqlWhere .= "AND $this->joinTableName.menuadmin_active = '$value' ";
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
					{$this->tableName}, {$this->joinTableName}
				WHERE 
					{$this->tableName}.{$this->joinIdName}={$this->joinTableName}.{$this->joinIdName}
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
				{$this->tableName}.*,
				{$this->joinTableName}.{$this->joinFieldName}, 
				{$this->joinTableName}.{$this->joinPriorityName},
				{$this->joinTableName}.{$this->joinTableName}_active
			FROM 
				{$this->tableName}, {$this->joinTableName}
			WHERE 			
				{$this->tableName}.{$this->joinIdName}={$this->joinTableName}.{$this->joinIdName}
				$sqlWhere
			ORDER BY 
				{$this->joinTableName}.{$this->joinPriorityName},
				{$this->joinTableName}.{$this->joinIdName},
				{$this->tableName}.{$this->priorityName},
				{$this->tableName}.{$this->idName}
			$sqlLimit
		";
        
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
	*/
	function verifyDel($id)
	{
		return true;
	}
	
    /**
	 * Delete items (multiple)
	 *
	 * @access: public
	 * @return: null
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
	*/
	function deleteItem($id)
	{		
        $db = &SDatabase::getInstance();
		if($this->verifyDel($id))
		{
			//delete userpermiss
            $q="DELETE FROM userpermiss WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
            
			
			//delete item
            $q="DELETE FROM {$this->tableName} WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
		}
	}
}
?>