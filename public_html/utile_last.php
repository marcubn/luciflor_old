<?php

//#########################################################################//
//# Like index, but used in some special cases: image, get fiel size...
//#
//# Author: Colotin Florin (E-mail: colotin_f@yahoo.com)
//# Date: 22.01.2004
//#########################################################################//
	
if(isset($_GET["obj"]) && $_GET["obj"]=='image')
{
	error_reporting(E_ALL);
	set_time_limit(0);
	session_start();

	require "config.php";

	//===> DATA BASE CONNEXION for pear environments
        require (LIB_DIR."db/db.legacy.php");
        require_once(LIB_DIR."db/database.php");
        $db = new DB();
	//<===

	//===> LIBRARIES
		require(LIB_DIR."time/time.lib.php");	
		require(LIB_DIR."db/db.lib.php");
	//<===

	if(!isset($_GET['noupl']))
	{
		if(substr($_GET['imgSrc'], 0, 4)=='upl/')
		{
			$tmp=str_replace('upl/', '', $_GET['imgSrc']);
			$_GET['imgSrc']=UPLOAD_DIR.$tmp;
		}
		elseif($_GET['imgSrc']!="img/site/no_image.jpg" && $_GET['imgSrc']!="img/admin/utile/noimage.jpg")
		{ 
			$tmp=str_replace(UPLOAD_DIR, '', $_GET['imgSrc']);
			$_GET['imgSrc']=UPLOAD_DIR.$tmp;
		}
	}	
    //===> Generarea pozei sau preluarea ei din cache
		$checkFileCache = md5( $_SERVER['REQUEST_URI'] );
		//$checkFileCache = str_replace("/", "_#_", $_SERVER['REQUEST_URI']); 
        		
        $filename = ($_SERVER['REQUEST_URI'] ); //server specific
        $extensiontmp = strtolower(substr(strrchr($filename, "."), 1));
		$file_extension = substr($extensiontmp,0,strpos($extensiontmp,"&")); 			
		
		switch($file_extension)
		{
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpe": case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		} 
		
        
		if (!is_file(UPLOAD_DIR."cache/".$checkFileCache))
		{
			$_GET['imgDst']=UPLOAD_DIR."cache/".$checkFileCache;
			
			include_once LIB_DIR."image/image.class.php";
				
			if($_GET["action"] != "")
				$obj = new image($_GET["action"]);
			else
				$obj = new image;
			
			if (!is_file(UPLOAD_DIR."cache/".$checkFileCache))
			{
				unset($_GET['imgDst']);
				
				if($_GET["action"] != "")
					$obj = new image($_GET["action"]);
				else
					$obj = new image;
				exit;
			}
		}
		header("Pragma: public"); // required // leave blank to avoid IE errors or header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); //leave blank to avoid IE errors
		header("Cache-Control: private", false); // required for certain browsers 
		
		header("Content-type: $ctype"); 
		readfile(UPLOAD_DIR."cache/".$checkFileCache);
        exit;
    //<===
}
elseif(isset($_GET["obj"]) && $_GET["obj"]=='download')
{
	if(is_file("config.php"))
		require "config.php";
	else 
		require "../config.php";
		
	if(isset($_GET['filePath']))
	{ 
		if(!isset($_GET['noupl']))
			$filePath=UPLOAD_DIR.$_GET['filePath'];
		else
			$filePath=$_GET['filePath'];
		
		if(is_file($filePath))
		{
			$filename = realpath($filePath); //server specific
			$file_extension = strtolower(substr(strrchr($filename, "."), 1)); 			
			
			switch($file_extension)
			{
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpe": case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
			} 
			
			header("Pragma: public"); // required // leave blank to avoid IE errors or header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); //leave blank to avoid IE errors
			header("Cache-Control: private", false); // required for certain browsers 
			
			header("Content-type: $ctype"); 
			
			header("Content-disposition: attachment; filename=\"".basename($filePath)."\""); 
			header("Content-length: ".filesize($filePath)); 
			header("Content-Transfer-Encoding: Binary");
			
			@readfile("$filePath") or die("File not found.");
		}
		else 
			echo "No file selected!";
	}
	else 
		echo "Invalid get path!";

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
