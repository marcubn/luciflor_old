<?php
/**
 * Language Stuff
 *  
 **/
class SLanguage{
    
    /**
     *  Current Language
     */
    var $default   = "1";
     
    var $lang      = null;
    
    var $lang_var  = "lng";
    /**
     *  Available Language
     */
    var $languages = array();

    var $multilanguage   = true;
    
    function SLanguage(){
        if($this->multilanguage==true){
            $this->languages    = $this->loadLanguages("language_id");
            $this->setLanguage();
            $this->lang         = $this->getLanguage();
        }else{
            $this->lang = $this->default;
        }
        
        $this->loadTextBlocks();
        
    }
    
    public static function &getInstance(  ){
        static $instances;
        if(!isset($instances)){
                $instances = array();
        }
        if(empty($instances["SLang"])){
                $instances["SLang"] = new SLanguage();
        }
        return $instances["SLang"];
    }
    
    function setLanguage( ){
        
        if( isset( $_REQUEST[ $this->lang_var  ]) && $_REQUEST[ $this->lang_var  ] !="" )
    	{
    		if ( isset($this->languages[ $_REQUEST[ $this->lang_var  ] ]) ) 
                $_SESSION[SESS_IDX]['lng'] = $_REQUEST[ $this->lang_var  ];
    	}
    }
    
    function getLanguage(){
        
        return isset($_SESSION[SESS_IDX]['lng'])?$_SESSION[SESS_IDX]['lng']:$this->default;
        
    }
    
    function loadLanguages( $key = "language_code"){
        
        $dbo = SDatabase::getInstance();
        $dbo->setQuery( "SELECT * FROM languages ORDER BY language_order" );
        return $dbo->loadObjectList( $key );
    
    }
    
    public function getTextsBlocks(){
        
        if(!isset($this->texts))
            $this->loadTextBlocks();
        
        return $this->texts;
    }
    
    private function loadTextBlocks(){
    	
    	$mobile_sql = "";
    	if(class_exists("SApp")){
    		$app = SApp::getInstance();
    		$mobile = $app->varGet("is_mobile");
    		$tablet = $app->varGet("is_tablet");
    		if($mobile || $tablet)
    			$mobile_sql = ",if(text_text_mobile!='', text_text_mobile, text_text) as text_text";
    	}
    	
        $db = SDatabase::getInstance();
        $db->setQuery("select * {$mobile_sql} from texte where text_lang='{$this->lang}'");
        $this->texts = $db->loadObjectList("text_alias");
        
    }
    
    static function getText($text_code,$no_html=true){
        $Slang = &SLanguage::getInstance();
        $texts = $Slang->texts;
        if(isset($texts[$text_code]->text_text)){
            return ($no_html)?strip_tags($texts[$text_code]->text_text):$texts[$text_code]->text_text;
        }else
            return $text_code;
        
    }
    
}
