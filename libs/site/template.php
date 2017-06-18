<?php
require_once( SMARTY_DIR . "Smarty.class.php" );

class STemplate extends Smarty{
    
    public static function &getInstance(){
        static $instance;
        
        if(!isset($instance)){
            //===> ENGINE TEMPLATE INITIALISATION
            require_once( SMARTY_DIR . "Smarty.class.php" );

            $smarty  = new STemplate;
            $smarty->setTemplateDir(TEMPLATES_DIR);
            $smarty->setConfigDir(TEMPLATES_DIR."configs/");
            $smarty->setCompileDir(COMPILE_DIR);
            $smarty->setCacheDir(CACHE_DIR);

            $smarty->debugging      = false;
            $smarty->setCaching(CACHING);
            if(CACHING>0)
                $smarty->setCompileCheck(false);
            $smarty->setCacheLifetime(CACHE_LIFETIME);
            $instance = $smarty;
        }
        
        return $instance;
    }
    
    function display($template, $cache_id = NULL, $compile_id = NULL, $parent = NULL){
        
        /**
         * Get modules data to display
         */
        $modulesManager = SModules::getInstance();
        $modulesManager->display();
        
        parent::display($template, $cache_id, $compile_id, $parent);
        
        $modulesManager->afterDisplay();
    }
}
?>