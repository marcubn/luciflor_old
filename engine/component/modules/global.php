<?php 
include_once LIB_DIR."site/module.php";

/**
 * 
 * Newsletter subscribing module
 * 
 */
class mod_global extends site_module{

	function process(){
        $SLang =  SLanguage::getInstance();
        $lang = $SLang->lang;
        $app = SApp::getInstance();
        
       
        
	}

    function display()
    {
        $db=SDatabase::getInstance(); 
    	$SLang =  SLanguage::getInstance();
        $lang = $SLang->lang;
        $app = SApp::getInstance();
        $smarty = $app->getTemplate();
        $section = (int)$app->varGet("section");
        $doc = SDocument::getInstance();
        
        $ip = getIP();
        if(isset($_SESSION[SESS_IDX][$ip]["ip_data"])){
        	$smarty->assign("localisation", $_SESSION[SESS_IDX][$ip]["ip_data"]);
        }

        
        $show_topmenu = $app->varGet("show_topmenu");
        if(!isset($show_topmenu))
        	$show_topmenu = true;
        
        
         $ip = getIP();
         //$ip = "89.33.124.121";
        
        $smarty->assign("return_url", base64_encode($_SERVER["REQUEST_URI"]));
        $smarty->assign("section", $section);
        $smarty->assign("show_topmenu", $show_topmenu);
                
    }
    
}

?>