<?php
ob_start();

$section = "SITE";
include "config.php";

include_once LIB_DIR."site/app.php";
$application = SApp::getInstance();
$application->execute();
$application->check_redirect();

$content_html=ob_get_contents();
ob_end_clean();
echo $content_html;
$application->displayDebug();
