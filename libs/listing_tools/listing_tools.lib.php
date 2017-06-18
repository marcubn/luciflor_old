<?php

//#########################################################################//
//# Utile listing, search and pagination
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 22.01.2004
//#########################################################################//

/**
 * Pagination 
 *
 * @parameters: $moduleName = name of module for session register | $action = url action case | $noRows = no rows per page
 * @access: public
 * @return: query limit string
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function paging($moduleName, $action, $noRows=0)
{
    $_pgNo = "pgNo";
	$_noRowsDisplayed = "noRowsDisplayed";
	$_action = "action";
	
	if(0 == $noRows)
	{
		if(defined("NO_ROWS_DISPLAYED"))
			$noRows = NO_ROWS_DISPLAYED;
		else 
			$noRows = 10;
	}
	
	if(!isset($_SESSION[SESS_IDX][$moduleName]['paging']))
    	$_SESSION[SESS_IDX][$moduleName]['paging'] = array();
    
	$myC = $_SESSION[SESS_IDX][$moduleName]['paging'];
	$myC[$_action] = $action;
	
	if(isset($_GET[$_pgNo]) && is_numeric($_GET[$_pgNo]) && $_GET[$_pgNo] > 0)
	{
		if(isset($_POST["act"]) && $_POST["act"]=="delete")
			$myC[$_pgNo]=($_GET[$_pgNo]>1) ? ($_GET[$_pgNo]-1):1;
		else
			$myC[$_pgNo]=$_GET[$_pgNo];
	}
    elseif(!isset($myC[$_pgNo]) || !is_numeric($myC[$_pgNo]) || $myC[$_pgNo] <= 0)
        $myC[$_pgNo]=1;
    
    if(isset($_POST[$_noRowsDisplayed]))
    {
        if(is_numeric($_POST[$_noRowsDisplayed]) && $_POST[$_noRowsDisplayed] > 0)
    		$myC[$_noRowsDisplayed]=$_POST[$_noRowsDisplayed];
    	else 
    		$myC[$_noRowsDisplayed]=$noRows;
    	$myC[$_pgNo]=1;
    }
    //else
    elseif(!isset($myC[$_noRowsDisplayed]) || !is_numeric($myC[$_noRowsDisplayed]) || $myC[$_noRowsDisplayed] <= 0)
    {
    	$myC[$_noRowsDisplayed]=$noRows;
    }
        
    $_SESSION[SESS_IDX][$moduleName]['paging']=$myC;
    
    $pgNo=$myC[$_pgNo];
    $noRowsDisplayed=$myC[$_noRowsDisplayed];
    return " LIMIT ".($pgNo-1)*$noRowsDisplayed.", ".$noRowsDisplayed;
}

/**
 * Store in session search variables
 *
 * @parameters: $moduleName = name of module for session register
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function search($moduleName)
{
    $db = &SDatabase::getInstance();
	if(isset($_SESSION[SESS_IDX][$moduleName]["search"]))
		unset($_SESSION[SESS_IDX][$moduleName]["search"]);
		
	if(isset($_SESSION[SESS_IDX][$moduleName]['paging']))
		unset($_SESSION[SESS_IDX][$moduleName]['paging']['pgNo']);
	
	if(isset($_REQUEST['pgNo'])) $_REQUEST['pgNo']=1;
		
    $_SESSION[SESS_IDX][$moduleName]["search"] = array();
	
    $myC=array();
	
	foreach($_POST as $key=>$value)
	{
		if(!is_array($value) && $value!='')
			$myC[$key] = strip_tags(mysqli_real_escape_string($db->_resource,$value));
		elseif(is_array($value))
			$myC[$key]=$value;
	}
	
	/*
	echo "<pre>";
	print_r($myC);
	echo "</pre>"; exit;
	*/
    
	$_SESSION[SESS_IDX][$moduleName]["search"]=$myC;
}

/**
 * Query sort function
 *
 * @parameters: $moduleName = name of module for session register | $fiedSort = name field sorted | $senswSort = ASC or DESC
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function newSort($moduleName, $fieldSort='', $senseSort='ASC')
{
    $_fieldSort = 'field_sort';
    $_senseSort = 'sense_sort';
	
	if(!isset($_SESSION[SESS_IDX][$moduleName]["sort"]))
        $_SESSION[SESS_IDX][$moduleName]["sort"]=array();
	
    $myC=$_SESSION[SESS_IDX][$moduleName]["sort"];
    
    if(isset($_REQUEST[$_fieldSort]) && "" != $_REQUEST[$_fieldSort])
    {
		if(isset($myC[$_fieldSort]) && $myC[$_fieldSort]==$_REQUEST[$_fieldSort])
		{
			if($myC[$_senseSort]=="ASC")		
				$myC[$_senseSort]="DESC";
			elseif($fieldSort==$_REQUEST[$_fieldSort] && $myC[$_senseSort]=="DESC") 
				$myC[$_senseSort]="ASC";
			else
			{
				unset($myC);
			}
		}
		else 
		{
			$myC[$_fieldSort]=$_REQUEST[$_fieldSort];
			$myC[$_senseSort]="ASC";
		}
		
		if(isset($_REQUEST[$_senseSort]) && ($_REQUEST[$_senseSort]=="ASC" || $_REQUEST[$_senseSort]=="DESC") )
    	{
    		$myC[$_senseSort]=$_REQUEST[$_senseSort];
    	}
    }
    
    $tmp="";
    if($fieldSort!="" && !isset($myC[$_fieldSort]))
    {
    	$myC[$_fieldSort]=$fieldSort;
    	$myC[$_senseSort]=$senseSort;
    }
    elseif($fieldSort!="" && isset($myC[$_fieldSort]))
    {
    	if($myC[$_fieldSort]!=$fieldSort)
    		$tmp = ", $fieldSort $senseSort ";
    }
    
    $_SESSION[SESS_IDX][$moduleName]["sort"]=$myC;
    
    
    $fieldSort = isset($myC[$_fieldSort]) ? $myC[$_fieldSort] : "";
    $senseSort = isset($myC[$_senseSort]) ? $myC[$_senseSort] : "";
    
    if("" != $fieldSort && "" != $senseSort)
        return " $fieldSort $senseSort $tmp";
    else 
        return "";
}
/**
 * Query sort function
 *
 * @parameters: $moduleName = name of module for session register | $fiedSort = name field sorted | $senswSort = ASC or DESC
 * @access: public
 * @return: null
 * @date: 08.01.2008 (dd.mm.YYYY)
*/
function dataGridSort($moduleName)
{
	$ifieldSort = "field_sort";
	$isenseSort = "sense_sort";
	$qString="";
	
	if(!isset($_SESSION[SESS_IDX][$moduleName]["sort"]))
        $_SESSION[SESS_IDX][$moduleName]["sort"]=array();
	
    $myC=$_SESSION[SESS_IDX][$moduleName]["sort"];

    if(isset($_GET["sort"]) && $_GET["sort"]!="")
    {
    	$newSort = $_GET["sort"];
    	if(isset($myC[$ifieldSort]) && $myC[$ifieldSort]!="")
    	{
    		$myC[$ifieldSort] = $newSort;
    		$sense = $myC[$isenseSort];
    		if($sense == "ASC")
    			$myC[$isenseSort] = "DESC";
    		else 
    			$myC[$isenseSort] = "ASC";
    	}
    	else {
    		$myC[$ifieldSort] = $newSort;
    		$myC[$isenseSort] = "ASC";
    	}
    }
   
    $sort = $myC[$ifieldSort];
    $sense = $myC[$isenseSort];
    $qString = " $sort  $sense";
    
    $_SESSION[SESS_IDX][$moduleName]["sort"]=$myC;
    return  $qString;
	
}

?>