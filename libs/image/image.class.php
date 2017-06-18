<?php
//#########################################################################//
//# Upload file class
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 30.03.2005
//#########################################################################//
//ini_set("memory_limit", "300M");

class image
{
	var $objImgSrc = null;
	var $objImgDst = null;
	var $fileType = null;
	var $quality = 100;
	
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function image($action='')
	{
		if (strpos($_GET['imgSrc'], "upl/")==0)
		{
			$_GET['imgSrc'] = str_replace("upl/", UPLOAD_DIR, $_GET['imgSrc']);
		}
		
		switch($action)
	  	{
	  		case "scaleW":
	  		{
	  			$imgSrc = $_GET['imgSrc'];
	  			$imgDst = isset($_GET['imgDst']) && $_GET['imgDst']!='' ? $_GET['imgDst'] : '';
	  			$scale  = $_GET['scale'];
				$this->scaleW($imgSrc, $imgDst, $scale);
      		 	break;
	  		}
	  		case "scaleH":
	  		{
	  			$imgSrc = $_GET['imgSrc'];
	  			$imgDst = isset($_GET['imgDst']) && $_GET['imgDst']!='' ? $_GET['imgDst'] : '';
	  			$scale  = $_GET['scale'];
				$this->scaleH($imgSrc, $imgDst, $scale);
      		 	break;
	  		}
	  		case "scale":
	  		{
	  			$imgSrc = $_GET['imgSrc'];
	  			$imgDst = isset($_GET['imgDst']) && $_GET['imgDst']!='' ? $_GET['imgDst'] : '';
	  			$scale  = $_GET['scale'];
				$this->scale($imgSrc, $imgDst, $scale);
      		 	break;
	  		}
      		case "thumbnail":
      		{
	  			$imgSrc = $_GET['imgSrc'];
	  			$imgDst = isset($_GET['imgDst']) && $_GET['imgDst']!='' ? $_GET['imgDst'] : '';
	  			if(isset($_GET['scale']) && is_numeric($_GET['scale']))
	  			{
	  				$toW  = $toH  = $_GET['scale'];
	  			}	
	  			else 
	  			{
		  			$toW  = $_GET['toW'];
		  			$toH  = $_GET['toH'];
	  			}
	  			
	  			if(isset($_GET['fill']))
	  				$fill="#".$_GET['fill'];
	  			else 
	  				$fill='';
	  			
				$this->thumbnail($imgSrc, $imgDst, $toW, $toH, $fill);
      		 	break;
      		}
      		case "crop":
      		{
				$imgSrc = $_GET['imgSrc'];
	  			$imgDst = isset($_GET['imgDst']) && $_GET['imgDst']!='' ? $_GET['imgDst'] : '';
	  			$toW  = $_GET['toW'];
	  			$toH  = $_GET['toH'];
				$this->crop($imgSrc, $imgDst, $toW, $toH);
      		 	break;
      		}
	  	}
	}
	
	/**
	 * Scale image from a string 
	 *
	 * @parameters: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 22.01.2004 (dd.mm.YYYY)
	*/
	function getStrImg($file)
	{
		$strFile = fread(fopen($file, "r"), filesize($file));
		return $strFile;
	}
	
	/**
	 * Set obj img scr
	 *
	 * @param: $imgSrc = image src
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 22.01.2004 (dd.mm.YYYY)
	*/
	function setObjImgScr($imgSrc)
	{
		$this->objImgSrc=null;
		
		if(isset($this->fileType) && $this->fileType=='bmp')
		{			
			$this->objImgSrc = @imagecreatefromwbmp($imgSrc);			
			imagejpeg($objImgSrc, '', $this->quality);
		}
		else 
		{
			if($this->fileType=='')
			{
				$this->fileType=preg_replace("/(.*)\.(.*)/", "$2", $imgSrc);
				$this->fileType=strtolower($this->fileType);
			}
			
			if($this->fileType=='pjpeg' || $this->fileType=='jpg' || $this->fileType=='jpeg')
				$this->objImgSrc = @imagecreatefromjpeg($imgSrc);
			elseif($this->fileType=='png')
				$this->objImgSrc = @imagecreatefrompng($imgSrc);
			elseif($this->fileType=='wbmp')
				$this->objImgSrc = @imagecreatefromwbmp($imgSrc);
			elseif($this->fileType=='gif')
			{
				$strImg = $this->getStrImg($imgSrc);
				$this->objImgSrc = @imagecreatefromstring($strImg);
			}
			else 
			{
				if($strImg = $this->getStrImg($imgSrc))
					$this->objImgSrc = @imagecreatefromstring($strImg);
			}
		}
	}
	
	/**
	 * Scale image by Width
	 *
	 * @param: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function scaleW($imgSrc, $imgDst, $scale)
	{
		$this->setObjImgScr($imgSrc);
		if(!$this->objImgSrc) return false;
		
		$w=imagesx($this->objImgSrc);
	    $h=imagesy($this->objImgSrc);
	    
	    $coefSrc = $w/$h;
	    
	    $newW=$scale;
	    $newH=round($scale*(1/$coefSrc));
	    
	    $this->objImgDst=@imagecreatetruecolor($newW,$newH) or imagecreate($newW,$newH);
	    imagecopyresampled($this->objImgDst, $this->objImgSrc, 0, 0, 0, 0, $newW, $newH, $w, $h);
	    
	    $this->outImg($imgDst);
	}
	
	/**
	 * Scale image by Height
	 *
	 * @param: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function scaleH($imgSrc, $imgDst, $scale)
	{
		$this->setObjImgScr($imgSrc);
		if(!$this->objImgSrc) return false;
		
		$w=imagesx($this->objImgSrc);
	    $h=imagesy($this->objImgSrc);
	    
	    $coefSrc = $w/$h;
	    
	    $newW=round($scale*$coefSrc);
	    $newH=$scale;
	    
	    $this->objImgDst=@imagecreatetruecolor($newW,$newH) or imagecreate($newW,$newH);
	    imagecopyresampled($this->objImgDst, $this->objImgSrc, 0, 0, 0, 0, $newW, $newH, $w, $h);
	    
	    $this->outImg($imgDst);
	}
	
	/**
	 * Scale image
	 *
	 * @param: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function scale($imgSrc, $imgDst, $scale)
	{
		$this->setObjImgScr($imgSrc);
		if(!$this->objImgSrc) return false;
		
	    $w=imagesx($this->objImgSrc);
	    $h=imagesy($this->objImgSrc);
	    
	    $coefSrc = $w/$h;
	    	
	    if($w >= $h){
	        $newW=$scale;
	    	$newH=round($scale*(1/$coefSrc));
	    }
	    else {
	        $newW=round($scale*$coefSrc);
	        $newH=$scale;
	    }
		
	   	$this->objImgDst=@imagecreatetruecolor($newW,$newH) or imagecreate($newW,$newH);
	    imagecopyresampled($this->objImgDst, $this->objImgSrc, 0, 0, 0, 0, $newW, $newH, $w, $h);
	    
	    $this->outImg($imgDst);
	}
	
	/**
	 * Thumbnail image
	 *
	 * @param: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function thumbnail($imgSrc, $imgDst, $toW, $toH, $fill='')
	{
		$this->setObjImgScr($imgSrc);
		if(!$this->objImgSrc) return false;
		
	    $w=imagesx($this->objImgSrc);
	    $h=imagesy($this->objImgSrc);
	   	    
	    $coefW=$toW/$w;
	    $coefH=$toH/$h;
		if($coefW>$coefH)
			$coef=$coefH;
		else
			$coef=$coefW;
			
		$newW=$w*$coef;
		$newH=$h*$coef;
		
		if($fill!='')
		{
			$objImgTmp=imagecreatetruecolor($newW, $newH) or imagecreate($newW, $newH);
			imagecopyresampled($objImgTmp, $this->objImgSrc, 0, 0, 0, 0, $newW, $newH, $w, $h);
			
			$this->objImgDst=imagecreatetruecolor($toW, $toH) or imagecreate($toW, $toH);
			
			$transparent = $this->imageColorAllocateFromHex($this->objImgDst, $fill);//imagecolorallocate($this->objImgDst, 255, 255, 255);
			$white = $this->imageColorAllocateFromHex($this->objImgDst, $fill);//imagecolorallocate($this->objImgDst, 255, 255, 255);
			
			imageFilledRectangle($this->objImgDst, 0, 0, $toW-1, $toH-1, $white);
			imagecolortransparent($this->objImgDst, $transparent);
			
			$xDst=($toW-$newW)/2;
			$yDst=($toH-$newH)/2;
			
			imagecopyresampled($this->objImgDst, $objImgTmp, $xDst, $yDst, 0, 0, $newW, $newH, $newW, $newH);
			
			$this->outImg($imgDst, "png");
		}
		else
		{
			$this->objImgDst=imagecreatetruecolor($newW, $newH) or imagecreate($newW, $newH);
			imagecopyresampled($this->objImgDst, $this->objImgSrc, 0, 0, 0, 0, $newW, $newH, $w, $h);
			$this->outImg($imgDst);
		}
	}
	
	/**
	 * Thumbnail image
	 *
	 * @param: $strImg = image contet | $imgDst = destination path | $scale = scale
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function crop($imgSrc, $imgDst, $toW, $toH)
	{
		$this->setObjImgScr($imgSrc);
		if(!$this->objImgSrc) return false;
		
		$newW=$toW;
		$newH=$toH;
		
	    $w=imagesx($this->objImgSrc);
	    $h=imagesy($this->objImgSrc);
	   	    
	    $coefW=$w/$toW;
		$coefH=$h/$toH;
		if($coefW>$coefH)
		{
			$newX=($toH/$h)*$w;
			$newY=$toH;
		}
		else
		{
			$newX=$toW;
			$newY=($toW/$w)*$h;
		}
		
		if($newX>$toW)
		{
			$tmpX=($newX-$toW)/2;
			$tmpY=0;
		}
		else
		{
			$tmpX=0;
			$tmpY=($newY-$toH)/2;
		}
		
		$objImgTmp=imagecreatetruecolor($newX, $newY) or imagecreate($newX, $newY);
		imagecopyresampled($objImgTmp, $this->objImgSrc, 0, 0, 0, 0, $newX, $newY, $w, $h);
		
		$this->objImgDst=imagecreatetruecolor($newW, $newH) or imagecreate($newW, $newH);
		imagecopyresampled($this->objImgDst, $objImgTmp, 0, 0, $tmpX, $tmpY, $newW, $newH, $newW, $newH);
		
		imagedestroy($objImgTmp);
		
		$this->outImg($imgDst);
	}
	
	/**
	 * Image color alocate from hex code
	 *
	 * @param: $objImg, $hexCode
	 * @access: public
	 * @return: color identifier
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function imageColorAllocateFromHex(&$objImg, $hexCode, $alpha=0)
	{
		$ret=array();
		if(substr($hexCode, 0 , 1)=="#")
		{
			$p1 = substr($hexCode, 1, 2);
			$p2 = substr($hexCode, 3, 2);
			$p3 = substr($hexCode, 5, 2);
		}
		else 
		{
			$p1 = substr($hexCode, 0, 2);
			$p2 = substr($hexCode, 2, 2);
			$p3 = substr($hexCode, 4, 2);
		}
		
		$decColor[0] = hexdec($p1);
		$decColor[1] = hexdec($p2);
		$decColor[2] = hexdec($p3);
		
		if($alpha>0)
			return imagecolorallocatealpha($objImg, $decColor[0], $decColor[1], $decColor[2], $alpha);
		else
			return imagecolorallocate($objImg, $decColor[0], $decColor[1], $decColor[2]);
	}
	
	/**
	 * Out image
	 *
	 * @param: $srcImgDst = image dst | $type = type output
	 * @access: public
	 * @return: null
	 * @author: CFlorin (colotin_f@yahoo.com)
	 * @date: 30.03.2005 (dd.mm.YYYY)
	*/
	function outImg($imgDst="", $type="jpeg")
	{
		if(defined("IMAGE_COPYRIGHT") && IMAGE_COPYRIGHT)
		{
			$w=imagesx($this->objImgDst);
	    	$h=imagesy($this->objImgDst);
			
	    	if (($w > IMAGE_COPYRIGHT_SIZEMIN || $h > IMAGE_COPYRIGHT_SIZEMIN) || (isset($_GET['wm']) && $_GET['wm']==1))
	    	{
		    	$d = round(sqrt(pow($w, 2)+pow($h, 2)));
				$pd = round(($d*5/100));
				$d = $d - $pd;
		    	
				$lTxt=strlen(IMAGE_COPYRIGHT_TEXT);
				
				$tmpFW=imagefontwidth(10)*$lTxt;
				$fSize = floor(($d*10)/$tmpFW);
				
				$tmpFH=imagefontheight($fSize);
				
				$angB = round(atan($w/$h)*(180/pi()));
				$ang = 90 - $angB;
				
				$dim = imagettfbbox ($fSize, $ang, IMAGE_COPYRIGHT_FTYPE, IMAGE_COPYRIGHT_TEXT);

				$min_x= min($dim[0], $dim[2], $dim[4], $dim[6]);
				$max_x= max($dim[0], $dim[2], $dim[4], $dim[6]);
				$width= $max_x-$min_x;
				
				$min_y= min($dim[1], $dim[3], $dim[5], $dim[7]);
				$max_y= max($dim[1], $dim[3], $dim[5], $dim[7]);
				$height= $max_y-$min_y;
				
				
				$c_a=round(cos($ang))*$tmpFH;
				$c_o=round(sin($ang))*$tmpFH;
				
				
				$px=-($min_x-$dim[0])+floor(($w-$width)/2)-2;
				$py=-($min_y-$dim[1])+floor(($h-$height)/2);
				
				
				if(defined("IMAGE_COPYRIGHT_FALPHA"))
					$alpha=IMAGE_COPYRIGHT_FALPHA;
				else 
					$alpha=0;
				
				$textColor=$this->imageColorAllocateFromHex($this->objImgDst, IMAGE_COPYRIGHT_COLOR, $alpha);
			    
				//===> ca sa scriu drept, jos
					$ang = 0;
					$px = $w-117;
					$fSize = 17;
					$py = $h-3;
				//<===
				
				imagettftext($this->objImgDst, $fSize, $ang, $px, $py, $textColor, IMAGE_COPYRIGHT_FTYPE, IMAGE_COPYRIGHT_TEXT);
	    	}
		}
		
		switch($type)
		{
			case "jpeg":
				header("Content-type: image/jpeg");
				imagejpeg($this->objImgDst, $imgDst, $this->quality);
				break;
			case "png":
				header("Content-type: image/png");
				imagepng($this->objImgDst);
				break;
			default:
				header("Content-type: image/jpeg");
				imagejpeg($this->objImgDst, $imgDst, $this->quality);
				break;
		}
		
		imagedestroy($this->objImgSrc);
	    imagedestroy($this->objImgDst);
	}
}
/*
$obj=new image();
$obj->scale('a.jpg', 'b.jpg', 400);
$obj->thumbnail('a.jpg', 'c.jpg', 600, 400);
*/
?>