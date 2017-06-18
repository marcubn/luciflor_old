<?php
/**
 * 
 * Pages Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class pages extends adminModule
{
	public $moduleTitle  = "Pages";
    public $moduleName   = "pages";
    public $tableName  	 = "pages";
    public $idName     	 = "page_id";
    public $priorityName = "page_id";
    //public $tplName		 = "pages";
    public $langName      = "lang";
    public $methodsMap   = array("preview");

    function initFields(){

        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 1, array("width"=>'200px'));
        $this->columnsList[]  = new adminField("page_category", "Category", "list", null, 2, array("query"=>"SELECT pages_cat_id as value, pages_cat_name as name FROM pages_category", "width"=>'200px'));    
        $this->columnsList[]  = new adminField("page_titlu", "Title", "text", 3, 3);
    	$this->columnsList[]  = new adminField("page_template", "Template", "text", 5, 5);
    	$this->columnsList[]  = new adminField("page_home_text", "Home text", "editor", null, 6);
    	$this->columnsList[]  = new adminField("page_text", "Text", "editor", null, 6);
    	$this->columnsList[]  = new adminField("page_alias", "Alias", "text", null, 6);
    	$this->columnsList[]  = new adminField("page_ordine", "Order", "text", null, 5);
        $this->columnsList[]  = new adminField("page_status", "Status", "switch", 6, 6);
    
    }

    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM pages GROUP BY page_id";
        return $q;
    }

}
?>