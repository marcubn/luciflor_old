<?php
/**
 * 
 * Slides Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class slides extends adminModule
{
	public $moduleTitle  = "Slides";
    public $moduleName   = "slides";
    public $tableName  	 = "slides";
    public $idName     	 = "id";
    public $priorityName = "ordine";
    public $orderBy = "ordine";
    //public $uplphoto     = "da";
    //public $langName     = "lang";

    var $methodsMap = array("delete_file");

    function initFields(){

        //$this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 1);
        $this->columnsList[]  = new adminField("title", "Title", "text", 1, 1);
        $this->columnsList[]  = new adminField("link", "Link", "text", 2, 2);
        $this->columnsList[]  = new adminField("ordine", "Ordine", "order", 3, 3);
        $this->columnsList[]  = new adminField("status", "Activ", "switch", 4, 4);
    	$this->columnsList[]  = new adminField("slide", "Picture", "upload", null, 5, array("dir"=>UPLOAD_DIR."photos/", "url"=>UPLOAD_URL."photos/", "extensions"=>array("jpg","jpeg","gif","png")));

    }

    /*function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM article GROUP BY id";
        return $q;
    }*/

    function delete_file()
    {
        if(isset($_GET[$this->idName]) && $_GET[$this->idName]!=''){
            $id = filter_var($_GET[$this->idName], FILTER_SANITIZE_NUMBER_INT);
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