<html>
<head>
<style>
	body a { font-size:10px; text-decoration:none; }
</style>
</head>
<body>

<form action="mcephotos.php" method="post" enctype="multipart/form-data" name="upload" id="upload">
  	<input type="file" name="uploaded_file" /> <input type="submit" name="upload" value="Uploadeaza" />
</form>

<?php
	include	'config.php'; 
	
	if (isset($_FILES["uploaded_file"]) && is_uploaded_file($_FILES["uploaded_file"]['tmp_name']))
	{
		$filename = basename($_FILES['uploaded_file']['name']);		
	    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], UPLOAD_DIR."mcephotos/".date("d-m-Y_H:i")."_".$filename);
    }
  	 
    if ($handle = opendir(UPLOAD_DIR."mcephotos/"))
    {
    	while (false !== ($file = readdir($handle)))
    	{
			if ($file != "." && $file != "..")
	    		echo "<a target='_blank' href=".ROOT_HOST."upl/mcephotos/"."$file\n>".ROOT_HOST."upl/mcephotos/"."$file\n</a><br /><hr>";
    	}
    	closedir($handle);
    }
?>
</body>
</html>