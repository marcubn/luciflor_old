<?php
/**
 * 
 * Mails Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );

class mails extends adminModule
{
	public $moduleTitle  = "Email Notifications";
    public $moduleName   = "mails";
    public $tableName  	 = "mails";
    public $idName     	 = "mails_id";
    public $priorityName = "mails_ordering";
    public $tplName		 = "mails";
    public $methodsMap   = array("preview");

    function initFields(){
    
    	$this->columnsList[]  = new adminField("mails_to", "To", "text", 1, 1);
    	$this->columnsList[]  = new adminField("mails_subject", "Subject", "text", 2, 1);
    	$this->columnsList[]  = new adminField("mails_type", "Type", "text", 3, 2);
    	$this->columnsList[]  = new adminField("mails_about", "Info", "text", 4, 2);
    	$this->columnsList[]  = new adminField("mails_content", "Content", "editor", null, 2);
    	$this->columnsList[]  = new adminField("mails_title", "Title", "text", 5, 2);
    	$this->columnsList[]  = new adminField("mails_ishtml", "HTML", "switch", 6, 2);
    	$this->columnsList[]  = new adminField("mails_status", "Status", "switch", 7, 2);
    	$this->columnsList[]  = new adminField("mails_emails", "Emails", "text", 8, 2);
    
    }
    
    function preview(){
    	error_reporting(0);
        require_once(INCLUDE_DIR."/mail_notify.php");
        if(isset($_GET["mails_id"]) && (int)$_GET["mails_id"] >0){
            SMailNotify::renderMail($_GET["mails_id"]);
        }
        
        exit;        
    }
	

}
?>