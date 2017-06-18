<?php

//#########################################################################//
//# Utile for date & time
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 22.01.2004
//#########################################################################//

/**
 * Get microtime in miliseconds
 *
 * @param: void
 * @access: public
 * @return: numeric miliseconds
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _dtGetMicroTime()
{ 
   list($usec, $sec) = explode(" ", microtime()); 

   return ((float)$usec + (float)$sec); 
}

/**
 * Get system tyme
 *
 * @param: void
 * @access: public
 * @return: numeric time
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _dtGetTime()
{ 
	if(defined("TIME_DELAY")) 
		return time()+TIME_DELAY;
	else
		return time();
}

/**
 * Get current date formated
 *
 * @param: $format = date format
 * @access: public
 * @return: date
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _dtGetDate($format='Y-m-d')
{ 
	$time = _dtGetTime();     
	return date($format, $time);
}

/**
 * Get date formated
 *
 * @param: $format = date format
 * @access: public
 * @return: date
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function _dtDateFormat($date, $format='d.m.y')
{
	if($date!='' && $date!='0000-00-00')
	{
		$time = strtotime($date);
		return date($format, $time);
	}
	else
		return false;
}

/**
 * Get H,I,S from unic time
 *
 * @param: $time = unix time | $zeroFill = zero fill
 * @access: public
 * @return: array()
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _dtGetHISFromUnixTime($time, $zeroFill='')
{
	$vectTime=array();
	$vectTime["h"] = (int)($time/3600);
	$vectTime["i"] = (int)(($time - ($vectTime["h"]*3600))/60);
	$vectTime["s"] = ($time - ($vectTime["h"]*3600) - ($vectTime["i"]*60));
	
	if(''!=$zeroFill)
	{
		if($vectTime["h"]<9) $vectTime["h"]=$zeroFill.$vectTime["h"];
		if($vectTime["i"]<9) $vectTime["i"]=$zeroFill.$vectTime["i"];
		if($vectTime["s"]<9) $vectTime["s"]=$zeroFill.$vectTime["s"];	
	}
	
	return $vectTime;
}

/**
 * Get date of week first day (weeks starting on Monday)
 *
 * @param: $year, $week
 * @access: public
 * @return: time
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function _dtGetUTimeOfWeekFirstDay($year, $week)
{		
	$startTime = strtotime("{$year}-01-01");
	
	$firstMondayTime = strtotime("first monday", $startTime);

	return $firstMondayTime+((3600*24*7)*($week-1));
}

/**
 * Get week days
 *
 * @param: null
 * @access: public
 * @return: time
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function getDaysOfWeek($lang_iso='en')
{
	$ret=array();
	
	$ret['ro'][1]='Luni';
	$ret['ro'][2]='Marti';
	$ret['ro'][3]='Mircuri';
	$ret['ro'][4]='Joi';
	$ret['ro'][5]='Vineri';
	$ret['ro'][6]='Sambata';
	$ret['ro'][7]='Duminica';
	
	$ret['en'][1]='Monday';
	$ret['en'][2]='Tuesday';
	$ret['en'][3]='Wednesday';
	$ret['en'][4]='Thursday';
	$ret['en'][5]='Friday';
	$ret['en'][6]='Saturday';
	$ret['en'][7]='Sunday';
	
	return $ret[$lang_iso];
}

/**
 * Get year months
 *
 * @param: null
 * @access: public
 * @return: time
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function getMonthsOfYear($lang_iso='en')
{
	$ret=array();
	
	$ret['ro'][1]='Ianuarie';
	$ret['ro'][2]='Februarie';
	$ret['ro'][3]='Martie';
	$ret['ro'][4]='Aprilie';
	$ret['ro'][5]='Mai';
	$ret['ro'][6]='Iunie';
	$ret['ro'][7]='Iulie';
	$ret['ro'][8]='August';
	$ret['ro'][9]='Septembrie';
	$ret['ro'][10]='Octombrie';
	$ret['ro'][11]='Noiembrie';
	$ret['ro'][12]='Decembrie';
	
	$ret['en'][1]='January';
	$ret['en'][2]='February';
	$ret['en'][3]='March';
	$ret['en'][4]='April';
	$ret['en'][5]='May';
	$ret['en'][6]='Jun';
	$ret['en'][7]='July';
	$ret['en'][8]='August';
	$ret['en'][9]='September';
	$ret['en'][10]='October';
	$ret['en'][11]='November';
	$ret['en'][12]='December';
	
	return $ret[$lang_iso];
}

/**
 * Get unic time for each day of the week
 *
 * @param: $year, $week
 * @access: public
 * @return: array();
 * @author: CFlorin (colotin_f@yahoo.com)
*/
function _dtGetUTimeForEachDayOfWeek($year, $week)
{
	$ret=array();				
	
	$startUT = _dtGetUTimeOfWeekFirstDay($year, $week);
	
	for($i=1;$i<=6;$i++)
	{
		$ret[$i]=$startUT+(($i-1)*(24*3600))-3600;
		//$ret[$i]=$startUT+(($i)*(24*3600))-3600;
		//$ret[$i]=$startUT+(($i-1)*(24*3600));
	}
	
	return $ret;
	//return null;
}
?>