<?php
/**
 * Pages front class
 *
 */
class pages
{
        var $tplName 		  = "default.tpl";
        var $tplDir 		  = "site/pages/";
        var $moduleName 	  = "";
        var $pagingAction 	  = "";

        var $tableName 		  = "";
        var $idName 		  = "";
        var $flagName 		  = "";
        var $priorityName	  = "";
        var $_lang	          = "";
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function pages($action="")
	{
            switch($action)
            {
                default:
                case "view":	
                    $this->view();
                break;      		 	

            }
	}
		
	/**
	 * Home Page
	 *
	 * @access: public
	 * @return: null
	*/
	function view()
	{
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $lang = SLanguage::getInstance();
            
        if(isset($_GET['alias'])&&$_GET['alias']!="")
        {
            $db = &SDatabase::getInstance();
            $db->setQuery("select * from pages where page_status=1 and page_lang='{$lang->lang}'");
            $all_pages = $db->loadObjectList("page_alias");
            
            $alias = getFromRequest($_GET, "alias","");
            
            $page = null;
            if(isset($all_pages[$alias])){
                
                $page = $all_pages[$alias];
                $page->poze = get_pictures('pages', $page->page_id, 1, 0);
                if($page->page_template!="")
                    $this->tplName = $this->tplDir.$page->page_template;
            }
                
            if($page!==false&&!empty($page)){
                
                $doc->setPageTitle($page->page_titlu);
                $smarty->assign("parametru", json_decode($page->params));
                $smarty->assign("page",$page);
            }
            else
                redirect(ROOT_HOST);
        }
        
        $category_name = "";
        $category_url = "";
        
        switch ($page->page_category){
        	default:
        	case 1:{
        		$category_name = ($lang->lang==1)?"Servicii & Contact":"Service & Contact";
        		$category_url = "/service-contact/";
        	}
        	case 2:{
        		$category_name = ($lang->lang==1)?"Despre noi":"About Us";
        		$category_url = "/despre-noi/";
        	}
        }
        
        $breadcrumbs = array( 
        	SLanguage::getText("breadcrumbs_home_label", true) => "/",
        	$category_name => $category_url, 
        	$page->page_titlu." " => ""
       	);
        $doc->setPathWay($breadcrumbs);
        $doc->includeJS('/js/jquery/validate/jquery.validate.min.js');
        $smarty->display($this->tplName, CACHE_ID);
	}

   
}
?>