<?php

//#########################################################################//
//# Utile for file-folders system
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 22.01.2004
//#########################################################################//

/**
 * Get files from a folder 
 *
 * @param: $dir = folder name | $like = files match
 * @access: public
 * @return: array
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffGetDirFiles($dir, $match='')
{	
	$vectFiles=array();
	$dh=opendir($dir);	
	if($match!='')
	{
		while($file=readdir($dh))
			if(strstr($file, $match))
				$vectFiles[]=$file;
	}
	else 
	{		
		while($file=readdir($dh))
			$vectFiles[]=$file;
	}
	unset($dh);
	
	return $vectFiles;	
}

/**
 * Write logs in a file
 *
 * @param: $logFile = log file | $str = log string
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffWriteLog($logFile, $str)
{
	$fp=fopen($logFile, "a");
	fputs($fp, "\n\r#"._dtGetDate("d-m-Y H:i:s")."\n".$str."\n");
	fclose($fp);
}

/**
 * Write a file
 *
 * @param: $fileName = file | $str = string
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffWriteFile($fileName, $str) 
{ 
	@$fp = fopen($fileName, "w"); 
	if($fp) 
	{ 
		fwrite($fp, $str, strlen($str));
		fclose($fp);
	}
	else
	{
		echo "Invalid file : $fileName<br>\n";		
		return false;
	}
} 

/**
 * Read file content
 *
 * @param: $fileName = file 
 * @access: public
 * @return: file content
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffReadFile($fileName)
{ 
	if(is_file($fileName) && file_exists($fileName))
		return implode("", file($fileName));
	else
	{
		echo "Invalid file : $fileName<br>\n";		
		return false;
	}
}

/**
 * Get File Content
 *
 * @param: $filePath = file path
 * @access: public
 * @return: file content
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffGetFileContent($filePath) 
{    
    if(is_file($filePath) && file_exists($filePath))
		return addslashes(fread(fopen($filePath, "r"), filesize($filePath)));
	else
	{
		echo "Invalid file : $filePath<br>\n";
		return false;
	}
}

/**
 * Remove a file
 *
 * @param: $fileName = file | $displayError = status dispaly error
 * @access: public
 * @return: file content
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffRemoveFile($filePath, $displayError=0)
{ 
	if(is_file($filePath) && file_exists($filePath))
	{        		
		$bool=unlink($filePath);
		if($bool)
			return true;
		else
		{
			if($displayError==1)
				echo "<br>The file {$filePath} cannot be removed!";
			return false;
		}
	}
	else
	{
		if($displayError==1)
			echo "<br>The file {$filePath} not exist!";
		return false;
	}
}

/**
 * Remove a entry folder
 *
 * @param: $folder
 * @access: public
 * @return: file content
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _ffRemoveFolder($folderPath)
{			
	$folderPath = trim($folderPath);
	if($folderPath!='')
	{		
		$l = strlen($folderPath);
		
		$lastChar = substr($folderPath, $l-1, $l);
		if($lastChar!='/') $folderPath .= '/';
		
		if(file_exists($folderPath))
		{
			$d = dir($folderPath);
			while($entry = $d->read()) 
			{
				if ($entry!= "." && $entry!= "..") 
				{  
					if(is_dir($folderPath.$entry))
						_ffRemoveFolder($folderPath.$entry);
					else
						_ffRemoveFile($folderPath.$entry);					
				} 
			}
			$d->close();
			
			rmdir($folderPath); 
		}
	}
}

/**
 * Get file extension
 *
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 30.03.2005 (dd.mm.YYYY)
*/
function _ffGetFileExtension($fileName)
{
	$tmp = explode('.', $fileName);
	$nop = count($tmp);
	if($nop > 1)
		return $tmp[$nop-1];
	else 
		return '';
}

/**
* 
* @param string $dir
* @param string $mode
* @return boolean
*/
function _ffCreateFolder($dir, $mode = 0777) {
if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
if (!_ffCreateFolder(dirname($dir), $mode)) return FALSE;
return @mkdir($dir, $mode);
}

?>