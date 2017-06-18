<?php
/**
 * Date format for smarty
 * 
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 18.04.06
*/
function sDateFormat($date, $format="d.m.y")
{
	if(defined("DATE_FORMAT"))
		$format=DATE_FORMAT;
	else 
		$format="d.m.y";
	
	if($date!='' && $date!='0000-00-00')
	{
		$time = strtotime($date);
		return date($format, $time);
	}
	else
		return "";
}

/**
 * Minutes format
 * 
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 18.04.06
*/
function sMTimeFormat($minutes, $format="%hh %mm", $zerofill='&nbsp;')
{
	if(is_numeric($minutes) && $minutes>0)
	{
		$h = (int)($minutes/60);
		$m = (int)($minutes-($h*60));
		
		if($zerofill!='')
		{
			$h = $h<=9 ? "{$zerofill}{$h}":$h;
			$m = $m<=9 ? "{$zerofill}{$m}":$m;
		}
		
		$ret = str_replace("%h", $h, $format);
		$ret = str_replace("%m", $m, $ret);
		
		return $ret;
	}
	else 
		return "";
}

$smarty->register_modifier("sDateFormat", "sDateFormat");
$smarty->register_modifier("sMTimeFormat", "sMTimeFormat");
?>