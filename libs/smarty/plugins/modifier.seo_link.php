<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty seo link modifier plugin
 *
 * Type:     modifier<br>
 * Name:     seo_link<br>
 * Purpose:  convert string to link (no spaces, url-encoded, etc...)
 * @param string
 * @return string
 */

    
function smarty_modifier_seo_link($string, $id=0)
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

?>
