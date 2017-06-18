<?php
/**
 * 
 * New model sample Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class sample_new extends adminModule
{
    public $moduleName = "sample_new";
    public $tableName  = "sample_new";
    public $idName     = "sample_id";
    /**
     * @deprecated
     */
    public $flagName   = "sample_status";
    public $priorityName = "sample_ordine";
    
    function initFields(){
        
        $this->columnsList[]  = new adminField("sample_title", "Title", "text", 1, 1);
        $this->columnsList[]  = new adminField("sample_hits", "Hits", "text", 2, 1);
        $this->columnsList[]  = new adminField("sample_text", "Description", "editor", null, 2);
        
    }

}
?>