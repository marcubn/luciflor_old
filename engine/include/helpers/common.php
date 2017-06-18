<?php
/**
 * 
 * Common class
 * 
 * @author: Bogdan Marcu.
 * 
 */
class Common
{
    /**
     * Get Locality ID
     * 
     * @todo:sa interogheze si nume fara diacritice
     * @todo:sa sanitiseze - este o problema aici: filter_var nu are suport pentru UTF-8 sau nu este folosit corect.
     * @return slides array
     */
	
    static function get_ws_id($localitate="home")
    {
        $db = SDatabase::getInstance();
        
        if(is_string($localitate)) 
        {
        	$string 	= $db->getEscaped($localitate);   
            $judet 		= substr($string, strrpos($string, "("));
            $localitate = str_replace($judet, "", $string);
            $judet 		= str_replace( array("(",")"), "", $judet); 	
            
            $q="SELECT ws_id FROM localitati WHERE (nume_diacritice='{$localitate}') AND (judet_diacritice='{$judet}')";
            
            $db->setQuery($q);
            $intermed = $db->loadAssoc();
            $ws_id=$intermed['ws_id'];
        }
        elseif(is_int($localitate))
        {
            $id = $db->getEscaped($localitate);
            $q="SELECT ws_id FROM localitati WHERE id=".$id;
            $db->setQuery($q);
            $intermed = $db->loadAssoc();
            $ws_id=$intermed['ws_id'];
        }
        return $ws_id;
	}
	
    /**
     * Get Locality Split
     * 
     * @todo:sa interogheze si nume fara diacritice
     * @todo:sa sanitiseze - este o problema aici: filter_var nu are suport pentru UTF-8 sau nu este folosit corect.
     * @return slides array
     */
	
    static function get_city_split($localitate="home")
    {
        $string=str_replace(")","", $localitate);
        $locatie = explode("(", $string);
        return $locatie;
	}
	
	/**
     * Get Manufacturer Name
     * 
     * @param integer
     * @return slides array
     */
	
    static function get_manufacturer($ws_id)
    {
        $db = SDatabase::getInstance();
        
        $q="SELECT marca FROM ws_auto_marci WHERE ws_id='{$ws_id}';";
        $db->setQuery($q);
        $var = $db->loadObject();
            
        return $var->marca;
	}
}
?>