<?php

require_once(LIB_DIR . "/admin/module.php" );


class seomanager extends adminModule
{
	var $tplName 		  = "seomanager";
    var $moduleName 	  = "seomanager";
    var $pagingAction 	  = "page_list";
    
    var $tableName 		  = "seotable";
    var $idName 		  = "seo_id";
	var $flagName 		  = "seo_active";
	var $priorityName	  = "seo_id";
	var $priorityFilter	  = "";
	
	var $tableFields	  = array("seo_title", "seo_description", "seo_keywords", "seo_h", "seo_url", "seo_active");
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function seomanager($action="")
	{
        if( !$this->adminModule($action) )
        {
            switch($action)
            {  		
                case "page_act":
                    $this->page_act();
                    break;

                case "add_upd":
                    $this->add_upd();
                    break;

                case "update_order":
                    $this->update_order();
                    break;

                default:
                    $this->page_list();
                    break;
            }
        }
	}
	
	/**
	 * Page act (add/edit form)
	 *
	 * @access: public
	 * @return: null
	*/
	function page_act()
	{
		global $smarty;
		$db = &SDatabase::getInstance();
		objInitVar($this, "admin/{$this->tplName}_act.tpl", "page_act", "", "", "", "");
				
		$form_act = array();
        if(isset($_GET['act']) && 'upd'==$_GET['act'] && isset($_GET[$this->idName]) && $_GET[$this->idName]!='')
        {
			$id = $_GET[$this->idName];
			$q="SELECT * FROM $this->tableName WHERE $this->idName = $id";
            $db->setQuery($q);
            $form_act=$db->loadAssoc();
            
            $url = $form_act["seo_url"];
            $form_act["seo_url"]=str_replace(ROOT_HOST,"",$form_act["seo_url"]);
            if($form_act["seo_url"]!=$url)
                $domain = ROOT_HOST;
            else{
                $form_act["seo_url"]=str_replace(ROOT_HOST,"",$form_act["seo_url"]);
                if($form_act["seo_url"]!=$url)
                    $domain = ROOT_HOST;
            }
            
        	$form_act['domain'] = $domain;
        	$form_act['act'] = 'upd';
        }
		else
		{
			if(isset($_POST))
				$form_act=$_POST;			
			$form_act['act'] = 'add';
		}
		
        
        $q="SELECT min($this->priorityName) min FROM $this->tableName LIMIT 0,1";
        $db->setQuery($q);
        $min = $db->loadAssoc();
        if($min)
          $minOrder=$min;

        $q="SELECT max($this->priorityName) max FROM $this->tableName LIMIT 0,1";
        $db->setQuery($q);
        $max = $db->loadAssoc();
        if($max)
          $maxOrder=$max;

        if ($maxOrder!=0 || $minOrder!=0) { 
            $maxOrder=$maxOrder++;
            $minOrder=$minOrder--;

        }
		$smarty->assign("minOrder", $minOrder);
        $smarty->assign("maxOrder", $maxOrder);
		
		//assign variables					
		$smarty->assign("form_act", htmlArrayFilter($form_act));
		$smarty->assign("idName", $this->idName);
		
		//dispaly smarty template
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
    	$db = &SDatabase::getInstance();
    	if(isset($_POST['act']) && ($_POST['act']=="add" || $_POST['act']=="upd"))
        {
            $_POST["seo_url"] = str_replace(array(ROOT_HOST),"",$_POST["seo_url"]);
            $_POST["seo_url"]=$_POST["seo_domain"].$_POST["seo_url"];

            $q_tmp = objPrepareTableFields($this);

            if($_POST['act']=="add")
            {
                $q="INSERT INTO 
                        $this->tableName
                    SET 
                        $q_tmp
                ";
                $db->setQuery($q);
                $db->query();
                $id=$db->insertid();		          
            }
            elseif(isset($_POST[$this->idName]) && $_POST[$this->idName]!="") //update
            {
                $id=$_POST[$this->idName];

                $q="UPDATE
                        $this->tableName
                    SET
                        $q_tmp
                    WHERE
                        $this->idName = $id
                ";
                $db->setQuery($q);
                $db->query();
            }

            //exit;

            if(isset($_POST['backToEditForm']))
                //redirect to update from
                redirect("index.php?obj={$_GET["obj"]}&action=page_act&{$this->idName}={$id}&act=upd");
            else
                //redirect to update from
                redirect("index.php?obj={$_GET["obj"]}&action=page_list");
        }
        else 
        	redirect("index.php?obj=index&action=page_invalid");
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
					case "seo_active":
						$sqlWhere .= "AND {$this->tableName}.{$this->flagName} = '$value' ";
						break;

					case "seo_title":
						$sqlWhere .= "AND {$this->tableName}.seo_title LIKE '%$value%' ";
						break;

					case "seo_url":
						$sqlWhere .= "AND {$this->tableName}.seo_url LIKE '%$value%' ";
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
			{$sqlLimit}
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
}
?>