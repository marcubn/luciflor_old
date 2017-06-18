<?php
//############//
//# Tiny MCE #//
//############//

class mce
{
	var $tplName="tpl_utile/mce.tpl";
	
    var $tableName ="";
    var $fieldName ="";
    var $idName ="";
    var $idValue ="";
    
    var $elemField ="";
    
	function mce($action="")
	{
		switch($action)
        {
			case "page":
				$this->page();
				break;
        }
	} 
	
	function page()
	{
		global $smarty;
        $this->tableName = isset($_GET["db_table"]) ? $_GET["db_table"] : "";        
        $this->fieldName = isset($_GET["db_field"]) ? $_GET["db_field"] : "";
                
        $this->elemField = isset($_GET["elem_field"]) ? $_GET["elem_field"] : $this->fieldName;
        
        
        $this->idName 	= isset($_GET["db_id_name"]) ? $_GET["db_id_name"] : "";
        $this->idValue = isset($_GET["db_id_value"]) ? $_GET["db_id_value"] : "";
           
        if($this->tableName!='' && $this->fieldName!='' && $this->idName!='' && $this->idValue!='')
        {            
            if(isset($_GET["db_id_name2"]) && $_GET["db_id_name2"]!='')
            {
            	$id_name2  = $_GET["db_id_name2"];
            	$id_value2 = $_GET["db_id_value2"];
            	
            	$sqlWhere = " AND $id_name2 = '$id_value2'";
            }
            else 
            	$sqlWhere = "";
            
        	$q = "
            	SELECT 
            		$this->fieldName
            	FROM
            		$this->tableName
            	WHERE
            		$this->idName = '$this->idValue'
            		$sqlWhere
            ";
        	$result = _sqlQuery($q);
        	$record=$result->fetchRow(DB_FETCHMODE_ASSOC);        	
        	$text = $record[$this->fieldName];
        }
        
        $text = (isset($text) ? $text : '');

       	$smarty->assign('text', $text);
        $smarty->assign('elemField', $this->elemField);
        $smarty->display($this->tplName);
	}
}
?>