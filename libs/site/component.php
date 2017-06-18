<?php
/**
 * Site Base Component Controller
 * 
 * Extend this with particular controllers
 *
 * @version 1.0
 * 
 */
class siteComponent{
    
    public $tplName         = null;
    public $tplDir          = null;
    public $moduleName      = null;
    public $pagingAction    = null;
    public $tasks           = null;
    public $default_task    = null;
    public $modelName       = null;
    public $modal           = null;
    
    /**
     * 
     * Routes and execute requested action
     * 
     * @param string $action
     */
    function siteComponent($action=""){
        
        if( !empty($this->modelName) && file_exists(INCLUDE_DIR."models/".$this->modelName.".php") ){
            
            include_once INCLUDE_DIR."models/".$this->modelName.".php";
            $modelName = $this->modelName."model";
            $this->_model = new $modelName();
            
        }else{
            /*
             * Load generic model
            include_once LIB_DIR."site/model.php";
            $this->_model = new siteModel();
             * 
             */
        }
            
        if( in_array($action, $this->tasks) ){

            if( method_exists($this, $action) ){
                $this->$action();
            }
            elseif( file_exists(TEMPLATES_DIR.$this->tplDir.$action.".tpl") ){
                $this->tplName = $this->tplDir.$action.".tpl";
                $this->display();
            }
            
        }else{
            $default_task = $this->default_task;
            $this->$default_task();
        }
    }
    
    /**
     * @todo
     */
    function page_list(){
        $this->tplName = $this->tplDir."/page_list.tpl";
        
        $this->display();
    }
    
    /**
     * @todo
     */
    function page_view(){
        $this->tplName = $this->tplDir."/page_view.tpl";
        
        $this->display();
    }
    
    /**
     * 
     * Displays the template using Smarty
     * 
     * @global object $smarty
     */
    function display(){
    	$app = SApp::getInstance();
        $smarty = $app->getTemplate();
        $smarty->assign("modal", $this->modal);
        $smarty->display($this->tplName);
        if(isset($this->modal) && $this->modal==true)
            exit;
    }
    
    /**
     * @todo
     */
    function _getList(){
        
        if( isset($this->_model) && method_exists($this->_model, "_getList") )
            return $this->_model->getList();
        
    }
    
    /**
     * @todo
     */
    function hit(){
        
    }
    
    /**
     * @todo
     */
    function store(){
        
    }
    
    /**
     * @todo
     */
    function _getTable(){
        
    }
    
    /**
     * 
     * Assign variable to template using Smarty
     * 
     * @global object $smarty
     */
    function assign($key, $value){
    	$app = SApp::getInstance();
        $smarty = $app->getTemplate();
        $smarty->assign($key, $value);
    }
    
    
}