{*<!-- 
	Parametrii: $cont contine textul care va fi afisat in toolTip
				$over contine imaginea
				$width contine latimea pe care vrem sa o dam div-ului (daca nu este setata setez o latime default)
				$color - culoarea (daca nu este setata setez o culoare default)
-->*}
{if $color==""}{assign var=color value="#000099"}{/if}
{if $width==""}{assign var=width value=500}{/if}
<img onmouseover="ddrivetip('{$cont}','{$color}', 100)" onmouseout="hideddrivetip()" src="{if $over!=""}{$smarty.const.ROOT_HOST}{$over}{else}img/admin/utile/view.gif{/if}"  />
