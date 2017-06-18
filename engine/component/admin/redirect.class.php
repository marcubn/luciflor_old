<?php
/**
 * REDIRECT Class Implementation
 * */

require_once(LIB_DIR . "/admin/module.php" );

class redirect extends adminModule
{
	var $tplName 		  = "redirect";
    var $moduleName 	  = "redirect";
    var $pagingAction 	  = "page_list";
    
    var $tableName 		  = "redirect";
    var $idName 		  = "redirect_id";
	var $flagName 		  = "redirect_status";
	var $priorityName	  = "redirect_date";
	var $moduleTitle	  = "Redirects";

	var $exportParams     = array(	
        "redirect_from"=>"Redirect From",
        "redirect_to"=>"Redirect To",
        "redirect_date"=>"Data"												
    );
    
    function initFields(){
    
    	$this->columnsList[]  = new adminField("redirect_from", "Redirect from", "text", 1, 1);
    	$this->columnsList[]  = new adminField("redirect_to", "Redirect to", "text", 2, 2);
    	$this->columnsList[]  = new adminField("redirect_status", "Status", "switch", 3, 3);
    
    }
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function redirect($action="")
	{
	   if( !$this->adminModule($action) )
        {
            switch($action)
            {  		
	           	case "add_upd":
					$this->add_upd();
	            	$ok = true;
	      		break;
	      		
	           	case "links":
	           		$this->links();	
	           	break;
               
	           default:
                $this->page_list();
                break;
	       }
           
	   }
       
	}
    
    function loadTable(){
	
        require_once ( LIB_DIR."db/db_table.php" );
        $this->table = &STable::tableInit($this->tableName,$this->idName);
        
    }
	
	/**
	 * Get Items List
	 *
	 * @access: public
	 * @return: array
	*/
    function getList()
	{
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
					case "redirect_from":
						$sqlWhere .= "AND {$this->tableName}.redirect_from LIKE '%$value%' ";
						break;
					case "redirect_to":
						$sqlWhere .= "AND {$this->tableName}.redirect_to LIKE '%$value%' ";
						break;
					 case "redirect_data1":
                        $sqlWhere .= "AND {$this->tableName}.redirect_date >= '".$value."' ";
                    break;
                    case "redirect_data2":
                        $sqlWhere .= "AND {$this->tableName}.redirect_date <= '".$value."' ";
                    break;
					case "redirect_status":
						$sqlWhere .= "AND {$this->tableName}.{$this->flagName} = '$value' ";
						break;
				}
			}
		}
		
		$q="SELECT 
				{$this->tableName}.{$this->idName}
			FROM 
				{$this->tableName}
			WHERE 
				1
				{$sqlWhere}
		";
		$db->setQuery($q);
        $db->query();
        $_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$db->getNumRows();
		
		$q="SELECT 				
				{$this->tableName}.*
			FROM 
				{$this->tableName}
			WHERE 			
				1
				{$sqlWhere}
			ORDER BY 
				{$sqlSort}
            LIMIT
                0,10
		";
        /*{$sqlLimit}*/
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
	 * Add/Update operation
	 * 
	 * @access: public
	 * @return: void
	*/
	function add_upd()
    {    	
        $db = &SDatabase::getInstance();
    	if( isset($_POST['act']) && ($_POST['act']=="add" || $_POST['act']=="upd") )
        {
            if( isset($_POST[$this->idName]) && $_POST[$this->idName]!="" ){
                $this->table->load($_POST[$this->idName]);
            }
            
            if(!isset($_POST['redirect_status']))
            	$_POST['redirect_status']=0;
			if($_POST['act']=="add")
            	$_POST['redirect_date'] = date("Y-m-d H:i:s",time());
            $from=getFromRequest($_POST, "redirect_from",0);
            
            $q = "SELECT $fieldName FROM $tableName WHERE $fieldName = '$fieldValue' LIMIT 0,1";
            $db->setQuery($q);
            $exist=$db->loadAssoc();
            
            if($exist && $_POST['act']=="add")
            {
            	systemMessage::addMessage("Exista deja un redirect de la adresa: <br/>".$from,2);
            	redirect($_SERVER["HTTP_REFERER"]);	
            }
            else 
            {
                
	            $this->table->bind($_POST);
	            $ok = $this->table->check();
	            if( $ok ){
	                $ok = $this->table->store();
	                $id = $this->table->{$this->idName};
	            }
	            
	            if( $ok===true )
	                systemMessage::addMessage("Saved");
	                    
	            else
	                systemMessage::addMessage(implode("; ",$ok));    
       		}
	        if( isset($id) && isset($_POST['backToEditForm']) )
	        	//redirect to update from
	       		redirect("index.php?obj={$_GET["obj"]}&action=page_act&{$this->idName}={$id}&act=upd");
	       	else
	       		//redirect to update from
	       		redirect("index.php?obj={$_GET["obj"]}&action=page_list");
        }
        else 
        	redirect("index.php?obj=index&action=page_invalid");
    }
    
    
    function links()
    {
    	$content = file_get_contents(ROOT_HOST."sitemap.xml");
    	//echo $content;
    		
		preg_match_all('/<loc>(.*)<\/loc>/', $content, $links);
		
		$ok=0;
		foreach ($links[1] as $key=>$item)
		{
			if(!_sqlCheckFieldExist("redirect", "redirect_from", $item))
			{
				$q="INSERT INTO redirect SET redirect_from = '{$item}', redirect_date=NOW()";
				_sqlQuery($q);
			}
			else 
				$ok++;
			
		}
    	echo "Done.<br/> Duplicate - ".$ok;
    }
		
}
?>