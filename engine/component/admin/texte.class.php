<?php
/**
 * 
 * Texte Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class texte extends adminModule
{
	public $moduleTitle  = "Texts";
    public $moduleName   = "texte";
    public $tableName  	 = "texte";
    public $idName     	 = "text_id";
    public $priorityName = "text_id";
    //public $tplName		 = "texte";
    public $langName      = "lang";
    public $methodsMap   = array("preview");

    function initFields(){

        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 1);
        $this->columnsList[]  = new adminField("text_category", "Category", "list", null, 2, array("query"=>"SELECT texte_cat_id as value, texte_cat_name as name FROM texte_category"));    
        $this->columnsList[]  = new adminField("text_titlu", "Title", "text", 3, 3);
    	$this->columnsList[]  = new adminField("text_text", "Text", "editor", null, 6);
    	$this->columnsList[]  = new adminField("text_alias", "Alias", "text", null, 6);
        $this->columnsList[]  = new adminField("text_status", "Status", "switch", 6, 6);
    
    }

    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM texte GROUP BY text_id";
        return $q;
    }
}
?>