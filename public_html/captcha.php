<?php
require_once('config.php');
require_once(LIB_DIR.'utile/captcha.php');
session_start();
$c = new captcha(160, 40);
$c->addText();
$c->outputImage();
?>