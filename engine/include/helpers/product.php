<?php
/**
 * Helper class for common product things
 */
class Helper_product{
	
	/**
	 * Single method to fetch SEF url for section, category or product
	 * @todo to develop as only product data is passed
	 */
	static function seo_link($parent_data=null, $category_data=null, $product_data=null){

		$SLang = SLanguage::getInstance();		
		$lang = $SLang->lang;
		if( (!$parent_data && !$category_data && !$product_data))
			return null;
		
		if( (int)$parent_data>0 )
			$section = $parent_data;
		else
			$section = $parent_data["id"];
			
		$parts =  array();
		/**
		 * Append section URL part
		 */
		//$parts["section"] = (($section==2)?$SLang->getText("url_consumer_prefix", true):$SLang->getText("url_business_prefix", true))."/".(($lang==1)?"produse":"products");
		$parts["section"] = (($section==2)?$SLang->getText("url_consumer_prefix", true):$SLang->getText("url_business_prefix", true));
		
		/**
		 * Append category url
		 */
		if($category_data && isset($category_data["id"])){
			$parts["category"] = $category_data["seo_name"];
		}
		
		/**
		 * Append product url
		 */
		 if($product_data && isset($product_data["id"])){
		 	//$parts["product"] = seo_link($product_data["name"], $product_data["id"])."/";
		 	if($product_data["alias"]=="")
		 	{
		 		$product_data["alias"] = seo_link($product_data['name']);
		 		//echo "UPDATE product SET alias = '".$product_data["alias"]."' WHERE id = ".$product_data['id'];
		 		$db=SDatabase::getInstance();
		 		$db->query("UPDATE product SET alias = '".$product_data["alias"]."' WHERE id = ".$product_data['id']." AND lang = $lang");
		 	}
		 	//echo "<pre>";var_dump($product_data["alias"]);
		 	$parts["product"] = seo_link($product_data["alias"], $product_data["id"])."/";
		 }
		 
		 $url = (isset($parts["section"])?$parts["section"]."/":"" ). (isset($parts["category"])?$parts["category"]."/":"" ). (isset($parts["product"])?$parts["product"]:"");
		 return $url;
	}
	
	/**
	 * Force Loads category if not provided but needed
	 */
	function loadCategory($id){
        $q="SELECT * FROM product_category WHERE id='{$id}' AND lang=$lang";
        $db->setQuery($q);
        return $db->loadAssoc();
	}
	
	/**
	 * Helper method to get online purchase URL for $action page based on parameter product url
	 *
	 * @param string $current_url
	 * @param string $action
	 * @return string
	 */
	static function getURL($current_url, $action=""){
	
		$SLang = SLanguage::getInstance();
	
		/**
		 * List of action specific sufixes for URL's mapped on language ID's
		 * 1 => Ro
		 * 2 => En
		*/
		$action_urls  = array(
				"oferta"		=> array( 1=> "oferta", 2 => "offer" ),
				"lead"			=> array( 1=> "lead", 2 => "lead" ),
				"calculate" 	=> array( 1=> "calculate", 2 => "calculate" ),
				"cere-oferta" 	=> array( 1=> "cere-oferta", 2 => "get-quote" ),
				"cumpara" 		=> array( 1=> "cumpara", 2 => "purchase" ),
				"detalii" 		=> array( 1=> "cumpara/detalii", 2 => "purchase/details" ),
				"plata" 		=> array( 1=> "cumpara/metoda_plata", 2 => "purchase/payment_method" ),
		);
		if( $action && isset($action_urls[$action])){
			$url_sufix = $action_urls[$action][$SLang->lang]."/";
		}
		
		$current_url .= $url_sufix;
		if($current_url[0]!="/")
			$current_url = "/".$current_url;
	
		return $current_url;
	}
	
}
?>