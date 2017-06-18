<?php
ob_start();	
	$section = "ADMIN";	

	include "../init.php";
	
	//===> check user login
	$redirect = 0;
	if(!isset($_SESSION[SESS_IDX]['UL']) && @$_GET["action"]!="commander" && @$_GET["action"]!="unsubscribe" && @$_GET['action']!="autolog")
	{
		$redirect = 1;
		if( !isset($_GET["obj"]) || (isset($_GET["obj"]) && $_GET["obj"]=='user' && isset($_GET["action"]) && $_GET["action"] == 'page_login') )
			$redirect = 0;
	}
	if($redirect==1)
	{
		redirect('index.php');
		exit;
	}
	//<===
	
	//===> set default value for obj and action
	if(!isset($_GET["obj"]) || (isset($_GET["obj"]) && $_GET["obj"]=='')) 
		$_GET["obj"] = "user";
	if(!isset($_GET["action"]) || (isset($_GET["action"]) && $_GET["action"]=='')) 
		$_GET["action"] = "page_login";
	//<===
	
	//===>
	if(file_exists(COMPONENT_DIR.'admin/'.$_GET["obj"].".class.php"))
	{
		if(DEBUG == 1)
			$profiler->enterSection($_GET["obj"]."-".$_GET["action"]);
		
		include_once COMPONENT_DIR.'admin/'.$_GET["obj"].".class.php";
			
		if($_GET["action"] != "")
			$obj = new $_GET["obj"]($_GET["action"]);
		else 
			$obj = new $_GET["obj"];
	
		if(DEBUG == 1)
			$profiler->leaveSection($_GET["obj"]."-".$_GET["action"]);	
	}
	//<===
	
	//===> DEBUG & PROFILER	
	if(1==DEBUG)
	{
		$profiler->stop();
		
		echo "<br><br><center>";
		$profiler->display();
		echo "</center>";
	}
	//<===
	
	//$db->disconnect();		

	$content_html=ob_get_contents();
		
ob_end_clean();

echo trim($content_html);
?>
