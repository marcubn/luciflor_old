<?php
/**
 * Modules manager tool
 */
class SModules
{
    public $modules = array();
    
    public static function &getInstance(){
        static $instance;
        
        if(!isset($instance)){
            $instance = new SModules();
        }
        return $instance;
    }    
    
    function getAvailableModules(){
        $dir = COMPONENT_DIR.  DIRECTORY_SEPARATOR."modules";
        if(!file_exists($dir))
            return;
        
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            
            if (!$file->isDir()){
                
                $module = new stdClass();
                $module->className = "mod_".$file->getBasename(".php");
                $module->path      = $file->getRealPath();
                $this->modules[] = $module;
            }
            
        }
        
    }
    
    /**
     * Processes the page modules
     */
    function process(){
        
        if(count($this->modules)){
            foreach ($this->modules as $mod) {
                include_once $mod->path;
                $tmp_mod = new $mod->className;
                $tmp_mod->process();
            }
        }
        
    }
    
    /**
     * Display the page modules
     */
    function display(){
        
        if(count($this->modules)){
            foreach ($this->modules as $mod) {
                include_once $mod->path;
                $tmp_mod = new $mod->className;
                $tmp_mod->display();
            }
        }
        
    }

    /**
     * After display events
     */
    function afterDisplay(){
        
        if(count($this->modules)){
            foreach ($this->modules as $mod) {
                include_once $mod->path;
                $tmp_mod = new $mod->className;
                $tmp_mod->afterDisplay();
            }
        }
        
    }

}
?>