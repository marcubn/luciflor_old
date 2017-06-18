<?php

//#########################################################################//
//# Config file - You must edit ROOT PATH, DB, EMAIL, UTILE APP
//#########################################################################//

define("VARPREFIX", "Pure Mess");
define("PUBLIC_FOLDER", "public_html/");

// ==> regional settings
date_default_timezone_set("Europe/Bucharest");
define("CHARSET", "utf-8");
// <==

//===> ROOT DIR AND ROOT HOST
define("ROOT_DIR", substr_replace(dirname(__FILE__), '/', strlen(dirname(__FILE__))-12, 12));
define("ROOT_HOST", "http://www.pure-mess.lan/");
//<===

//===> DB CONSTANTS ===> LE MODIFICATI NUMAI SUS <===
define("DB_TYPE", "mysqli");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "bogdanm_puremess");
//<===

//===> UTILE APP
define("COPYRIGHT", "0");
define("SITE_NAME", 'Pure Mess');
define("PAGE_TITLE_ADMIN", "Pure Mess - Administrare");
define("PAGE_TITLE_SITE", "Pure Mess");
define("NO_ROWS_DISPLAYED", 20);
define("ADMINMENU_SHOW", 1);
//<===

//===> FROM EMAIL CONSTANTS
define("MAILER", "smtp");
define("EMAIL_FROM_NAME", "Pure Mess");
define("EMAIL_FROM_ADDR", "office@pure-mess.ro");
define("EMAIL_CONTACT_ADDR", "office@pure-mess.ro");
define("MAIL_REPORTS",0);
define("MAIL_HOST", "pure-mess.ro");
define("MAIL_PORT", 587);
define("MAIL_OFFLINE", 0);
define("MAIL_USER", "office@pure-mess.ro");
define("MAIL_PASS", "pur3m3ss");
//<===

//===> set first index for session in back-end and font-end
define("SESS_IDX_BE", VARPREFIX."_admin");
define("SESS_IDX_FE", VARPREFIX."_site");
//<===

//===> IMAGES URL AND UPLOAD DIR
define("IMAGES_URL", ROOT_HOST."img/");
define("UPLOAD_URL", ROOT_HOST."upl/");
define("UPLOAD_DIR", ROOT_DIR.PUBLIC_FOLDER."upl/");
define("IMAGE_UPLOAD_RESIZE", 4000);
//<===

//===> SYSTEM CONSTANTS
define("APP_MODE", "DEVELOPMENT");
define("ERROR_REPORTING", E_ALL);
//define("ERROR_REPORTING", 0);
define("DEBUG", 0);
define("DB_DEBUG", 0);
define("LIB_DIR", ROOT_DIR."libs/");
define("SMARTY_DIR", LIB_DIR."smarty/");
define("COMPONENT_DIR", ROOT_DIR."engine/component/");
define("TEMPLATES_DIR", ROOT_DIR."templates/");
define("COMPILE_DIR", ROOT_DIR."tmp/templates_c/");
define("CACHE_DIR", COMPILE_DIR."cache/");
if (isset($section) && $section=="SITE") define("CACHING", 0); else define("CACHING", 0);
define("CACHE_ID", $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
define("CACHE_LIFETIME", 300);
define("INCLUDE_DIR", ROOT_DIR."engine/include/");
define("PASS_ENCODE", "123");
define("JPCACHE_ON", "0");
//<===

//===> DEFINE DB LOGS 
define("DB_LOG", 0);
define("DB_LOG_TIME", 2); // Slow queries log
define("DB_LOG_DIR", ROOT_DIR."tmp/sqllogs/");
//<===

//===> FOR AUTOLOGIN ADMIN
define("VAR_COOKIE_USER", VARPREFIX."_cookie_user");
define("VAR_COOKIE_PASS", VARPREFIX."_cookie_pass");	
//<===

//===> FOR AUTOLOGIN FRONT-END
define("VAR_COOKIE_M_USER", VARPREFIX."_m_cookie_user");
define("VAR_COOKIE_M_PASS", VARPREFIX."_m_cookie_pass");
//<===

//===> FOR AUTOLOGIN FRONT-END
define("VAR_COOKIE_R_USER", VARPREFIX."_r_cookie_user");
define("VAR_COOKIE_R_PASS", VARPREFIX."_r_cookie_pass");
//<===

//===> FOR CAPTCHA CODE
define("RECAPTCHA_PUBLIC_KEY", "6Lc_m9oSAAAAAK9Zq-mhF-408BmFxQlsNY9JU5Fr ");
define("RECAPTCHA_PRIVATE_KEY", "6Lc_m9oSAAAAAEEufbFpAKEMJpRlJQ_Z8l2BSnkP "); 
define("CAPTCHA_BK_COLOR", "#000");
define("CAPTCHA_BD_COLOR", "#000");
define("CAPTCHA_LN_COLOR", "");
define("CAPTCHA_DT_COLOR", "");
//<===

//#########################################################################//
//# PARTICULARIZED CONSTANTS
//#########################################################################//

//===> UPLOAD DIRS
define("PHOTOS_UPLOAD_DIR", UPLOAD_DIR."photos/");
define("PHOTOS_UPLOAD_URL", UPLOAD_URL."photos/");
//<===

//===> UPLOAD ORIGINAL DIRS
define("ORIGINAL_UPLOAD_DIR", UPLOAD_DIR."original/");
define("ORIGINAL_UPLOAD_URL", UPLOAD_URL."original/");
//<===

?>