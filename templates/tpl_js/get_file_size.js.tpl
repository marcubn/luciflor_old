<script type="text/javascript">
var obj = '{$smarty.get.obj}';
var utileFilePath = '{$utileFilePath|default:"../"}';
{literal}
function getFileSize(elemFile, elemSize)
{
	var action=document.form_act.action;
	
	//===>get size file
	document.form_act.target='ifr_utile';
	document.form_act.action=utileFilePath+'utile.php?obj=get_file_size&action=get&elemFile='+elemFile+'&elemSize='+elemSize;
	document.form_act.submit();
	
	document.form_act.action=action;
	document.form_act.target='_self';
	//<====
}
{/literal}
</script>