{*$smarty.get|var_dump*}
{*<iframe width='560' height='315' src='{$smarty.get.video}'></iframe>*}
{if $smarty.get.type=='youtube'}
<object width="530" height="330"><param name="movie" value="{$smarty.get.video}?hl=en_US&amp;version=3&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="{$smarty.get.video}?hl=en_US&amp;version=3&amp;rel=0" type="application/x-shockwave-flash" width="530" height="330" allowscriptaccess="always" allowfullscreen="true"></embed></object>
{elseif $smarty.get.type=='vimeo'}
<iframe src="{$smarty.get.video}" width="530" height="330" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
{/if}