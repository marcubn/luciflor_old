<?php
/**
 * AJAX Operations class
 */
if( !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
{
	//exit("Invalid AJAX request");
}

class ajax
{
	var $tplName 		  = "";
	var $moduleName 	  = "";
	var $pagingAction 	  = "";

	var $tableName 		  = "";
	var $idName 		  = "";
	var $flagName 		  = "";
	var $priorityName	  = "";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function ajax($action="")
	{
		switch($action)
	  	{
            case "change_map":
                $this->change_map();
                break;
			break;
	  	}
	}
    
    function change_map(){
		$db=SDatabase::getInstance();
		$city = $db->quote($_POST['city']);
		$db->setQuery("SELECT * FROM locatii JOIN orase ON locatii_oras = orase_id WHERE locatii_status = 1 AND orase_status = 1 AND orase_nume = $city ORDER BY orase_ordine");
		$locatii = $db->loadAssocList();
		foreach($locatii as $key=>$locatie) {
			$locatii[$key]["html"] = '<table style="font-size:12px;  font:Trebuchet MS !important;"  class="html_map"><tr><td><a target="_parent" style=" text-decoration:none; color:#F48600; font-size:14px; font-weight:bold;"></a></td> <td>  <table><tr><td><a style=" text-decoration:none; color:#F48600; font-size:14px; font-weight:bold;">'.$locatii[$key]["locatii_nume"].'</a></td></tr><tr><td>'.$locatii[$key]['locatii_adresa'].'</td></tr></table></td></tr></table>';
		}
		echo json_encode($locatii);
    }
}
?>