<?php

require_once(LIB_DIR.'folder_files/folder_files.lib.php');

 
class FUpload{
  
  /**
   * File's Key in $_FILES to be uploaded
  */
  var $filename     = "";
  
  /**
   * File's destination name uploaded
  */
  var $new_filename = "";
  
  /**
   * Upload destination directory 
  */
  var $upload_path  = "";
  
  /**
   * Upload Max MB Size
  */
  var $max_size     = 2097152;
  
  /**
   * CUSTOM Uploading Parameters 
   **/
  
  /**
   * Upload Mode
   *    0: New Suffixed Name ( filename(2).doc );
   *    1: Overwrite; 
   *    2: Skip; 
   **/
  
  var $upload_mode = 0;
  
  var $success      = false;
  
  var $error_msg_queue = array();
  
  var $fileTypeAccepted = array();
  
  function FUpload( $filename, $upload_path ){
    
    $this->filename = $filename;
    $this->upload_path = $upload_path;
    
  }
  
  function cms_do_upload( &$obj ){
        
        // IF upload then Upload
        $this->do_upload();
        if ( $this->success == true ){
            $_POST[ $this->filename ] = $this->new_filename;
        }else{
            //unset( $obj->tableFields[ array_search($obj->filename, $obj->tableFields) ] );
        }
        
  }
  
  
  function do_upload(){
    
    // test if uploaded
    if ( is_uploaded_file($_FILES[ $this->filename ]['tmp_name'])) {
        
        $tmp_name = $_FILES[$this->filename]["tmp_name"];
        $name     = $_FILES[$this->filename]["name"];
        $size     = $_FILES[$this->filename]["size"];

        if($size > $this->max_size){
            
            $this->error_msg_queue[] = "Fisierul $this->filename depaseste limita de ".($this->max_size/ ( 1024 * 1024 ) )."MB !";
            
            
        }else{
            
            if($this->checkType()){
                if($this->upload_mode==0){
                    $name = $this->unique_name($this->upload_path,$name);
                    if( !move_uploaded_file( $tmp_name, "{$this->upload_path}/{$name}") ){
                        
                        $this->error_msg_queue[] = "Fisierul $this->filename nu s-a uploadat cu succes!";
                        
                    }else{
                        $this->new_filename = $name;
                        $this->success = true;
                    }
                }elseif($this->upload_mode==1){
                    if( !move_uploaded_file( $tmp_name, "{$this->upload_path}/{$name}") ){
                        
                        $this->error_msg_queue[] = "Fisierul $this->filename nu s-a uploadat cu succes!";
                    
                    }else{
                        $this->new_filename = $name;
                        $this->success = true;
                    }
                }elseif($this->upload_mode==2){
                    
                    if(!file_exists("{$this->upload_path}/{$name}")){
                        if( !move_uploaded_file( $tmp_name, "{$this->upload_path}/{$name}") ){
                            
                            $this->error_msg_queue[] = "Fisierul $this->filename nu s-a uploadat cu succes!";
                        
                        }else{
                            $this->new_filename = $name;
                            $this->success = true;
                        }
                    }else{
                        
                        $this->success = false;
                        
                    }
                }
            }
            
        }
        
           
    }
    
  }
	function getFileType()
	{		
		$fileType = explode("/", $_FILES[$this->filename]['type']);				
		
		return $fileType;
	}
	
	function checkType()
	{
	    $fileType = $this->getFileType();
        
		if( !$this->fileTypeAccepted || in_array($fileType[1],$this->fileTypeAccepted))
			return true;
		else{
            $this->error_msg_queue[] = "Imaginea $this->filename nu este de un format permis! Trebuie sa fie din lista: ".implode(",", $this->fileTypeAccepted);
			return false;
		} 
	}
	
    
    /**
     *
     *  Parses Destination Folder and sufixes the name with the occurences number
     *  Ex: my_document(2).txt,my_document(3).txt 
     *  
     **/
    function unique_name( $folder, $filename) {
        
        $filename = str_replace(" ", "_", $filename);
        $filename = strtolower(basename($filename));
        $destFullPath = $folder . $filename;
        $newFilename = $filename;
        $i = 1;
        if (!file_exists($folder)) {
        	if (!_ffCreateFolder($folder)) {
                $this->success = false;
        		if(DEBUG)
                    $this->error_msg_queue[] = "Folder does not exist: " . $folder;
                else
                    $this->error_msg_queue[] = "S-a produs o eroare in sistem. Incercati mai tarziu va rugam!";
                
        	}
        }
        while (file_exists($destFullPath)) {
        	$file_extension  = strtolower(strrchr($filename, "."));
        	$file_name = basename($filename, $file_extension);
        	$newFilename = $file_name . "($i)" . $file_extension;
        	$destFullPath = $folder . $newFilename;
        	$i++;
        }
        return $newFilename;
    
    }
    
}

?>