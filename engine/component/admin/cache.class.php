<?php
require_once(INCLUDE_DIR.'/models/cache.php');
class cache
{
    var $tplName          = "";
    var $moduleName 	  = "";
    var $pagingAction 	  = "";

    var $tableName        = "";
    var $idName           = "";
    var $flagName         = "";
    var $priorityName	  = "";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: FlorinM
	*/
	function cache($action="")
	{
            switch($action)
            {
      		
                case "home":
                    $this->home();
                break;
        }
    
    }

	
    function home()
    {
            global $smarty;
            objInitVar($this, "admin/cache.tpl", "cache", "cache", "", "", "");
            $mesaj	= '';
            if(isset($_GET['item']))
            {
                if($_GET['item']=='photos')
                {
                    $mesaj = SCache::purgePhotosCache();
                    systemMessage::addMessage($mesaj." poze.",3);
                    redirect("index.php?obj=cache&action=home");

                }
                if($_GET['item']=='files')
                {
                    $mesaj = SCache::purgeSmartyCache();
                    systemMessage::addMessage($mesaj." fisiere.",3);
                    redirect("index.php?obj=cache&action=home");
                }
            }
            $smarty->display($this->tplName);
    }
	
}