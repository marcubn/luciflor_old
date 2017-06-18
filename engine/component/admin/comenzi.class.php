<?php
/**
 * 
 * Simple Class Implementation
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class comenzi extends adminModule
{
	var $tplName 		  = "comanda";
    var $moduleName 	  = "comanda";
    var $pagingAction 	  = "page_list";
    
    var $tableName 		  = "comanda";
    var $idName 		  = "comanda_id";
	var $flagName 		  = "comanda_status";
	var $priorityName	  = "comanda_status";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: PSorin (sorinporumboiu@gmail.com)
	*/
	function comenzi($action="")
	{
	   if( !$this->adminModule($action) ){
	       
           switch($action){
           		
           		case "status_s":
      				$this->status_s();
				break;
           		case "page_personalizate":
      				$this->page_personalizate();
				break;
           		
	           default:
                $this->page_list();
                break;
	       }
           
	   }
       
	}
    
    function loadTable(){
	
        require_once ( LIB_DIR."db/db_table.php" );
        $this->table = &STable::tableInit($this->tableName,$this->idName,null);
        
    }
	
	/**
	 * Get Items List
	 *
	 * @access: public
	 * @return: array
	 * @author: PSorin (sorinporumboiu@gmail.com)
	*/
    function getList()
	{
		if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);
		
		$sqlSort = newSort($this->moduleName, "{$this->tableName}.{$this->priorityName}", 'ASC');
		$sqlSort = newSort($this->moduleName, "{$this->tableName}.comanda_data", 'DESC');
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
					case "comanda_data1":
						$sqlWhere .= "AND {$this->tableName}.comanda_data >= '$value' ";
						break;
					case "comanda_data2":
						$sqlWhere .= "AND {$this->tableName}.comanda_data <= '$value' ";
						break;
					case "comanda_nume":
						$sqlWhere .= "AND {$this->tableName}.comanda_nume LIKE '%$value%' ";
						break;
					case "comanda_status":
						$sqlWhere .= "AND {$this->tableName}.{$this->flagName} = '$value' ";
						break;
				}
			}
		}
		
		$q="SELECT 
				{$this->tableName}.{$this->idName}
			FROM 
				{$this->tableName}
			LEFT JOIN diamante ON diamant_id = comanda_produs
			WHERE 
				1
				{$sqlWhere}
		";
		$result=_sqlQuery($q);
		$_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$result->numRows();
		
		$q="SELECT 				
				{$this->tableName}.*,diamante.*
			FROM 
				{$this->tableName}
			LEFT JOIN diamante ON diamant_id = comanda_produs
			WHERE 			
				1
				{$sqlWhere}
			ORDER BY 
				{$sqlSort}
			{$sqlLimit}
		";
		$result=_sqlQuery($q);
		
		$list = array(); $i=-1;
		while($record=$result->fetchRow(DB_FETCHMODE_ASSOC))
		{
			$i++;
			$list[$i]=$record;
			$list[$i]["del_op"] = $this->verifyDel($record[$this->idName]);
		}
		
		return $list;
	}

		/**
	 * Verify item for delete operation
	 *
	 * @access: public
	 * @return: bool
	 * @author: PSorin (sorinporumboiu@gmail.com)
	*/
	function verifyDel($id)
	{
		return false;
	}
	
	function status_s()
	{
		if(isset($_GET[$this->idName]) && $_GET[$this->idName]!='' && isset($_GET['fieldName']) && $_GET['fieldName']!=''){
			systemMessage::addMessage("Status Switched!");
			_sqlFieldSwitch($this->tableName, $_GET['fieldName'], $this->idName, $_GET[$this->idName]);
			$q="UPDATE comanda set comanda_data_confirmarii= NOW() WHERE comanda_id = '{$_GET[$this->idName]}'";
			_sqlQuery($q);
		}
	 	redirect("index.php?obj={$_GET['obj']}&action=page_list");
	}	



	/**
	 * Get Items List
	 *
	 * @access: public
	 * @return: array
	 * @author: PSorin (sorinporumboiu@gmail.com)
	*/
    function getList_personalizate()
	{
		if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);
		
		$sqlSort = newSort($this->moduleName, "comanda_personalizata.{$this->priorityName}", 'ASC');
		$sqlSort = newSort($this->moduleName, "comanda_personalizata.comanda_data", 'DESC');
        $sqlLimit = "";
		
		$myC = $_SESSION[SESS_IDX][$this->moduleName];		
		$vectSearch = (isset($myC["search"]) ? $myC["search"] : array());
		$sqlWhere="";
		if(count($vectSearch) > 0)
		{
			foreach($vectSearch as $key=>$value)
			{
				switch($key)
				{
					case "comanda_data1":
						$sqlWhere .= "AND comanda_personalizata.comanda_data >= '$value' ";
						break;
					case "comanda_data2":
						$sqlWhere .= "AND comanda_personalizata.comanda_data <= '$value' ";
						break;
					case "comanda_nume":
						$sqlWhere .= "AND comanda_personalizata.comanda_nume LIKE '%$value%' ";
						break;
					case "comanda_status":
						$sqlWhere .= "AND comanda_personalizata.{$this->flagName} = '$value' ";
						break;
				}
			}
		}
		
		$q="SELECT 
				comanda_personalizata.{$this->idName}
			FROM 
				comanda_personalizata
			WHERE 
				1
				{$sqlWhere}
		";
		$result=_sqlQuery($q);
		$_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$result->numRows();
		
		$q="SELECT 				
				comanda_personalizata.*
			FROM 
				comanda_personalizata
			WHERE 			
				1
				{$sqlWhere}
			ORDER BY 
				{$sqlSort}
			{$sqlLimit}
		";
		$result=_sqlQuery($q);
		
		$list = array(); $i=-1;
		while($record=$result->fetchRow(DB_FETCHMODE_ASSOC))
		{
			$i++;
			$list[$i]=$record;
			$list[$i]["del_op"] = $this->verifyDel($record[$this->idName]);
		}
		
		return $list;
	}

	 /**
	 * Page list/edit
	 *
	 * @access: public
	 * @return: null
	 * @author: PSorin (sorinporumboiu@gmail.com)
	*/
    function page_personalizate()
	{
		global $smarty;
		
		objInitVar($this, "admin/{$this->tplName}_personalizata.tpl", "personalizata", "page_personalizate", "", "", "");
		
		//delete multiple items
		if(isset($_POST["act"]) && $_POST["act"]=="delete")
			$this->deleteMItems();
			
		$smarty->assign("recList", $this->getList_personalizate());
		$smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);
		
		$smarty->display($this->tplName);
	}	
}
?>