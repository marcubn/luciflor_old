<?php
/**
 * 
 * Web Document Data Wrapper
 *  Seo Purpose:
 *  
 *  Page Title
 *  Metatags:
 *   Page Description
 *   Page Keywords
 *  
 *  Breadcrumbs Pathway
 *
 *  Version:1.0
 *
 * 
 * */
class SDocument{
    
    var $page_title = null;
    
    var $_metatags = array();
    
    var $pathway = array();
    
    static public $scripts   = array();
    static public $js_files  = array();
    static public $css_files = array();
    
    static function &getInstance( $page_properties = array() ){
        
        static $instances;
        if(!isset($instances)){
                $instances = array();
        }
        if(empty($instances["SDoc"])){
                $instances["SDoc"] = new SDocument($page_properties);
        }
        return $instances["SDoc"];
        
    }
    
    function SDocument(){
        $this->pathway = $this->getPathway();
    }
    
    function setPageTitle( $title ){
        
        $this->page_title = $title;
    
    }
    
    function getPageTitle(){
        
        return $this->page_title;
    
    }
    
    function setMetaTag( $tag_name, $tag_value ){
        
        $this->_metatags[ $tag_name ] = $tag_value;
        
    }
    
    function getMetaTag( $tag_name ){
        
        if(isset($this->_metatags[ $tag_name ]))
            return $this->_metatags[ $tag_name ];

        return "";
    }
    
    function setPathWay( $arr = array() ){
        
        $SLang =  &SLanguage::getInstance();
        if(isset($SLang->texts["breadcrumb_home"]))
            $arrR = array(strip_tags(($SLang->texts["breadcrumb_home"]["text"])) => "/");
        else
            $arrR = array();
        
        if($arr)
            $this->pathway = array_merge($arrR,$arr);
        else
            $this->pathway = $arrR;
    
    }
    
    function addScript($js){
        $this->scripts[md5($js)] = $js;
    }
    
    function includeJS($js){
        @$this->js_files[md5($js)] = $js;
    }

    function includeCSS($css){
        @$this->css_files[] = $css;
    }
    
    function displayMetaTags( ){
        $out = "";
        if($this->_metatags){
            foreach($this->_metatags as $k => $tag){
                $out .= '   <meta name="'.strtolower($k).'" content="'.strip_tags($tag).'" />'.PHP_EOL;
            }
        }
        return $out;
    }
    
    function systemHead(){
        $head_string = "";
        /**
         * Add css files
         */
        if(@isset($this->css_files) && count(@$this->css_files)>0){
            foreach(@$this->css_files as $file)
                $head_string .= "\t<link rel=\"stylesheet\" href=\"{$file}\">".PHP_EOL;
        }
        
        if($head_string){
            return $head_string;
        }else
            return "";
    }
    
    function systemFooter(){
        
        $head_string = "";
        /**
         * Add javascript files
         */
        if(@isset($this->js_files) && count(@$this->js_files)>0){
            foreach(@$this->js_files as $file)
                $head_string .= "\t<script src=\"{$file}\"></script>".PHP_EOL;
        }

        /**
         * Add javascript code
         */
        if(isset($this->scripts) && count($this->scripts)>0){
            $js_statements = implode($this->scripts, PHP_EOL);

            $head_string .= "<script type=\"text/javascript\">".PHP_EOL;
            $head_string .= "$(document).ready( function() {".PHP_EOL;
            $head_string .= $js_statements.PHP_EOL;
            $head_string .= "});".PHP_EOL;
            $head_string .= "</script>".PHP_EOL;
        }
        
        
        if($head_string){
            return $head_string;
        }else
            return "";
    }
    
    function getPathway(){ 
        
        return $this->pathway;
    }
}

?>