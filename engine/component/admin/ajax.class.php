<?php

class ajax
{
    var $tplName          = "";
    var $moduleName 	  = "";
    var $pagingAction 	  = "";

    var $tableName        = "";
    var $idName           = "";
    var $flagName         = "";
    var $priorityName	  = "";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: FlorinM
	*/
	function ajax($action="")
	{
            switch($action)
            {
      		
                case "ajx_gallery_select":
                    $this->ajx_gallery_select();
                break;
        }
    
    }

	
    function ajx_gallery_select(){
        $db = &SDatabase::getInstance();
        $term = getFromRequest($_REQUEST,"term",null);
        
        $sqlWhere = "";
        if( $term!="" )
            $sqlWhere = " AND gallery_name LIKE '%{$term}%'";
            
        
        $db->setQuery("SELECT * from uplgallery WHERE gallery_status = 1 {$sqlWhere} ORDER BY gallery_ordering ASC LIMIT 0,10 ");
        
        $skulist = $db->loadObjectList();
        
        $li = count($skulist);
        $i = 0;
        $sku_array = array();
        foreach($skulist as $k => $item){
            
            $sku_array[$i] = array( "label"=>$item->gallery_name,"value"=>$item->gallery_name );
            $i++;
        }
        $response = "" . json_encode($sku_array) . "";  
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo $response;exit;
        break;
    }
	
}