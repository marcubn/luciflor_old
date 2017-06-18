<?php
/**
 * 
 * Contact Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class contact extends adminModule
{
	public $moduleTitle  = "Contact";
    public $moduleName   = "contact";
    public $tableName  	 = "contact";
    public $idName     	 = "id";
    public $priorityName = "status";

    function initFields(){

    	$this->columnsList[]  = new adminField("nume", "Name", "text", 1, 1);
        $this->columnsList[]  = new adminField("email", "Email", "text", 2, 2);
        $this->columnsList[]  = new adminField("telefon", "Telefon", "text", 3, 3);
        $this->columnsList[]  = new adminField("subiect", "Subiect", "text", 4, 4);
        $this->columnsList[]  = new adminField("mesaj", "Mesaj", "editor", null, 5);
        $this->columnsList[]  = new adminField("ip", "Ip", "text", null, 6);
    	$this->columnsList[]  = new adminField("data", "Data", "text", null, 7);
    	$this->columnsList[]  = new adminField("status", "Status", "switch", 8, 8);
    
    }
    
}
?>