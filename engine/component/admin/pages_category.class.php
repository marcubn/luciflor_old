<?php
/**
 * 
 * Pages Category Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class pages_category extends adminModule
{

	public $moduleTitle  = "Pages category";
    public $moduleName   = "pages_category";
    public $tableName  	 = "pages_category";
    public $idName     	 = "pages_cat_id";
    public $priorityName = "pages_cat_id";
    //public $tplName		 = "pages_category";
    public $langName      = "lang";
    public $methodsMap   = array("preview");

    function initFields(){

        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 1);    
    	$this->columnsList[]  = new adminField("pages_cat_name", "Name", "text", 2, 2);
    	$this->columnsList[]  = new adminField("pages_cat_alias", "Alias", "text", 3, 3);
    
    }

    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM pages_category GROUP BY pages_cat_id";
        return $q;
    }

}
?>