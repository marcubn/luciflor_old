<?php
//#########################################################################//
//# Upload file class
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 30.03.2005
//#########################################################################//

include(LIB_DIR.'image/image.class.php');

if(!defined("UPLOAD_SIZE_LIMIT")) define("UPLOAD_SIZE_LIMIT", 4*1024*1024);
if(!defined("UPLOAD_IMG_W_LIMIT")) define("UPLOAD_IMG_W_LIMIT", 3000);
if(!defined("UPLOAD_IMG_H_LIMIT")) define("UPLOAD_IMG_H_LIMIT", 3000);

ini_set("memory_limit","300M");

class upload extends image
{	
	var $fileTypeAccepted = ''; //file type condition 
	
	var $fileSrc 	 	  = ''; //name of uploaded file
	var $fileUpd 	 	  = ''; //name of element type file
	var $fileDst	 	  = ''; //name of destination file
	var $folderDst	 	  = '';	//name of destination folder
		
	var $prefixFileDst 	  = '';	//set a prefix for fileDst
	
	var $flagAddExtForDst = 0;  //flag ext for set 
	
	var $sizeLimit		  = 0;  //0 - indiferent, '' - default
	var $imgWLimit	 	  = 0;  //0 - indiferent, '' - default
	var $imgHLimit	 	  = 0;  //0 - indiferent, '' - default
	
	var $errCode		  = '';
	
	var $fileType		  = ''; 
	
	var $fileSize		  = 0;
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function upload($fileUpl, $folderDst, $fileDst='')
	{
    	 $this->fileUpl		= $fileUpl;
    	 $this->fileDst		= $fileDst;
    	 $this->folderDst 	= $folderDst;
	}
	
	/**
	 * Process file uploaded
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function process($obj=null, $action="", $x_size=0, $y_size=0, $type="crop")
	{
		//uploaded condition
		if(is_uploaded_file($_FILES[$this->fileUpl]['tmp_name']))
		{
			$this->fileSrc = $_FILES[$this->fileUpl]['name'];
			$this->fileDst = $this->prefixFileDst.($this->fileDst!='' ? $this->fileDst : $this->fileSrc);
			
			if($this->flagAddExtForDst==1)
				$this->fileDst .= '.'.$this->getFileExtension($this->fileSrc);
			
			$this->fileSize = $_FILES[$this->fileUpl]['size'];
			
			//uploaded size condition > 0
			if($this->fileSize > 0)
			{								
				//uploaded particular size condition
				if($this->fileSize <= $this->sizeLimit || $this->sizeLimit===0)
				{
					$this->fileType = $this->getFileType();					
					
					//check particular file type condition
					if($this->checkType($this->fileType))
					{
						if ($this->fileType[0]=="image" && $this->fileType[1]!="gif")
						{
							if (isset($obj) && $obj!=null)
							{
								if (isset($_FILES[$this->fileUpl]['size']) && $_FILES[$this->fileUpl]['size']>UPLOAD_SIZE_LIMIT)
								{
									global $smarty;
									$smarty->assign("msgErr", "The file size must be under ".number_format(UPLOAD_SIZE_LIMIT/1024/1024, 2)."MB. Your file is ".number_format($_FILES[$this->fileUpl]['size']/1024/1024, 2)."MB");
									eval("\$obj->\$action();");
									exit;
								}
								$uploadedfile = $_FILES[$this->fileUpl]['tmp_name'];
								
								if($this->fileType[1]=='pjpeg' || $this->fileType[1]=='jpg' || $this->fileType[1]=='jpeg')
								{
									if ($src = imagecreatefromjpeg($uploadedfile))
									{}
									else 
									{
										global $smarty;
										$smarty->assign("msgErr","File too big or not in {$this->fileType[1]} format");
										eval("\$obj->\$action();");
										exit;
									}
								}
								elseif($this->fileType[1]=='png')
								{
									if ($src = @imagecreatefrompng($uploadedfile))
									{}
									else 
									{
										global $smarty;
										$smarty->assign("msgErr","This file is not {$this->fileType[1]} format");
										eval("\$obj->\$action();");
										exit;
									}
								}
								elseif($this->fileType[1]=='wbmp')
								{
									if ($src = @imagecreatefromwbmp($uploadedfile))
									{}
									else 
									{
										global $smarty;
										$smarty->assign("msgErr","This file is not {$this->fileType[1]} format");
										eval("\$obj->\$action();");
										exit;
									}
								}
								elseif($this->fileType[1]=='gif')
								{
									$strImg = $this->getStrImg($uploadedfile);
									if ($src = @imagecreatefromstring($strImg))
									{}
									else 
									{
										global $smarty;
										$smarty->assign("msgErr","This file is not {$this->fileType[1]} format");
										eval("\$obj->\$action();");
										exit;
									}
								}
								else 
								{
									global $smarty;
									$smarty->assign("msgErr","File type {$this->fileType[1]} not suported");
									$_POST['act']='';
									eval("\$obj->\$action();");
									exit;
								}
								list($width, $height)=getimagesize($uploadedfile);
								if ($width>UPLOAD_IMG_W_LIMIT || $height>UPLOAD_IMG_H_LIMIT)
								{
									global $smarty;
									$smarty->assign("msgErr","Image too big. Maximum size ".UPLOAD_IMG_W_LIMIT." x ".UPLOAD_IMG_H_LIMIT);
									unset($_POST['act']);
									eval("\$obj->\$action();");
									exit;
								}
								
								
								if ($x_size>0 && $y_size>0)
								{
									if ($type=="thumb")
									{
										$quef1=$y_size/$height;
										$quef2=$x_size/$width;
										
										if($quef1<$quef2)
											$quef=$quef1;
										else
											$quef=$quef2;
											
										$newwidth=$width*$quef;
										$newheight=$height*$quef;
										
										$tmp=imagecreatetruecolor($newwidth,$newheight) or imagecreate($newwidth,$newheight);
										imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
									}
									else
									{
										$newW=$x_size;
										$newH=$y_size;
										
										$quef1=$width/$x_size;
										$quef2=$height/$y_size;
										
										if($quef1>=$quef2)
										{
											$newX=($x_size/$width)*$width;
											$newY=$y_size;
										}
										else
										{
											$newX=$x_size;
											$newY=($y_size/$height)*$height;
										}
										
										if($newX>$x_size)
										{
											$tmpX=($newX-$x_size)/2;
											$tmpY=0;
										}
										else 
										{
											$tmpX=0;
											$tmpY=($newY-$y_size)/2;
										}
										
										$objImgTmp=imagecreatetruecolor($newX, $newY) or imagecreate($newX, $newY);
										imagecopyresampled($objImgTmp, $src, 0, 0, 0, 0, $newX, $newY, $width, $height);
										
										$tmp=imagecreatetruecolor($newW, $newH) or imagecreate($newW, $newH);
										imagecopyresampled($tmp, $objImgTmp, 0, 0, $tmpX, $tmpY, $newW, $newH, $newW, $newH);
										imagedestroy($objImgTmp);
									}
									
								}
								else 
								{
									if ($width >= IMAGE_UPLOAD_RESIZE && $height >= IMAGE_UPLOAD_RESIZE)
									{
										if ($width>=$height)
										{
											$newwidth=IMAGE_UPLOAD_RESIZE;
											$newheight=($height/$width)*IMAGE_UPLOAD_RESIZE;
										}
										else
										{
											$newheight=IMAGE_UPLOAD_RESIZE;
											$newwidth=($width/$height)*IMAGE_UPLOAD_RESIZE;
										}
									}
									elseif ($width > IMAGE_UPLOAD_RESIZE)
									{
										$newwidth=IMAGE_UPLOAD_RESIZE;
										$newheight=($height/$width)*IMAGE_UPLOAD_RESIZE;
									}
									elseif ($height > IMAGE_UPLOAD_RESIZE)
									{
										$newheight=IMAGE_UPLOAD_RESIZE;
										$newwidth=($width/$height)*IMAGE_UPLOAD_RESIZE;
									}
									else 
									{
										$newwidth=$width;
										$newheight=$height;
									}
									$tmp=imagecreatetruecolor($newwidth,$newheight) or imagecreate($newwidth,$newheight);
									imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
								}
								
								$filename = $this->folderDst.$this->fileDst;
								
								if($this->fileType[1]=='pjpeg' || $this->fileType[1]=='jpg' || $this->fileType[1]=='jpeg')
									imagejpeg($tmp,$filename,100);
								elseif($this->fileType[1]=='png')
									imagepng($tmp,$filename);
								elseif($this->fileType[1]=='wbmp')
									image2wbmp($tmp, $filename);
								elseif($this->fileType[1]=='gif')
								{
									imagegif($tmp, $filename);
								}
								chmod($filename, 0777);
								
								imagedestroy($src);
								imagedestroy($tmp);
							}
							else 
							{
								$vectArgs = func_get_args();
								$this->processImage($vectArgs);
							}
						}
						else
						{
							$this->moveUploadFile();
						}
					}
					else 
						$this->errCode = 4;
				}
				else 
					$this->errCode = 3;
			}
			else 
				$this->errCode = 2;
		}
		elseif(isset($_FILES[$this->fileUpl]['size']) && $_FILES[$this->fileUpl]['size']>0)
			$this->errCode = 1;
		else
			$this->errCode = 1;
		
		if($this->errCode=='')
			return true;
		else
		{
			//echo $this->errorMsg($this->errCode);
			//exit;
			return false;
		}
	}
	
	/**
	 * Get name of file dst
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function getFileDst()
	{
		return $this->fileDst;
	}
	
	/**
	 * Get file size
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 21.07.2006 (dd.mm.YYYY)
	*/
	function getFileSize()
	{
		return $this->fileSize;
	}
	
	/**s
	 * Set a limit size for a file
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function setSizeLimit($value=0)
	{
		$this->sizeLimit = $value;
	}
	
	/**
	 * Set a prefix for file dest
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function setPrefixFileDst($prefix='')
	{
		$this->prefixFileDst = $prefix;
	}
	
	/**
	 * Set type of file accepted
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function setFileTypeAccepted($value='')
	{
		$this->fileTypeAccepted = $value;
	}
	
	/**
	 * Add extension for file dst
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function addExtForDst()
	{
		$this->flagAddExtForDst=1;
	}
	
	/**
	 * Set WH limit for images
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function imgWHLimit($imgWLimit=0, $imgHLimit=0)
	{
		if($imgWLimit==='')
			$imgWLimit = UPLOAD_IMG_W_LIMIT;
		if($imgHLimit==='')
			$imgHLimit = UPLOAD_IMG_H_LIMIT;
		
		$this->imgWLimit = $imgWLimit;
		$this->imgHLimit = $imgHLimit;
	}
	
	/**
	 * Get file Type
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function getFileType()
	{		
		$fileType = explode("/", $_FILES[$this->fileUpl]['type']);				
		
		return $fileType;
	}
	
	/**
	 * Check type of file uploaded
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function checkType($vType)
	{
		if($this->fileTypeAccepted=='' || $this->fileTypeAccepted==$vType[0])
			return true;
		else 
			return false;
	}
	
	/**
	 * Move uploaded file
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function moveUploadFile()
	{
		if(move_uploaded_file($_FILES[$this->fileUpl]['tmp_name'], $this->folderDst.$this->fileDst))
		{
			chmod($this->folderDst.$this->fileDst, 0777);
		}
		else 
		{
			$this->errCode = 5;
		}
	}
	
	/**
	 * Process Image
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function processImage($vectArgs=array())
	{						
		if(count($vectArgs)>0 && $vectArgs[0]!='' && $vectArgs[1]!='')
		{
			$objImg = new image();
			
			switch($vectArgs[0])
			{				
				case 'crop':
				{
					$nw = $vectArgs[1];
					$nh = $vectArgs[2];
					
					break;
				}
				case 'thumbnail':
				{
					$nw = $vectArgs[1];
					$nh = $vectArgs[2];
					
					break;
				}
				case 'scale':
				{
					$scale = $vectArgs[1];					
					$img = new image();
					$img->fileType = $this->fileType[1];
					
					if($img->fileType != 'bmp')
						$img->scale($_FILES[$this->fileUpl]['tmp_name'], $this->folderDst.$this->fileDst, $scale);
					else 
						$this->errCode = 6;	
					
					unset($img);
					break;
				}
				case 'scalex':
				{
					$scale = $vectArgs[1];
					
					break;
				}
				case 'scaley':
				{
					$scale = $vectArgs[1];
					
					break;
				}				
			}
		}
		else
		{
			$imgInfo = getimagesize($_FILES[$this->fileUpl]['tmp_name']);
			$w = $imgInfo[0];
			$h = $imgInfo[1];
			
			if(($w <= $this->imgWLimit && $h <= $this->imgHLimit) || ($this->imgWLimit===0 && $this->imgHLimit===0))
			{
				$this->moveUploadFile();
			}
			else 
			{
	  			$img = new image();
	  			
	  			$img->fileType = $this->fileType[1];
	  			
	  			if($img->fileType != 'bmp')
					$this->thumbnail($_FILES[$this->fileUpl]['tmp_name'], $this->folderDst.$this->fileDst, $this->imgWLimit, $this->imgHLimit);
				else
					$this->errCode = 6;
      		 	
				unset($img);
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
	function getFileExtension($fileName)
	{
		$tmp = explode('.', $fileName);
		$nop = count($tmp);
		if($nop > 1)
			return strtolower($tmp[$nop-1]);
		else 
			return '';
				
	}
	
	/**
	 * Error
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function errorMsg($code)
	{
		if($code==1)
			return "File not uploaded";
		elseif($code==2) 
			return "Size of file is zerro";
		elseif($code==3) 
			return "Depasire limita de size";
		elseif($code==4) 
			return "Invalid file type";
		elseif($code==5) 
			return "Fisier != IMAGE nu a putut fi mutat";
		elseif($code==6) 
			return "Files type .bmp are not suported!";
	}
}
/*
$obj = new upload('file1', 'upload', 'file1.jpg');
$obj->setSizeLimit(1000);
$obj->setFileTypeAccepted('image');
$obj->imgWHLimit(200, 300);

$obj->process('thumbnail', 300, 400);
$obj->process('scale', 400);
$obj->process('scalew', 400);
$obj->process('scaleh', 400);
$obj->process('crop', 300, 400);
*/
?>