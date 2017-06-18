<?php
//#########//
//# Index #//
//#########//

class index
{
	var $tplName 		= "";
    var $moduleName 	= "";
    var $pagingAction 	= "";

    var $tableName 		= "";
    var $idName 		= "";
	var $flagName 		= "";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function index($action="")
	{		
		switch($action)
	  	{
			case "page_index":				
				objInitVar($this, "admin/index.tpl", "", "", "", "", "");
				$this->page_index();
      		 	break;
      		 	
      		case "page_invalid":
      			objInitVar($this, "tpl_utile/page_invalid.tpl", "", "", "", "", "");				
				global $smarty;
				$smarty->display($this->tplName);
      		 	break;
      		 	 	
      		default:
      			objInitVar($this, "admin/index.tpl", "", "", "", "", "");
				$this->page_index();
      		 	break;     
            
	  	}
        
	}
	
	/**
	 * Index page
	 *
	 * @access: public
	 * @return: null
	*/
	function page_index()
	{
        
		global $smarty;
		$db = &SDatabase::getInstance();
        $q="SELECT count(id) as nr FROM contact WHERE status = 0";
        $db->setQuery($q);
        $intermed=$db->loadAssoc();
        $numar=$intermed['nr'];
        $smarty->assign("numar", $numar);

		$smarty->display($this->tplName);
	}
}
?>