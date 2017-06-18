<?php

//#########################################################################//
//# Utile for string processing
//#
//# Author: CFlorin (E-mail: colotin_f@yahoo.com)
//# Date: 22.01.2004
//#########################################################################//

/**
 * String fomated
 *
 * @parameters: $str = string | $noChars = no. of chars | $sufix = sufix terminator
 * @access: public
 * @return: string
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _strFormated($str, $noChars, $sufix='...')
{
	if(strlen($str) <= $noChars) 
		 return $str;
	else 
		return substr($str, 0, $noChars).$sufix;
}

/**
 * Get Tabulation
 *
 * @parameters: $no = no. of tabs \t
 * @access: public
 * @return: tab string
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _strGetTabulation($no)
{
	$ret = "";
	for($i=0;$i<$no;$i++)
		$ret.="\t";
	return $ret;
}

/**
 * Generate random string
 *
 * @parameters: $noChar = string lenght | $strFrom = base of string generated
 * @access: public
 * @return: string generated
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _strGetRandomStr($noChars, $strFrom='')
{
	if(''==$strFrom)
		$strFrom = "qwertyuiop123asdfghjkl456zxcvbnm7890";
	
    $len = strlen($strFrom);
    
    $vectStrGenerated = array();
    
    $i = 0;    
    while($i<$noChars)
    {
        $pos = rand(0, $len-1);
        $vectStrGenerated[$i] = $strFrom[$pos];
        $i++;
    }
    
    $strGenerated = implode('', $vectStrGenerated);
    
    return $strGenerated;
}

/**
 * Number formating
 *
 * @parameters: $no = number | $p = precision
 * @access: public
 * @return: number formated
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 22.01.2004 (dd.mm.YYYY)
*/
function _strNoFormated($no, $p=0)
{	
	return number_format($no, $p, '.', ' ');
}

?>