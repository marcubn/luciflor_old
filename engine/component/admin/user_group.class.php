<?php
/**
 * 
 * User Group Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class user_group extends adminModule
{
    public $moduleName = "user_group";
    public $tableName  = "user_group";
    public $idName     = "group_id";
    public $moduleTitle = "User Group";
    public $priorityName = "group_id";
    
    function initFields(){
        
        $this->columnsList[]  = new adminField("group_name", "Name", "text", 1, 1);
        $this->columnsList[]  = new adminField("group_status", "Active", "switch", 2, 2);
        
    }

}
?>