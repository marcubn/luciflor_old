<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Date romanian date modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_ro<br>
 * Purpose:  convert date to romanian style
 * @param string
 * @return string
 */
function smarty_modifier_date_ro($string_date)
{
	$luni = array(
		1 => "Ianuarie",
		2 => "Februarie", 
		3 => "Martie", 
		4 => "Aprilie", 
		5 => "Mai", 
		6 => "Iunie", 
		7 => "Iulie", 
		8 => "August", 
		9 => "Septembrie", 
		10 => "Octombrie", 
		11 => "Noiembrie",
		12 => "Decembrie"  
	);
	
	$zile = array(
		1 => "Luni",
		2 => "Mar&#355;i",
		3 => "Miercuri",
		4 => "Joi",
		5 => "Vineri",
		6 => "S&acirc;mb&#259;t&#259;",
		7 => "Duminic&#259;"
	);
	
	if (is_numeric($string_date)) $time = $string_date;
	else $time = strtotime($string_date);
	
	$date_ro = $zile[date("N", $time)].", ".date("d", $time)." ".$luni[date("n", $time)]." ".date("Y", $time);
	
    return $date_ro;
}

?>
