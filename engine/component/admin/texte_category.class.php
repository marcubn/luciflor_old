<?php
/**
 * 
 * Text Category Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class texte_category extends adminModule
{
	public $moduleTitle  = "Texts category";
    public $moduleName   = "texte_category";
    public $tableName  	 = "texte_category";
    public $idName     	 = "texte_cat_id";
    public $priorityName = "texte_cat_id";
    //public $tplName		 = "texte_category";
    public $langName      = "lang";
    public $methodsMap   = array("preview");

    function initFields(){

        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 1);    
    	$this->columnsList[]  = new adminField("texte_cat_name", "Name", "text", 2, 2);
    	$this->columnsList[]  = new adminField("texte_cat_alias", "Alias", "text", 3, 3);
    
    }

    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM texte_category GROUP BY texte_cat_id";
        return $q;
    }
		
}
?>