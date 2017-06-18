<?php
/**
 * 
 * New model sample Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class newsletter extends adminModule
{
    public $moduleName = "newsletter";
    public $tableName  = "newsletter";
    public $moduleTitle  = "Newsletter Subscribers";
    public $idName     = "id";
    /**
     * @deprecated
     */
    public $flagName   = "status";
    public $priorityName = "date";
    
    var $exportParams         = array("email"=>"Email", "name"=>"Name", "ip"=>"Ip", "date"=>"Data", "status"=>"Status");
    
    function initFields(){
        
        //$this->columnsList[]  = new adminField("name", "Name", "text", 1, 1);
        $this->columnsList[]  = new adminField("email", "Email", "text", 2, 1);
        $this->columnsList[]  = new adminField("ip", "IP", "text", 3, 1);
        $this->columnsList[]  = new adminField("date", "Date", "text", 4, 1);
        $this->columnsList[]  = new adminField("status", "Status", "switch", 5, 1);
        
        
    }

}
?>