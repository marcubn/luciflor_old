<?php
/**
 * Base application manager
 * 
 * @version 1.0
 */
class SApp{
    
    /**
     * Current module name
     * @var type string
     */
    public $obj;
    /**
     * Current module action
     * @var type string
     */
    public $action;
    /**
     * Application templating engine
     * @var type Smarty object 
     */
    public $template;
    /**
     * List of modules
     * @var type array
     */
    public $modules;
    
    /**
     * Set of application variables
     */
    private $variables=array();

    public static function &getInstance(){
        static $instance;
        
        if(!isset($instance)){

            $application = new SApp();
            $application->init();
            $instance = $application;
        }
        
        return $instance;
    }
    
    /**
     * Executes an application
     */
    function execute()
    {
        global $profiler;
        /**
         * Execute modules
         */
        $modulesManager = SModules::getInstance();
        $modulesManager->getAvailableModules();
        $modulesManager->process();
        
        $this->obj    = filter_var(getFromRequest($_GET, "obj", "front") , FILTER_SANITIZE_STRING);
        $this->action = filter_var(getFromRequest($_GET, "action", "home") , FILTER_SANITIZE_STRING);
        
        if(file_exists(COMPONENT_DIR.'site/'.$this->obj.".class.php"))
        {
            if(DEBUG == 1)
                $profiler->enterSection($this->obj."-".$this->action);

            include_once COMPONENT_DIR.'site/'.$this->obj.".class.php";

            new $this->obj($this->action);

            if(DEBUG == 1)
                $profiler->leaveSection($this->obj."-".$this->action);	
        }else{
            /**
             * @todo standard 404
             */
        }
        
    }
    
    /**
     * Initialise application
     */
    function init()
    {
        session_start();
		define("SESS_IDX", SESS_IDX_FE);
        //===> DEBUG & PROFILER
        if(1==DEBUG)
        {
            global $profiler, $mp, $start_memory;
            require(LIB_DIR."bench/Profiler.php");
            $profiler = new Benchmark_Profiler();
            $profiler->start();

            require_once LIB_DIR."bench/Memory.php";
            $mp = new MemoryProfiler();
            $start_memory = $mp->getMemory();

        }
        
        /**
         * Brutally kills and logs every attempt of xss, sqli, malware upload, etc
         */
        include_once LIB_DIR."firewall/firewall.php";
        $firewall = new SFirewall();
        $firewall->filter();

        if(DEBUG == 1)
            $profiler->enterSection("<b>Application load</b>");
	
		//define session 
		if(!isset($_SESSION[SESS_IDX]))
            $_SESSION[SESS_IDX]=array();
        
        /**
         * Error reporting
         */
        error_reporting(ERROR_REPORTING);

        switch (ERROR_REPORTING)
        {
            default:
            case 'none':
            case '0':
                    error_reporting(0);
                    break;

            case E_ALL:
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    
                    break;
        }
        
        /**
         * requires database libraries
         */
        include_once( LIB_DIR . "db/database.php");
        /**
         * requires standard librariess
         */
        include_once( LIB_DIR . "utile/utile.lib.php");
        /**
         * requires document library
         */
        include_once( LIB_DIR . "document/document.lib.php");
        /**
         * requires language library
         */
        include_once( LIB_DIR . "lang/lang.lib.php");
        /**
         * requires modules library
         */
        include_once( LIB_DIR . "site/modules.php");
        /**
         * requires pagination library
         */
        require_once( SMARTY_DIR . "pagination.class.php" );
        /**
         * require photo gallery library
         * @todo should it load always?
         */
        include_once( LIB_DIR . "image/uplphoto.lib.php");
        
        /**
         * Mobile detect
         */
        require_once( LIB_DIR . "MobileDetect/Mobile_Detect.php");
        $detect = new Mobile_Detect;
        $this->varSet("is_mobile", $detect->isMobile());
        $this->varSet("is_tablet", $detect->isTablet());
        
        
        /**
         * This should not be included always
         */
        require_once( LIB_DIR . "mail/sendmail.php");
        
        include_once( INCLUDE_DIR . "functions.php");

        if(DEBUG == 1)
            $profiler->leaveSection("<b>Application load</b>");
    }
    
    /**
     * get's and decorates the template with common elements
     */
    public function getTemplate()
    {
        if(!$this->template){
            include_once LIB_DIR."site/template.php";
            $this->template = STemplate::getInstance();


            $this->template->assign("session", $_SESSION[SESS_IDX]);
            $SDoc = SDocument::getInstance();
            $SDoc->setPathWay();
            $this->template->assignByRef("SDoc",$SDoc);
            
            if(isset($_SESSION[SESS_IDX]["UL"]["auth"]) && 1==$_SESSION[SESS_IDX]["UL"]["auth"]){
            	unset($_SESSION[SESS_IDX]["UL"]["member_pass"]);
            	$this->template->assign("UL", $_SESSION[SESS_IDX]["UL"]);
            }

            $SLang =  &SLanguage::getInstance();
            $this->template->assign("lang", $SLang->lang);
            $this->template->assign("texts", $SLang->texts);

		    require_once( LIB_DIR . "MobileDetect/Mobile_Detect.php");
		    $detect = new Mobile_Detect;
		    $this->template->assign("IS_MOBILE", $detect->isMobile());

            //===>ASSIGN SEO
            $db = SDatabase::getInstance();
            $url = "http://".$db->getEscaped($_SERVER["HTTP_HOST"]).($_SERVER["REQUEST_URI"]);
            $db->setQuery("select * from seotable where seo_url = '{$url}'");
            $seo = $db->loadObject();
            $this->template->assign('site_meta_url', $url);
            if(isset($seo->seo_id)){
                $this->template->assign('site_meta_title', $seo->seo_title);
                $this->template->assign('site_meta_h1', $seo->seo_h);
                $this->template->assign('site_meta_description', $seo->seo_description);
                $this->template->assign('site_meta_keywords', $seo->seo_keywords);
            }
            //===>ASSIGN SEO
		    
            $this->template->assign("APP_MESSAGE",systemMessage::renderSystemMessage());
            $this->template->assign("APP_CODE",systemComPipe::getCode());
            $this->template->assign("APP_TRACK_EVENTS",systemMessage::renderTrackEvents());
            $this->template->assign("session", $_SESSION[SESS_IDX]);
        }
        return $this->template;
        
    }
    
    /**
     * Setter for application variables
     * If variable_data is null and variable exists in stack, then unset's it
     */
    public function varSet($variable_name, $variable_data=null){
    	if(isset($this->variables[$variable_name]) && is_null($variable_data))
    		unset($this->variables[$variable_name]);
    	else
    		$this->variables[$variable_name] = $variable_data;
    }
    
    /**
     * Getter for application variables
     */
    public function varGet($variable_name){
    	
    	if(isset($this->variables[$variable_name]))
    		return $this->variables[$variable_name]; 
		return null;
    }
    
    
    function displayDebug()
    {
        global $profiler, $mp, $start_memory;
        //===> DEBUG & PROFILER	
        if(DEBUG == 1){
            $profiler->stop();
                echo "<center style='width:80%; margin:auto; border:1px solid #555;'>";
                $dbo = SDatabase::getInstance();
                echo "<strong>Executed Queries:</strong> ".count($dbo->_log)."<br />";
                echo "<h2 style='color:red; background:#555;' onclick=\"document.getElementById('sapp_profiler').style.display='';\">Profiler</h2>";
                echo "<div style='display:none;' id='sapp_profiler'>";
                $profiler->display();
                echo "</div>";
                echo "<h2 style='color:red; background:#555;' onclick=\"document.getElementById('sapp_queries').style.display='';\">Queries</h2>";
                echo "<div style='display:none;' id='sapp_queries'>";
                if(count($dbo->_log)){
                    echo "<table border=1><tr><th width='25'>#</th><th>Query</th></tr>";
                    foreach($dbo->_log as $k => $q){
                        echo "<tr><td>{$k}</td><td>{$q}</td></tr>";
                    }
                    echo "</table>";
                }
                echo "</div>";
                echo "<h2 style='color:red; background:#555;' onclick=\"document.getElementById('sapp_memory').style.display='';\">Memory & CPU</h2>";
                echo "<div style='display:none;' id='sapp_memory'>";
                echo "App Memory load: <strong>".$mp->convert($mp->getMemory()-$start_memory)."</strong>";
                echo "<br />Total memory load: <strong>".$mp->convert($mp->getMemory())."</strong>";
                echo "<br />  CPU load: <strong>".$mp->get_server_cpu_usage()."%</strong>";
                echo "<br /><br /></div>";

                echo "</center>";
        }
        //<===


        
    }

    /**
     * Redirect onl links
     */
    function check_redirect() {
        $db = SDatabase::getInstance();
        $from = $db->getEscaped(substr(ROOT_HOST,0,-1).$_SERVER['REQUEST_URI']);
        $q = "SELECT redirect_to FROM redirect WHERE redirect_from = '{$from}'";
            $db->setQuery($q);
            $r = $db->loadObject();
            if(isset($r->redirect_to)){
                $to = $r->redirect_to;

                if($to)
                {
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: $to"); 
                }
            }
    }
}
?>