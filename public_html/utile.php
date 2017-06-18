<?php

if(isset($_GET["obj"]) && $_GET["obj"]=='image')
{
	 
	require "config.php";
	
        
	$checkFileCache = str_replace("/", "_#_", $_SERVER['REQUEST_URI']);
	$dir = substr(md5($checkFileCache),0, 1);
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        $checkFileCache = md5($checkFileCache);

    
	if (!is_file(UPLOAD_DIR."cache/".$dir.'/'.$checkFileCache))
	{

        if (isset($_GET['section']) && in_array($_GET['section'], array('projects')) && isset($_GET['type']) && in_array($_GET['type'], array( 'cover', 'single')))
		{
			$sizes = array(
                'projects' => array(
					'cover'			=> array('w'=>1240, 	'h'=>800, 	'op'=>'crop'),
					'single'		=> array('w'=>1240, 	'h'=>800, 	'op'=>'crop')
				)
			);
            
            $paths = array(
                "projects" => ""
            );

			$_GET['toW'] = $sizes[$_GET['section']][$_GET['type']]['w'];
			$_GET['toH'] = $sizes[$_GET['section']][$_GET['type']]['h'];
			$_GET['action'] = $sizes[$_GET['section']][$_GET['type']]['op'];
		    $_GET['imgSrc'] = PHOTOS_UPLOAD_DIR.$paths[$_GET['section']].$_GET['imgSrc'];
		}

		if (!isset($_GET["action"])) exit;
		
     	if (!is_dir(UPLOAD_DIR."cache/".$dir))
		{
			mkdir(UPLOAD_DIR."cache/".$dir);
			chmod(UPLOAD_DIR."cache/".$dir, 0777);
		}
		
		$_GET['imgDst'] = UPLOAD_DIR."cache/".$dir.'/'.$checkFileCache;
		
		include_once LIB_DIR."image/image.class.php";
		
		if($_GET["action"] != "")
			$obj = new image($_GET["action"]);
		else
			$obj = new image;
	}
    
	if (is_file(UPLOAD_DIR."cache/".$dir.'/'.$checkFileCache))
	{
		header("Content-type: image/jpeg");
		readfile(UPLOAD_DIR."cache/".$dir.'/'.$checkFileCache);
	}
	
}
elseif(isset($_GET['obj']))
{
	ob_start();
		include "init.php";
		
		if($_GET["obj"]=='rtf')
			$compDir = LIB_DIR.'rtf/';
		elseif($_GET["obj"]=='get_file_size')
			$compDir = LIB_DIR.'folder_files/';
		else
			$compDir = COMPONENT_DIR.'utile/';
			
		if(file_exists($compDir.$_GET["obj"].".class.php"))
		{
			include_once $compDir.$_GET["obj"].".class.php";
			
			if($_GET["action"] != "")
				$obj = new $_GET["obj"]($_GET["action"]);
			else
				$obj = new $_GET["obj"];
		}
		
		$db->disconnect();
		
		//===> DEBUG & PROFILER	
		if(1==DEBUG)
		{
			$profiler->stop();
			
			echo "<br><br><center>";
			$profiler->display();
			echo "</center>";
		}
		//<===
		$content_html=ob_get_contents();
	ob_end_clean();
	echo $content_html;
}
?>
