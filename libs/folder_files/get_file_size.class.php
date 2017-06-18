<?php
//#########################################################################//
//# Iframe Get file size
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 30.03.2005
//#########################################################################//
class get_file_size
{    
	var $tplName = "tpl_utile/ifr_utile.tpl";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 23.02.2005 (dd.mm.YYYY)
	*/	
	function get_file_size($action="")
	{		
		switch($action)
		{
			case "ifr":
			  	$this->ifr();
	    	 	break;
	    	 	
	    	case "get":
	    	  	$this->get();
	    	 	break;
	    	 	
	    	default :
	    		$this->ifr();			  
	    	 	break;
		}
	}
	
	/**
	 * Get
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 23.02.2005 (dd.mm.YYYY)
	*/	
	function get()
	{
		global $smarty;		
		
		if(isset($_GET["elemFile"]) && isset($_GET["elemSize"]))
		{		
			if(is_uploaded_file($_FILES[$_GET["elemFile"]]['tmp_name']))
			{
				$size=$_FILES[$_GET["elemFile"]]['size'];
				
				$js_code = "				
					var elemSize = findParentDOM('{$_GET["elemSize"]}', 0);
					elemSize.value = '{$size}';
				";
				
				$smarty->assign("js_code", $js_code);
			}
		}
				
		$smarty->display($this->tplName);
	}
	
	/**
	 * Default
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 23.02.2005 (dd.mm.YYYY)
	*/	
	function ifr()
	{
		global $smarty;		
		$smarty->display($this->tplName);
	}
	
}
?>



