<?php
include(LIB_DIR.'upload/upload.class.php');
require_once(LIB_DIR . "/admin/module.php" );
/**
 * 
 * Backend Photo Manager
 * 
 * @package: Upload
 * 
 */
class uplphoto extends adminModule
{
    
    var $tplName                  = "tpl_utile/uplphoto_list.tpl";
    var $moduleName               = "uplphoto";
    var $pagingAction             = "page_list";

    var $tableName 		  = "uplphoto";
    var $idName 		  = "id";
    var $flagName 		  = "active";
    var $priorityName             = "priority";
    var $fileName	 	  = "file";
	
    var $tableFields              = array("title", "priority", "def", "active", "gallery_id", "media_type", "video_src", "video_code");
    
    var $methodsMap = array("page_edit", "delete", "create_thumbnail", "resizeThumbnailImage", "video_preview", "change");

    var $uploadDir		  = PHOTOS_UPLOAD_DIR;		

        
        function page_edit(){
            global $smarty;
            $err=0;
            $db=SDatabase::getInstance();
            objInitVar($this, "tpl_utile/uplphoto_edit.tpl", "", "", "", "", "");
	           if(isset($_SESSION[SESS_IDX]['reload_upl']) && $_SESSION[SESS_IDX]['reload_upl'] == 1)
               {
                    $smarty->assign("reload", 1);
                    unset($_SESSION[SESS_IDX]['reload_upl']);
               }
            if(!empty($_GET['owner']) && !empty($_GET['owner_id']))
            {
                $owner    = $db->getEscaped($_GET['owner']);
                $owner_id = $db->getEscaped($_GET['owner_id']);

                $form_act = array();
                if(!empty($_GET[$this->idName]) && is_numeric($_GET[$this->idName]))//form update
                {
                    $id = $db->getEscaped($_GET[$this->idName]);

                    //===>get info
                    $q="SELECT * FROM uplphoto WHERE $this->idName=$id and owner='$owner'";
                    $db->setQuery($q);
                    $form_act=$db->loadAssoc();
                    if( $form_act )
                    {
                        $form_act['act'] = 'upd';
                        //var_dump($form_act);
                        
                        $current_large_image_width = $this->getWidth(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']);
                        $current_large_image_height = $this->getHeight(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']);

                        $smarty->assign("current_large_image_width", $current_large_image_width);
                        $smarty->assign("current_large_image_height", $current_large_image_height);

                        $large_photo_exists = "<img src=\"".ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']."\" alt=\"Large Image\"/>";
                        $smarty->assign("large_photo_exists", $large_photo_exists);
                    }
                    //<===
                }
                //<===

                $smarty->assign("form_act", htmlArrayFilter($form_act));
            }else 
                $err=2;
            
            $smarty->assign("err", $err);
            $smarty->display($this->tplName);
            exit;
        }
		
	function page_act()
	{
            global $smarty;
            $err=0;
            objInitVar($this, "tpl_utile/uplphoto_act.tpl", "", "", "", "", "");
            $db = &SDatabase::getInstance();
		
            if(!empty($_GET['owner']) && !empty($_GET['owner_id']))
            {
                $owner    = $db->getEscaped($_GET['owner']);
                $owner_id = $db->getEscaped($_GET['owner_id']);

                $form_act = array();
                if(!empty($_GET[$this->idName]) && is_numeric($_GET[$this->idName]))//form update
                {
                    $id = $db->getEscaped($_GET[$this->idName]);

                    //===>get info
                    $q="SELECT * FROM uplphoto WHERE $this->idName=$id and owner='$owner'";
                    $db->setQuery($q);
                    $form_act=$db->loadAssoc();
                    if( $form_act)
                    {
                        $form_act['act'] = 'upd';
                        //echo "<pre>";var_dump($form_act);exit;
                        if(isset($form_act['gallery_id']) && $form_act['gallery_id']!="")
                        {
                            $gallery_id = $form_act['gallery_id'];
                            $q="SELECT gallery_name FROM uplgallery WHERE gallery_id = '$gallery_id'";
                            $db->setQuery($q);
                            $galerie = $db->loadObject();
                            if(isset($galerie->gallery_name) && $galerie->gallery_name!="")
                                $form_act['gallery_name']=$galerie->gallery_name;
                        }
                        
                        if($form_act['media_type']!='video_embed')
                        {
                            $current_large_image_width = $this->getWidth(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']);
                            $current_large_image_height = $this->getHeight(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']);

                            $smarty->assign("current_large_image_width", $current_large_image_width);
                            $smarty->assign("current_large_image_height", $current_large_image_height);
                        }

                        $large_photo_exists = "<img src=\"".ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$form_act['file']."\" alt=\"Large Image\"/>";
                        $smarty->assign("large_photo_exists", $large_photo_exists);
                    }
                    //<===
                }else{
                        if(isset($_POST))
                            $form_act=$_POST;
                        $form_act['act'] = 'add';
                        $q="SELECT max(priority) as priority FROM uplphoto";
                        $db->setQuery($q);
                        $priort=$db->loadAssoc();
                        $form_act['priority']=(int)$priort['priority']+1;
                        if(isset($_GET['title']))
                            $form_act['title'] = $db->getEscaped($_GET['title']);
                }
                //<===

                $smarty->assign("form_act", htmlArrayFilter($form_act));
            }else 
                $err=2;
            
            $smarty->assign("err", $err);
            $smarty->display($this->tplName);
            exit;
	}		
	
	function page_list()
	{
            global $smarty;
            $err=0;
            $db=SDatabase::getInstance();
            if(!empty($_GET['owner']) && !empty($_GET['owner_id']))
            {
                $owner      = $db->getEscaped($_GET['owner']);
                $owner_id   = $db->getEscaped($_GET['owner_id']);

                $this->moduleName .= $owner.$owner_id;

                objInitVar($this, "tpl_utile/uplphoto_list.tpl", "", "", "", "", "");

                if(!empty($_GET['noColumns']) && is_numeric($_GET['noColumns']))
                    $noColumns=$_GET['noColumns'];
                else 
                    $noColumns=3;

                if(!empty($_GET['noRecPage']) && is_numeric($_GET['noRecPage']))
                    $noRecPage=$_GET['noRecPage'];
                else 
                    $noRecPage = 6;


                //===>get list
                $sqlLimit = paging($this->moduleName, $this->pagingAction, $noRecPage);
                $q="SELECT {$this->idName} FROM $this->tableName WHERE owner='{$owner}' AND owner_id='{$owner_id}' ORDER BY {$this->priorityName}, {$this->idName}";
                $db->query($q);
		        $_SESSION[SESS_IDX][$this->moduleName]['paging']['noRowsResult']=$db->getNumRows();

                $q="SELECT * FROM $this->tableName WHERE owner='{$owner}' AND owner_id='{$owner_id}' ORDER BY {$this->priorityName}, {$this->idName} {$sqlLimit}";
                $db->setQuery($q);
                $recList = $db->loadAssocList();
                foreach($recList as &$rec){
                    $rec["file_size"] = filesize( $this->uploadDir.$rec["file"] );
                    $rec["file_size"] = number_format($rec["file_size"]/1024,4)." kb";
                }

                $smarty->assign("recList", $recList);
                $smarty->assign("paging_options", "&owner={$owner}&owner_id={$owner_id}&noRecPage={$noRecPage}&noColumns={$noColumns}");
                $smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);
            }else
                $err=1;

            $smarty->assign("err", $err);

            $smarty->display($this->tplName);
            exit;
	}
	
	function add_upd()
	{
            $db = &SDatabase::getInstance();
            $owner                = $db->getEscaped($_GET['owner']);
            $owner_id             = $db->getEscaped($_GET['owner_id']);
            $add_new              = getFromRequest($_POST,"add_new",0);
            $backToEditForm       = getFromRequest($_POST,"backToEditForm",0);
        
            if(isset($_POST['act']) && ($_POST['act']=='add' || $_POST['act']=='upd'))
            {

                $files=array();
                foreach ($_FILES['file']['name'] as $key => $item) 
                {
                    $files[$key]['name']=$_FILES['file']['name'][$key];
                    $files[$key]['type']=$_FILES['file']['type'][$key];
                    $files[$key]['tmp_name']=$_FILES['file']['tmp_name'][$key];
                    $files[$key]['error']=$_FILES['file']['error'][$key];
                    $files[$key]['size']=$_FILES['file']['size'][$key];
                }

                foreach ($files as $key=>$item) 
                {
                    unset($_FILES);
                    $_FILES['file']=$item;  
                    
                    #===>INSERT/UPDATE GALLERY 
                        if(isset($_POST['gallery_id']) && $_POST['gallery_id']!="")
                        {
                            $nume_gal = $db->getEscaped($_POST['gallery_id']);
                            $db->setQuery("SELECT gallery_id FROM uplgallery WHERE gallery_name = '$nume_gal'");
                            $galerie = $db->loadObject();
                            if(isset($galerie->gallery_id) && $galerie->gallery_id!="")
                            {
                                $_POST['gallery_id']=$galerie->gallery_id;
                            }
                            else
                            {
                                $db->setQuery("SELECT max(gallery_ordering) as ordine FROM uplgallery");
                                $order = $db->loadObject();
                                if(!$order->ordine)
                                    $ordine=1;
                                else
                                    $ordine=$order->ordine+1;
                                $q="INSERT INTO uplgallery SET gallery_name = '$nume_gal', gallery_ordering='$ordine'";
                                $db->setQuery($q);
                                $db->query();
                                $_POST['gallery_id']=$db->insertid();
                            }
                        }
                    #===>INSERT/UPDATE GALLERY
                    
                    $q_tmp = objPrepareTableFields($this);
                    
                    if(isset($_POST['act']) && $_POST['act']=='add')
                    {
                        $q="INSERT INTO 
                                $this->tableName
                            SET
                                owner='$owner',
                                owner_id='$owner_id',
                                $q_tmp
                        ";
                        $db->query($q);
                        $id=$db->insertid();
                    }
                    elseif(isset($_POST['act']) && $_POST['act']=='upd' && !empty($_POST[$this->idName]) && is_numeric($_POST[$this->idName]))
                    {
                        $id=$db->getEscaped($_POST[$this->idName]);
                        $q="SELECT * FROM uplphoto WHERE $this->idName=$id AND owner=$owner";
                        $db->setQuery($q);
                        $info=$db->loadAssocList();
                        if($info)
                        {
                            $q="UPDATE
                                    $this->tableName
                                SET
                                    $q_tmp
                                WHERE
                                    {$this->idName}=$id
                            ";
                            $db->query($q);
                        }
                    }
                    if(!empty($id))
                    {
                        //===>upload photo
                        $fileUpl = $this->fileName;
                        if (isset($_POST['title']) && $_POST['title']!="")
                        {
                            $file_name = str_replace("-", "_", seo_link($_POST['title'], $id));
                        }
                        else
                            $file_name = "{$owner}_{$owner_id}_{$id}";
                            
                        //===>upload photo
                            $fileUpl = $this->fileName;
                            $obj = new upload($fileUpl, $this->uploadDir, $file_name);
                            $obj->addExtForDst();
                            if($obj->process($this, "page_act"))
                            {
                                $fileDst = $obj->getFileDst();
                                unset($obj);
                                
                                $q="SELECT $fileUpl FROM $this->tableName WHERE $this->idName = $id";
                                $db->setQuery($q);
                                $old_file = $db->loadAssoc();
                                if($old_file!='')
                                {
                                    if($old_file!=$fileDst)
                                        _ffRemoveFile($this->uploadDir.$old_file);
                                }               
                                
                                $q="UPDATE $this->tableName SET $fileUpl='$fileDst' WHERE $this->idName=$id ";
                                $db->query($q);
                            }
                        //<=== end upl file

                            
                        //===>UPLOAD ORIGINAL
                            $fileUpl = $this->fileName;
                            $obj = new upload($fileUpl, UPLOAD_DIR."original/", $file_name);
                            $obj->addExtForDst();
                            if($obj->process($this, "page_act"))
                            {
                                $fileDst = $obj->getFileDst();
                                unset($obj);            
                                
                            }
                    }
                    else{
                        systemMessage::addMessage("S-a produs o eroare la adaugarea pozei!");
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                if($add_new==1){

                    redirect("index.php?obj={$_GET['obj']}&action=page_act&owner={$_GET['owner']}&owner_id={$_GET['owner_id']}&title={$_POST['title']}&reload=1");

                }else{

                    if($backToEditForm){
                        redirect("index.php?obj={$_GET['obj']}&action=page_act&owner={$_GET['owner']}&owner_id={$_GET['owner_id']}&{$this->idName}={$id}&title={$_POST['title']}&reload=1");
                        exit;
                    }else{
                        redirect("index.php?obj={$_GET['obj']}&action=page_act&owner={$_GET['owner']}&owner_id={$_GET['owner_id']}&title={$_POST['title']}&close=1");
                        exit;
                    }

                }
            }
	}
	
	/**
	 * Delete
	 *
	 * @access: public
	 * @return: null
	*/
	function delete()
	{
        $db=Sdatabase::getInstance();
		if(!empty($_GET[$this->idName]) && is_numeric($_GET[$this->idName]))
		{
            $q="SELECT file FROM $this->tableName WHERE $this->idName = '{$_GET[$this->idName]}'";
            $db->setQuery($q);
            $rezultat=$db->loadAssoc();
            //var_dump($rezultat);exit;
            $file=$rezultat['file'];
			if($file)
			{
				if(is_file(ROOT_DIR.PUBLIC_FOLDER."upl/original/".$file)){
					_ffRemoveFile(ROOT_DIR.PUBLIC_FOLDER."upl/original/".$file);
				}
				if(is_file(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$file)){
					_ffRemoveFile(ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$file);
				}
				if (is_file($this->uploadDir.$_GET['owner'].'/small/'.$file))
				{
		        	_ffRemoveFile($this->uploadDir.$_GET['owner'].'/small/'.$file);
				}

				if (is_file($this->uploadDir.$_GET['owner'].'/medium/'.$file))
				{
					_ffRemoveFile($this->uploadDir.$_GET['owner'].'/medium/'.$file);
				}
				
				if (is_file($this->uploadDir.$_GET['owner'].'/large/'.$file))
				{
		        	_ffRemoveFile($this->uploadDir.$_GET['owner'].'/large/'.$file);
				}
			}
                $q="DELETE FROM $this->tableName WHERE $this->idName = '{$_GET[$this->idName]}'";
	           $db->query($q);
               redirect($_SERVER['HTTP_REFERER']);
		}
	}

	//You do not need to alter these functions
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}
	//You do not need to alter these functions
	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}

	function create_thumbnail () {

		if (isset($_POST["upload_thumbnail"]) && $_POST["upload_thumbnail"]!="") {
			//Get the new coordinates to crop the image.
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
                        
			$poza = ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$_POST['poza'];
			$thumb = ROOT_DIR.PUBLIC_FOLDER."upl/photos/thumb_".$_POST['poza'];
			//Scale the image to the thumb_width set above
			$thumb_width = 400;
			$scale = $thumb_width/$w;
			$cropped = $this->resizeThumbnailImage($poza, $poza,$w,$h,$x1,$y1,$scale);
			//exit;
			//Reload the page again to view the thumbnail
			//var_dump($_SERVER);exit;
            $_SESSION[SESS_IDX]['reload_upl'] = 1;
			redirect($_SERVER["HTTP_REFERER"]);
			exit();
		}

        if(isset($_POST['restore_original']) && $_POST['restore_original']=='Restore') {
            $fileUpl = ROOT_DIR.PUBLIC_FOLDER."upl/original/".$_POST['poza'];
            $target= ROOT_DIR.PUBLIC_FOLDER."upl/photos/".$_POST['poza'];
            resize_picture($fileUpl, 0, 0, $target, "crop");
            $_SESSION[SESS_IDX]['reload_upl'] = 1;
            redirect($_SERVER["HTTP_REFERER"]);
            exit();
        }


		if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
		//get the file locations 
			$large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
			$thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'];
			if (file_exists($large_image_location)) {
				unlink($large_image_location);
			}
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
			//header("location:".$_SERVER["PHP_SELF"]);
			exit(); 
		}
	}

	//You do not need to alter these functions
	function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
	  	}
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) {
			case "image/gif":
		  		imagegif($newImage,$thumb_image_name); 
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
	    }
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}
    
    function video_preview()
	{
		global $smarty;
		objInitVar($this, "tpl_utile/uplvideo_preview.tpl", "", "", "", "", "");
        
        $smarty->display($this->tplName);
    }

    function change() {
        //pr($_GET);exit;
        if (isset($_GET[$this->idName]) && $_GET[$this->idName] != '' && isset($_GET['fieldName']) && $_GET['fieldName'] != '') {

            systemMessage::addMessage("Status Switched!");
            $db = SDatabase::getInstance();
            $fieldname = filter_var($_GET['fieldName'], FILTER_SANITIZE_STRING);
            if ((int)$_GET[$this->idName] != 0) {
                $id = filter_var($_GET[$this->idName], FILTER_SANITIZE_NUMBER_INT);
                $q = "UPDATE $this->tableName SET $fieldname=($fieldname+1)%2 WHERE $this->idName='$id'";
                $db->setQuery($q);
                $db->query();
            }
        }
        redirect("index.php?obj=uplphoto&action=page_list&owner=".$_GET['owner']."&owner_id=".$_GET['owner_id']."&noRecPage=".$_GET['noRecPage']."&noColumns=".$_GET['noColumns']);
    }
}
?>