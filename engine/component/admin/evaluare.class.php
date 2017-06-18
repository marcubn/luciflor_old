<?php
/**
 * 
 * Evaluare Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class evaluare extends adminModule
{
	public $moduleTitle  = "Evaluare";
    public $moduleName   = "evaluare";
    public $tableName  	 = "evaluare";
    public $idName     	 = "id";
    public $priorityName = "status";

    var $methodsMap = array("delete_file");

    function initFields(){

    	$this->columnsList[]  = new adminField("nume", "Nume", "text", 1, 1);
        $this->columnsList[]  = new adminField("telefon", "Telefon", "text", 2, 2);
        $this->columnsList[]  = new adminField("email", "Email", "text", 3, 3);
        $this->columnsList[]  = new adminField("tip", "Tip", "list", null, 4, array("query"=>"SELECT id as value, tip as name FROM evaluare_tip"));
    	$this->columnsList[]  = new adminField("descriere", "Descriere", "editor", null, 5);
        $this->columnsList[]  = new adminField("status", "Status", "switch", 8, 8);
    	$this->columnsList[]  = new adminField("photos", "Picture", "upload", null, 9, array("dir"=>UPLOAD_URL."evaluare/", "extensions"=>array("jpg","jpeg","gif","png")));

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