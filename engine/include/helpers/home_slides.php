<?php
/**
 * 
 * Helper Class for home slides
 * 
 * @author: Bogdan Marcu.
 * 
 */
class Helper_home_slides
{
    /**
     * Get slides for a page
     * 
     * @return slides array
     */
	
    function getSlides($page="home")
    {
        $db = SDatabase::getInstance();
    	$SLang =  SLanguage::getInstance();
    	
    	$columns = array(
    		1 => "text_ro as `text`, link_ro as link, foto_ro as foto, params_ro as params, picture_alt_text_ro as picture_alt_text",
    		2 => "text_en as `text`, link_en as link, foto_en as foto, params_en as params, picture_alt_text_en as picture_alt_text",
    	);
    	
        $q="select 
        		id, name , display_time, ".$columns[$SLang->lang]."  
            from home_slides 
            where 
               status=1 
                and 
               (  
                    ( publish_date <= NOW() and  publish_date != '0000-00-00 00:00:00' ) or ( publish_date = '0000-00-00 00:00:00')
               )
               and 
               (  
                    ( unpublish_date > NOW() and  unpublish_date != '0000-00-00 00:00:00' ) or ( unpublish_date = '0000-00-00 00:00:00')
               )
            order by `ordering` ";//AND page = '{$page}'
        
        $db->setQuery($q);
        $slides = $db->loadAssocList();
        
        if(count($slides)>0){
            foreach($slides as $key=>&$item)
                if(isset($item["params"]))
                    $slides[$key]['params']=json_decode($item['params'], true);
            return $slides;
        }
        else
            return false;
	}
}
?>