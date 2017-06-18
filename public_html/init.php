<?php	
/**
 * Init Framework standard libraries for both clients admin and front
 *
 * @package  Smarty Project Model
 **/
set_time_limit(0);
session_start();

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

include_once "app_config.php";

//===> ENGINE TEMPLATE INITIALISATION
require_once( SMARTY_DIR . "Smarty.class.php" );

$smarty  = new Smarty;
$smarty->template_dir   = TEMPLATES_DIR;
$smarty->compile_dir    = COMPILE_DIR;
$smarty->config_dir     = TEMPLATES_DIR."configs/";
$smarty->debugging      = DEBUG;
$smarty->cache_dir      = CACHE_DIR;
$smarty->caching        = CACHING;
$smarty->cache_lifetime = CACHE_LIFETIME;
//<===

//===> Database Connect
/** 
 * 
 * Legacy Database Object and Result Object
 * 
 * @deprecated
 *  
 * */
//require_once( LIB_DIR . "db/db.legacy.php"); 
require_once( LIB_DIR . "db/database.php");
//$db = new DB();
$db = &SDatabase::getInstance();
//<===

//===> DEBUG & PROFILER
if(1==DEBUG)
{
    require(LIB_DIR."bench/Profiler.php");
    $profiler = new Benchmark_Profiler();
    $profiler->start();
}

//#########################################################################//


//===> LIBRARIES	
require_once( LIB_DIR . "time/time.lib.php");
/**
 * @deprecated
 */
//require_once( LIB_DIR . "db/db.lib.php");
require_once( LIB_DIR . "folder_files/folder_files.lib.php");
require_once( LIB_DIR . "listing_tools/listing_tools.lib.php");
require_once( LIB_DIR . "mail/sendmail.php");
require_once( LIB_DIR . "string/string.lib.php");
require_once( LIB_DIR . "utile/utile.lib.php");
//require_once( LIB_DIR . "utile/Mobile_Detect.php");
require_once( LIB_DIR . "utile/class.inputfilter_clean.php");
require_once( LIB_DIR . "recaptcha/recaptchalib.php");
require_once( LIB_DIR . "MobileDetect/Mobile_Detect.php");
require_once( LIB_DIR . "document/document.lib.php");
require_once( LIB_DIR . "lang/lang.lib.php");
require_once( LIB_DIR . "image/uplphoto.lib.php");
//require(LIB_DIR."utile/utile_smarty.lib.php");
require_once(INCLUDE_DIR."smarty/plugins.php");
//<===

//===> FUNCTIONS
require( INCLUDE_DIR."functions.php");
//<===

//#########################################################################//

//#########################################################################//
//## BEGIN TOP OPERATIONS

if(isset($section) && $section=="ADMIN")//only for back-end
{
	require(INCLUDE_DIR."functions_admin.php");
    
	initAdmin();
    
}
else//only for front-end
{
	initSite();
}

//## END TOP OPERATIONS
//#########################################################################//
?>