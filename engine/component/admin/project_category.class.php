<?php
/**
 * 
 * Project Category Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class project_category extends adminModule
{
	public $moduleTitle  = "Project Categories";
    public $moduleName   = "project_category";
    public $tableName  	 = "project_category";
    public $idName     	 = "id";
    public $priorityName = "status";
    //public $langName     = "lang";

    //var $methodsMap = array("delete_file");

    function initFields(){
    
        //$this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 0);
    	$this->columnsList[]  = new adminField("name", "Name", "text", 1, 1);
    	$this->columnsList[]  = new adminField("seo_name", "Seo Name", "text", null, 3);
    	$this->columnsList[]  = new adminField("status", "Status", "switch", 5, 5);
    
    }
    
    /*function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM article_category GROUP BY id";
        return $q;
    }*/
    
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
                    unlink(UPLOAD_DIR."press/{$file}");
                }
            }
            systemMessage::addMessage("File removed!");    
            redirect("index.php?obj={$_GET['obj']}&action=page_act&act=upd&{$this->idName}=".$id);
        }
    }
    
}
?>