<?php

//#################//
//# Site Function #//
//#################//

/**
 * Init Site
 *
 * @access: public
 * @return: null
*/
function initSite()
{
	global $smarty, $L;
	$db = &SDatabase::getInstance();
	define("SESS_IDX", SESS_IDX_FE);
	
	//define session 
	if(!isset($_SESSION[SESS_IDX]))
		$_SESSION[SESS_IDX]=array();
	
	abonare_newsletter();
	
	//serialize get parameters
	if(isset($_GET))
		$smarty->assign("getParamSerialized", serializeGetParam());

	check_redirect();
	//===> texte
		$q="SELECT * FROM texte ORDER BY text_id ASC";
                $db->setQuery($q);
		$texte = $db->loadAssocList();
		$texteList = array();
		foreach($texte as $text)
		{
			$texteList[$text['text_id']]['text'] = $text['text_text'];
			$texteList[$text['text_id']]['photos'] = get_pictures("texte", $text['text_id']);
		}
		
		$smarty->assign("texteList", $texteList);
	//<===

	//===> OFFLINE
        if(defined("SAPP_OFFLINE") && SAPP_OFFLINE==true){	
            $smarty->display("site/offline.tpl");
            exit;
        }
    //===> OFFLINE

	//===>ASSIGN SEO
        $db = SDatabase::getInstance();
        $url = "http://".$db->getEscaped($_SERVER["HTTP_HOST"]).($_SERVER["REQUEST_URI"]);
        $db->setQuery("select * from seotable where seo_url = '{$url}'");
        $seo = $db->loadObject();
        $smarty->assign('site_meta_url', $url);
        if(isset($seo->seo_id)){
            $smarty->assign('site_meta_title', $seo->seo_title);
            $smarty->assign('site_meta_h1', $seo->seo_h);
            $smarty->assign('site_meta_description', $seo->seo_description);
            $smarty->assign('site_meta_keywords', $seo->seo_keywords);
        }
    //===>ASSIGN SEO
	
	//smart session assign
	$smarty->assign("session", $_SESSION[SESS_IDX]);
	
	//===> set date format
		$smarty->assign("time", _dtGetTime());
		$smarty->assign("DF", "%d %b %Y ");
		$smarty->assign("DTF", "%d %b %Y %H:%M:%S");
	//<===	
    $SDoc = &SDocument::getInstance();
    $SDoc->setPathWay();
    $smarty->assignByRef("SDoc",$SDoc);

    $SLang =  &SLanguage::getInstance();
    $smarty->assign("lang", $SLang->lang);
    
    $smarty->assign("APP_MESSAGE",systemMessage::renderSystemMessage());
    $smarty->assign("APP_CODE",systemComPipe::getCode());
    $smarty->assign("APP_TRACK_EVENTS",systemMessage::renderTrackEvents());
}

/**
 * Serialize GET parameters
 *
 * @access: public
 * @return: string
*/
function serializeGetParam()
{
	$str_param = "";
	
	if(isset($_GET))
	{	
		$vect_get = array();
		
		foreach ($_GET as $key=>$value)
		{
			if(!isset($vect_get[$key]) && $key!="lang_iso" && $key!="obj" && $key!="act")
				$vect_get[$key] = $value;
		}
		
		foreach ($vect_get as $key=>$value)
		{
			if ($str_param!="")
				$str_param .= "&";
			
			$value = str_replace("&"," ",$value);
			$value = str_replace("="," ",$value);
	
			$str_param .= "$key=$value";
		}
	}
	
	return $str_param;
}

/**
 * Forbidden Access
 *
 * @access: public
 * @return: null
*/
function forbiddenAccess()
{
	global $smarty;
	
	$smarty->display('site/forbidden_access.tpl');
	exit;
}

/**
 * Autolog link
 *
 * @access: public
 * @return: string
*/
function autolog_link($user, $pass, $link)
{
	$param=array();
	$param['user']=$user;
	$param['pass']=$pass;
	$param['link']=$link;

	$return = ROOT_HOST."admin/index.php?obj=user&action=autolog&param=".urlencode(base64_encode(gzdeflate(serialize($param))));
	
	return $return;
}

/**
 * Get all uplphotos for an item
 *
 * @access: public
 * @return: null
*/
/*function get_pictures($table, $id, $no=0)
{
	if ($no>0) $sqlLimit="LIMIT 0, ".$no;
	else $sqlLimit="";
	
	$q="SELECT 
			* 
		FROM 
			uplphoto
		WHERE 			
			owner='{$table}' AND 
			owner_id='{$id}'
		ORDER BY
			def DESC, priority ASC 
		$sqlLimit
	";

	$result=_sqlQuery($q);
	
	$list = array(); $i=-1;
	while($record=$result->fetchRow(DB_FETCHMODE_ASSOC))
	{
		$i++;
		$list[$i]=$record;
	}
	
	if ($no==1 && isset($list[0]))
		return $list[0];
	elseif (count($list)==0) 
		return false;
	else
		return $list;
}*/

function CheckCaptchaCode( $val1, $val2, $key )
{
	return !abs( strcmp( md5( $val1 . $key ), $val2 ) );
}

function seo_link($string, $id=0)
{
	// Inlocuiesc toate literele mari cu litere mici
	$string = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", html_entity_decode($string));
	
	$link = strtolower($string);	
	
	for ($i=0; $i < strlen($link); $i++)
	{
		// Inlocuiesc toate caracterele care nu sunt cifre sau litere cu "_"
		if(!ctype_alnum($link[$i]))
		{
			$link[$i]="-";
		}
	}
	
	if ($id!=0)
	{
		$link .= '-'.$id;
	}
	
	//==> Verific daca in string exista mai multe "-" consecutive si daca da, le inlocuiesc cu doar una
	while(strpos($link,'--') !== false )
	{
		$link=str_replace('--','-',$link);
	}
	//<==
	
    return $link;
}

function pr($object , $name = '') {
    print '<div style="position:absolute; left:0;top:0; padding:10px;background:#000;color:#fff;opacity: .8;filter: alpha(opacity=80);z-index:1000">';
		print ($name);
	if ( is_array ( $object ) ) {
	   print ( '<pre style="text-align:left;">' )  ;
	   htmlentities(print_r ( $object) ) ;
	   print ( '</pre>' ) ;
	} else {
	  htmlentities(  var_dump ( $object ) );
	}
	print '</div>';
}
/**
 * Resize picture
 *
 * @access: public
 * @return: null
*/
function resize_picture($file, $x_size, $y_size, $target="", $type="thumb")
{
	if(filesize($file) > 0)
	{
		//$fileType = explode("/", finfo_file($file));
		//$fileType = array("image", "jpg");

		$file_info = new finfo(FILEINFO_MIME);  // object oriented approach!
		$fileType = $file_info->buffer(file_get_contents($file));
		
		
		$fileType = explode(";", $fileType);
		if(!isset($fileType[0]) || $fileType[0]=="")
		{
			$lista = getimagesize($file);
			$fileType[0]=$lista['mime'];
		}

		$fileType = explode("/", $fileType[0]);
		//var_dump($fileType);
		
		if ($fileType[0]=="image")
		{
			list($width, $height)=getimagesize($file);
			if ($width > UPLOAD_IMG_W_LIMIT || $height > UPLOAD_IMG_H_LIMIT)
			{
				return false;
			}
			
			if($fileType[1]=='pjpeg' || $fileType[1]=='jpg' || $fileType[1]=='jpeg')
				$src = imagecreatefromjpeg($file);
			elseif($fileType[1]=='png')
				$src = @imagecreatefrompng($file);
			elseif($fileType[1]=='wbmp')
				$src = @imagecreatefromwbmp($file);
			elseif($fileType[1]=='gif')
			{
				$strImg = $this->getStrImg($file);
				$src = @imagecreatefromstring($strImg);
			}
			
			if ($src)
			{
				if ($x_size>0 && $y_size>0)
				{
					if ($type=="thumb")
					{
						$quef1=$y_size/$height;
						$quef2=$x_size/$width;
						
						if ($quef1<$quef2) 	$quef=$quef1;
						else				$quef=$quef2;
							
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
						
						if($quef1>=$quef2)	{ $newX=($x_size/$width)*$width; $newY=$y_size; }
						else				{ $newX=$x_size; $newY=($y_size/$height)*$height; }
						
						if($newX>$x_size)	{ $tmpX=($newX-$x_size)/2; $tmpY=0; }
						else 				{ $tmpX=0; $tmpY=($newY-$y_size)/2; }
						
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
			}
			else return false;
			
			imagedestroy($src);
			
			if ($target!="")
			{
				if($fileType[1]=='pjpeg' || $fileType[1]=='jpg' || $fileType[1]=='jpeg')
					imagejpeg($tmp, $target, 100);
				elseif($fileType[1]=='png')
					imagepng($tmp, $target);
				elseif($fileType[1]=='wbmp')
					image2wbmp($tmp, $target);
				elseif($fileType[1]=='gif')
					imagegif($tmp, $target);
				
				imagedestroy($tmp);
				
				return true;
			}
			else 
			{
				return $tmp;
			}
		}
		else 
			return false;
	}
	else
		return false;
}

function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) 
    {
        $length -= strlen($etc);
        if (!$break_words && !$middle) 
        {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) 
        {
            return substr($string, 0, $length).$etc;
        } 
        else 
        {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } 
    else 
    {
        return $string;
    }
}

/**
 * Process
 *
 * @access: public
 * @return: null
*/
function process($toprocess)
{
	$myFilter = new InputFilter();
	$result = (mysqli_real_escape_string($myFilter->process($toprocess)));

	return $result;
}


/**
 * Form validation
 *
 * @access: public
 * @return: null
*/
function form_validation($post=array(), $fields=array(), $tip=0, $tabel="", $mail=0, $subject="", $message="", $email="")
{
	$query = "";
	
	$i=0;
	foreach ($fields as $key => $item)
	{
		if (isset($post[$key]) && $post[$key]!="")
		{
			$list[$key] = (strip_tags($post[$key]));
			if ($i != 0)
				$query .= ", ";
			$query .= $item[0]." = '".(strip_tags($post[$key]))."'";
			$message .= "<br><b>". $item[1]. ": </b>". strip_tags($post[$key]);
		}
		$i++;
	}
	
	if ($mail == 1)
		send_mail($email, EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, $subject, array(), true);
		
	if ($tip==1)
	{
		$q = "INSERT INTO {$tabel} SET ".$query;
		_sqlQuery($q);
	}
	else
		return $list;
}

//################################//
//# Particularized Site Function #//
//################################//

function abonare_newsletter()
{
	global $smarty;
	$db = &SDatabase::getInstance();
	if (isset($_POST['act']) && $_POST['act']=="newsletter_send")
	{
		clearCache($_GET['action']);
		
		include_once(LIB_DIR."utile/validator.php");
		
		$v = new Validator($_POST);
		$v->setErrorMessage('email', 'Introduceti adresa de email!');
		$v->setErrorMessage('name', 'Introduceti numele!');

		$v->filledIn('email');
		$v->filledIn('name');
			
		if (!preg_match('/^[^0-9][a-zA-Z0-9_]*([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST['email']))
		{
			$v->pushError("email", "Adresa de mail ".$_POST['email']." este invalida!");
		}
		
                /*
		if (_sqlCheckFieldExistsDuplicate('newsletter', 'email', $_POST['email']))
		{
			$v->pushError("news_email", "Adresa de mail ".$_POST['email']." a fost deja inscrisa!");
		}
                 * 
                 */
		
		if (!$v->isValid())
		{
			$jsErrors="";
			foreach ($v->getErrors() as $k => $error)
			{
				$jsErrors .= $error."\\n";
			}
			
			$smarty->assign("jsAlertMsg", $jsErrors);
		}
		else
		{
			if (!_sqlCheckFieldExist("newsletter", "email", $_POST['email']))
			{
				$q="INSERT INTO
						newsletter
					SET 
						email = '".process($_POST['email'])."',
						name = '".process($_POST['name'])."',
						status = 1,
						ip = '".$_SERVER['REMOTE_ADDR']."',
						date = '".date("Y-m-d", time())."' 
				";
				_sqlQuery($q);
				
				$member_id=mysqli_insert_id($db->_resource);
			}
			else
			{
				$q="UPDATE
						newsletter
					SET 
						status = 1,
						ip = '".$_SERVER['REMOTE_ADDR']."'
					WHERE 
						email = '".process($_POST['email'])."' 
				";
				_sqlQuery($q);
			}
			
			unset($_POST);
			$smarty->assign("jsAlertMsg", "Multumim! Adresa ta de e-mail a fost inregistrata cu succes.");
		}
	}
}

function clearCache($cat)
{
	$dir = CACHE_DIR;
	$mydir = opendir($dir);
	$no_files=0;
	while(false !== ($file = readdir($mydir))) {
		if($file != "." && $file != ".." && strpos($file, $cat)>0) {
			unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
			$no_files++;
		}
	}
	closedir($mydir);
}

function clearAllCache()
{
	$dir = CACHE_DIR;
	$mydir = opendir($dir);
	while(false !== ($file = readdir($mydir))) {
		if($file != "." && $file != "..") {
			unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
			$no_files++;
		}
	}
	closedir($mydir);
}

function check_redirect() {
    $db = SDatabase::getInstance();
	$from = $db->getEscaped(substr(ROOT_HOST,0,-1).$_SERVER['REQUEST_URI']);
	$q = "SELECT redirect_to FROM redirect WHERE redirect_from = '{$from}'";
        $db->setQuery($q);
        $r = $db->loadObject();
        if(isset($r->redirect_to)){
            $to = $r->redirect_to;

            if($to)
            {
                header("HTTP/1.1 301 Moved Permanently"); 
                header("Location: $to"); 
            }
        }
        
}


/**
 * Suggested tags
 *
 * @access: public
 * @return: array of words
*/
function suggest_tags($text, $table, $id, $nrRet=10)
{
	$occ=2;
	$titleImp = 1.7; $summImp = 1.3; $twoImp = 2;
	
	//===> split text
	$arrTxt = explode("#$", $text);
	if (isset($arrTxt[2]))
	{
		$text = implode(" ", $arrTxt); $pri_2 = $arrTxt[1]; $pri_1 = $arrTxt[0];
	}
	elseif (isset($arrTxt[1]))
	{
		$text = implode(" ", $arrTxt);
		$pri_2 = "";
		$pri_1 = $arrTxt[0];
	}
	else 
	{
		$text = $arrTxt[0];
		$pri_2 = "";
		$pri_1 = "";
	}
	//<===
	
	$exclude= array(
		"o", "un", "ca", "sa", "care", "si", "la", "daca", "doar", "sunt", "este",
		"va", "mai", "mult", "multe", "putin", "putine", "are", "pentru", "trebuie", "unui",
		"unei", "de", "da", "nu", "dupa", "intr", "in", "din", "pe", "sub", "cum", "despre", 
		"", "", "", "", "", "", "", "", "", "", ""
	);
	
	$words = preg_split("/[^\w]+/s", $text);
	$text = preg_replace('/'.implode(' | ', $exclude).'/si', ' ', implode(" ", $words));
	$words = preg_split("/[\s]+/s", $text);
	
	$tags = array();
	
	for($i=0; $i<count($words); $i++)
	{
		if (!in_array($words[$i], $exclude))
		{
			if (isset($tags[$words[$i]])) $tags[$words[$i]]++;
			else $tags[$words[$i]]=1;
			
			if ($i!=count($words)-1)
			{
				if (isset($tags[$words[$i]." ".$words[$i+1]])) $tags[$words[$i]." ".$words[$i+1]]++;
				else $tags[$words[$i]." ".$words[$i+1]]=1;
			}
		}
	}
	
	foreach ($tags as $tag => $nr)
	{
		if ($nr<$occ || strlen($tag)<4)
		{
			unset($tags[$tag]);
		}
		else
		{
			
			if (strstr($pri_1, $tag))						//title
				$tags[$tag] = round($tags[$tag]*$titleImp);
			elseif (strstr($pri_2, $tag))					//summary
				$tags[$tag] = round($tags[$tag]*$summImp);
			
			if (strstr($tag, " ")) 							//2 words
				$tags[$tag] = round($tags[$tag]*$twoImp);
		}
	}
	
	$result = array(); $i=0;
	while($i<$nrRet && count($tags)>0)
	{
		//===> pick a max weight word
			$max=0; $maxW="";
			foreach ($tags as $tag => $nr)
			{
				if ($nr>$max) { $max=$nr; $maxW=$tag; }
			}
		//<===
		
		//===> check if it's already put
			if ($tag_id = _sqlGetFieldContent("tags", "id", "name", $maxW))
			{
				if (_sqlCheckFieldExist("tags_links", "owner", $table, "owner_id", $id, "tag", $tag_id))
				{
					unset($tags[$maxW]);
					continue;
				}
			}
		//<===
		
		//===> put word in list, exclude it and move further
			$result[] = $maxW;
			unset($tags[$maxW]);
			$i++;
		//<===
	}
	
	return $result;
} 

function getLocalitateInfo($id){
    $db = &SDatabase::getInstance();
    
    $db->setQuery("select * from localitati where ws_id = '{$id}' ");
    return $db->loadObject();
}

function detectAgeFromCNP($cnp){
    
    if($cnp[0]==1 || $cnp[0]==2 )
        $date_birth = "19";
    elseif($cnp[0]==3 || $cnp[0]==4 )
        $date_birth = "18";
    elseif($cnp[0]==5 || $cnp[0]==6 )
        $date_birth = "20";
    else
        return false;
    
    
    $date_birth .= $cnp[1].$cnp[2]."-".$cnp[3].$cnp[4]."-".$cnp[5].$cnp[6];
    return $date_birth;
}

function dateDiff ($d1, $d2) {
// Return the number of days between the two dates:
  return round(abs(strtotime($d1)-strtotime($d2))/86400);

}  // end function dateDiff

function  checkCIF($cif){
	if  (!is_numeric($cif))  return  false;
	if  (  strlen($cif)>10  )  return  false;

	$cifra_control  =  substr($cif,  -1);
	$cif  =  substr($cif,  0,  -1);
	while  (strlen($cif)!=9){
		$cif  =  "0".$cif;
	}
	$suma  =  $cif[0]*7  +  $cif[1]*  5  +  $cif[2]  *  3  +  $cif[3]  *  2  +  $cif[4]  *  1  +  $cif[5]  *  7  +  $cif[6]  *  5  +  $cif[7]  *  3  +  $cif[8]  *  2;
	$suma=$suma*10;
	$rest  =  fmod($suma,  11);
	if  (  $rest==10  )  $rest=0;

	if  ($rest==$cifra_control)  return  true;
	else  return  false;
}

function bnr_fetch($currency){

	$db = SDatabase::getInstance();
	$db->setQuery("SELECT * FROM `curs_bnr` ORDER BY `date_added` DESC LIMIT 0,1");
	$row    =  $db->loadAssoc();

	if(isset($row[strtolower($currency)]))
		return $row[strtolower($currency)];
	else{
		/**
		 * @todo better fail management. die() has no turning backs!
		 */
		die("Error fetching curs valutar!");
	}

}

function bnr_parity($from_currency, $to_currency){
	$db = SDatabase::getInstance();

	$fc = strtolower($from_currency);
	$tc = strtolower($to_currency);

	if ($fc==$tc)
		return 1;

	if($fc=="ron")

		$formula = " ( 1 / {$tc} )";

	elseif($tc=="ron")

	$formula = $fc;

	else
		$formula = " {$fc} / {$tc}";

	$db->setQuery("SELECT {$formula} as parity FROM `curs_bnr` ORDER BY `date_added` DESC LIMIT 0,1");
	$row    =  $db->loadAssoc();
	
	if(isset($row["parity"]))
		return $row["parity"];

	else{
		/**
		 * @todo better fail management. die() has no turning backs!
		 */
		die("Error fetching curs valutar parity!");
	}
}


/**
 *
 * @return true if has Seismic Risk ; false if is not
 * @param array $adresa
 */
function checkSeismicRisk( $adresa = array() ){
	$db = SDatabase::getInstance();

	$l_id       = $adresa["id"];

	$addrO = $db->search("localitati", "row", "`ws_id`=".$l_id);
	$addr = get_object_vars($addrO);
	
	$judet = $addr["judet"];
	$oras  = $addr["nume"];
	if($oras=="Bucuresti"){
		$judet="Bucuresti";
	}

	$street     = $adresa["street"];
	$street_no  = ( isset($adresa["street_no"]) )?$adresa["street_no"]:null;
	$block_no   = ( isset($adresa["block_no"]) )?$adresa["block_no"]:null;

	$street_filtered = strtoupper(trim($street));
	$street_no_filtered = filter_var($street_no, FILTER_SANITIZE_NUMBER_INT);

	if($street_no)
		$q = "select count(id) as nr from acl_risk_table where Judet ='{$judet}' and Oras='{$oras}' and UPPER(Strada)='{$street_filtered}' and Nr_strada='{$street_no_filtered}'";
	else
		$q = "select count(id) as nr from acl_risk_table where Judet ='{$judet}' and Oras='{$oras}' and UPPER(Strada)='{$street_filtered}' and Bloc='{$block_no}'";

	$db->setQuery($q);
	$row = $db->loadAssoc();
	$result = $row["nr"];

	/*
	 if(isset($adresa["debug"]) && $adresa["debug"]==0){
	 echo "<strong>Input:</strong> Str. {$street} Nr. {$street_no} <br /> <strong>Se cauta</strong> Str. {$street_filtered} Nr. {$street_no_filtered} ";
	 echo "<br />";
	 echo "<strong>Query </strong>:".$q;
	 echo "<br />";
	 echo "<strong>Rezultat </strong>:".$result["nr"];
	}*/

	if( isset($result["nr"]) && $result["nr"] > 0 )
		return true;
	else
		return false;
}
?>