<?php
/**
 * 
 * Produse Brand Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class produse_brand extends adminModule
{
	public $moduleTitle  = "Produse Brand";
    public $moduleName   = "produse_brand";
    public $tableName  	 = "produse_brand";
    public $idName     	 = "id";
    public $priorityName = "status";
    public $langName     = "lang";

    function initFields(){
    
        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 0);
    	$this->columnsList[]  = new adminField("nume", "Name", "text", 1, 1);
    	$this->columnsList[]  = new adminField("ordine", "Ordine", "text", null, 3);
    	$this->columnsList[]  = new adminField("status", "Status", "switch", 5, 5);
    
    }
    
    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM produse_brand GROUP BY id";
        return $q;
    }
    
}
?>