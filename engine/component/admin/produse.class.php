<?php
/**
 * 
 * Article Class
 * 
 * 
 * */
require_once(LIB_DIR . "/admin/module.php" );
include(LIB_DIR.'upload/fupload.class.php');

class produse extends adminModule
{
	public $moduleTitle  = "Produse";
    public $moduleName   = "produse";
    public $tableName  	 = "produse";
    public $idName     	 = "id";
    public $priorityName = "status";
    public $langName     = "lang";

    public $uplphoto     = "da";

    var $methodsMap = array("delete_file");

    function initFields(){

        $this->columnsList[]  = new adminField("lang", "Language", "lang", 0, 2);
    	$this->columnsList[]  = new adminField("nume", "Nume", "text", 1, 1);
    	$this->columnsList[]  = new adminField("cod_produs", "Cod produs", "text", 1, 1);
    	$this->columnsList[]  = new adminField("pret", "Pret", "text", 1, 1);
    	$this->columnsList[]  = new adminField("pret_retail", "Pret retail", "text", 1, 1);
        $this->columnsList[]  = new adminField("categorie", "Category", "list", null, 1, array("query"=>"SELECT id as value, nume as name FROM produse_category GROUP BY id"));
    	$this->columnsList[]  = new adminField("descriere", "Descriere", "editor", null, 3);
        $this->columnsList[]  = new adminField("detalii", "Detalii", "editor", null, 4);
        $this->columnsList[]  = new adminField("brand", "Brand", "list", null, 1, array("query"=>"SELECT id as value, name FROM brands GROUP BY id ORDER BY name"));
        $this->columnsList[]  = new adminField("status", "Status", "switch", 5, 5);
        $this->columnsList[]  = new adminField("nou", "Nou", "switch", 6, 6);
        //$this->columnsList[]  = new adminField("tip", "Tip", "list", null, 1, array("query"=>"SELECT id as value, nume as name FROM produse_tip GROUP BY id"));

    }

    function getQuery() {
        $q="SELECT *, GROUP_CONCAT(lang) as langkeys FROM produse GROUP BY id";
        return $q;
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