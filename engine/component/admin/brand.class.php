<?php
/**
 * 
 * Brand Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class brand extends adminModule
{
	public $moduleTitle  = "Brand";
    public $moduleName   = "brand";
    public $tableName  	 = "brands";
    public $idName     	 = "id";
    public $priorityName = "status";

    var $methodsMap = array("delete_file");

    function initFields(){

    	$this->columnsList[]  = new adminField("name", "Nume", "text", 1, 1);
        $this->columnsList[]  = new adminField("seo_name", "Nume seo", "text", 1, 1);
        $this->columnsList[]  = new adminField("status", "Activ", "switch", 5, 5);
    	$this->columnsList[]  = new adminField("photos", "Picture", "upload", null, 5, array("dir"=>UPLOAD_DIR."photos/","url"=>UPLOAD_URL."photos/" , "extensions"=>array("jpg","jpeg","gif","png")));

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
                    unlink(UPLOAD_DIR."photos/{$file}");
                }
            }
            systemMessage::addMessage("File removed!");
            redirect("index.php?obj={$_GET['obj']}&action=page_act&act=upd&{$this->idName}=".$id);
        }
    }

}
?>