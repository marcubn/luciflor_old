<?php
/**
 * 
 * 
 * Home Slides Class Implementation
 *
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class home_slides extends adminModule
{
    var $tplName 		  = "home_slides";
    var $moduleName       = "home_slides";
    var $pagingAction     = "page_list";
    var $moduleTitle      = "Main Teasers";
    
    var $tableName 		  = "home_slides";
    var $idName 		  = "id";
    var $flagName 		  = "status";
    var $priorityName     = "ordering";
    var $methodsMap = array("delete_file");
	
    
    function add_upd(){
        
        $act = getFromRequest($_POST, "act");
        $table = clone $this->table;
        
        $_POST['params_ro'] = json_encode($_POST['params_ro']);
        $_POST['params_en'] = json_encode($_POST['params_en']);
        
        // Begin Upload Fle
        $new_upload = new FUpload("foto_ro",UPLOAD_DIR."home_slides/");
        $new_upload->fileTypeAccepted = array("jpg","jpeg","gif","png");
        $new_upload->cms_do_upload($this);
        if( !$new_upload->success ){
            systemMessage::addMessage(implode("<br />",$new_upload->error_msg_queue) );
            unset($table->foto_ro);
        }
        
        // Begin Upload Fle
        $new_upload = new FUpload("foto_en",UPLOAD_DIR."home_slides/");
        $new_upload->fileTypeAccepted = array("jpg","jpeg","gif","png");
        $new_upload->cms_do_upload($this);
        if( !$new_upload->success ){
            systemMessage::addMessage(implode("<br />",$new_upload->error_msg_queue) );
            unset($table->foto_en);            
        }
        
        $table->bind($_POST);
        
        if( $act=="upd" ){
            // Modified stuff
            $table->modified_by = $_SESSION[SESS_IDX]["UL"]["user_id"];
            $table->modified_date = date("Y-m-d H:i:s");
        }
        
        $ok = $table->store(true);
        
        if( $ok===true ){
            systemMessage::addMessage("Saved");
            $id = $table->id;
        }
        else
            systemMessage::addMessage(implode("; ",$ok));    

        if( isset($id) && isset($_POST['backToEditForm']) )
            redirect("index.php?obj={$_GET["obj"]}&action=page_act&{$this->idName}={$id}&act=upd");
        else
            redirect("index.php?obj={$_GET["obj"]}&action=page_list");
    }
    
    function loadTable(){
	
        require_once ( LIB_DIR."db/db_table.php" );
        $this->table = &STable::tableInit($this->tableName,$this->idName);
        
    }
	
    /**
     * Get Items List
     *
     * @access: public
     * @return: array
    */
    function getList()
    {
        $db = SDatabase::getInstance();
        if(isset($_POST["act"]) && $_POST["act"] == "search") search($this->moduleName);

        $sqlSort = newSort($this->moduleName, "{$this->tableName}.{$this->priorityName}", 'ASC');
        $sqlLimit = paging($this->moduleName, $this->pagingAction);
		
		$myC = $_SESSION[SESS_IDX][$this->moduleName];		
		$vectSearch = (isset($myC["search"]) ? $myC["search"] : array());
		$sqlWhere="";
		if(count($vectSearch) > 0)
		{
			foreach($vectSearch as $key=>$value)
			{
				switch($key)
				{
					case "simple_name":
						$sqlWhere .= "AND {$this->tableName}.product_name LIKE '%$value%' ";
						break;
					case "simple_status":
						$sqlWhere .= "AND {$this->tableName}.{$this->flagName} = '$value' ";
						break;
				}
			}
		}
		
		$q="SELECT 
				{$this->tableName}.{$this->idName}
			FROM 
				{$this->tableName}
			WHERE 
				1
				{$sqlWhere}
		";
                $db->query($q);
                $_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$db->getNumRows();
		
		$q="SELECT 				
				{$this->tableName}.*
			FROM 
				{$this->tableName}
			WHERE 			
				1
				{$sqlWhere}
			ORDER BY 
				{$sqlSort}
			{$sqlLimit}
		";
		$db->setQuery($q);
                $result=$db->loadAssocList();
		
		$list = array(); $i=-1;
		foreach($result as $key=>$record)
		{
			$i++;
			$list[$i]=$record;
			$list[$i]["del_op"] = $this->verifyDel($record[$this->idName]);
		}
		
		return $list;
	}

    
    /**
     * Page act (add/edit form)
     *
     * @access: public
     * @return: null
    */
    function page_act()
    {
        global $smarty;
        $db = SDatabase::getInstance();
        objInitVar($this, "admin/{$this->tplName}_act.tpl", "page_act", "", "", "", "");

        $form_act = array();
        if(isset($_GET['act']) && 'upd'==$_GET['act'] && isset($_GET[$this->idName]) && $_GET[$this->idName]!=''){
            
            $id = $_GET[$this->idName];
            $q="SELECT * FROM $this->tableName WHERE $this->idName = $id";
            $db->setQuery($q);
            $form_act=$db->loadAssoc();
            $form_act['act'] = 'upd';
            
            if(!isset($form_act['modified_by']))
            {
                $form_act['modified_by']=0;
            }
            $q="SELECT user_userid FROM user WHERE user_id = ".$form_act['modified_by'];
            $q=$db->setQuery($q);
            $modified_by = $db->loadAssoc();
            $form_act['modified_by_usr']=$modified_by['user_userid'];
            
            if(isset($form_act['params_ro']))
            {
                $params_ro=json_decode($form_act['params_ro'], true);    
                $smarty->assign("params_ro", $params_ro);
            }
            
            if(isset($form_act['params_en']))
            {
                $params_en=json_decode($form_act['params_en'],true);    
                $smarty->assign("params_en", $params_en);
            }
            
            
            
        }else{
            $form_act = $this->table->getProperties();
            if(isset($_POST) && !empty($_POST))
                $form_act=$_POST;		
            $form_act['act'] = 'add';

        }

        if($this->priorityName!=""){
            
		  $minOrder=$db->getMin($this->tableName, $this->priorityName);
		  $maxOrder=$db->getMax($this->tableName, $this->priorityName);
            
            if ($maxOrder!=0 || $minOrder!=0) { 
                $maxOrder=$maxOrder++;
                $minOrder=$minOrder--;
            }
            if(!isset($maxOrder))
                $maxOrder=1;
            
            $smarty->assign("minOrder", $minOrder);
            $smarty->assign("maxOrder", $maxOrder);
        }

        //assign variables					
        $smarty->assign("form_act", htmlArrayFilter($form_act));
        $smarty->assign("idName", $this->idName);

        //display smarty template
        $smarty->display($this->tplName);		
    }
    
    function delete_file()
    {
        if(isset($_GET[$this->idName]) && $_GET[$this->idName]!=''){
            echo $id = filter_var($_GET[$this->idName], FILTER_SANITIZE_NUMBER_INT);
            $filename = filter_var(getFromRequest($_GET, "file_name"), FILTER_SANITIZE_STRING);
            if($filename){

                $table = clone $this->table;
                $table->load( $id );

                $file = @$table->$filename;

                if( trim($file)!=""){
                    $table->$filename = "";
                    $table->store();
                    unlink(UPLOAD_DIR."home_slides/{$file}");
                }
            }
            systemMessage::addMessage("File removed!");    
            redirect("index.php?obj={$_GET['obj']}&action=page_act&act=upd&{$this->idName}=".$id);
        }
    }
        
}
?>