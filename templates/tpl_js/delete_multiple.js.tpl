<script language="javascript">
var imgPath = '{$smarty.const.IMAGES_URL}admin/utile/';
var elemBtnDel = 'btnDel';
var txtConfirmDel = '{#txtConfirmDel#}';
var obj = '{$smarty.get.obj}';
{literal}
function setDelete(objForm, ids)
{
	objForm.action = 'index.php?obj='+obj+'&action=delete_items&ids='+ids;
	objForm.act.value = 'delete';
	if(confirm(txtConfirmDel))
		objForm.submit();
}

function old_verifyRowChecked(objForm, textMatch)
{
	var c=0, text='';
	var items = objForm.elements;
	for(i=0;i<items.length;i++)
	{
		if(items[i].type == 'checkbox' && items[i].name.match(textMatch))
			if(items[i].checked == true)
			{
				c++;
				break;
			}
	}

	if(c >= 1)
		text = '<span onClick="setDelete('+objForm.name+')" onMouseOver="style.cursor=\'pointer\';"><img src="'+imgPath+'del.gif" border="0" align="absmiddle"></span>';
	else
		text = '<img src="'+imgPath+'del.gif" border="0" style="filter:Alpha(Opacity=20)" align="absmiddle">';
		
	innerWrite(elemBtnDel, text);
}

function verifyRowChecked(objForm, textMatch)
{
	var c=0, text='';
	var ids = "0";
	$('.jsx_checkbox').each(function () {
       var sThisVal = (this.checked ? $(this).val() : "");
       if(sThisVal!="")
       {
       		ids = ids+","+sThisVal;
	       	c++;
       }
  	});
	
	if(c >= 1)
		text = '<span onClick="setDelete('+objForm.name+', \''+ids+'\')" onMouseOver="style.cursor=\'pointer\';"><img src="'+imgPath+'del.gif" border="0" align="absmiddle"></span>';
	else
		text = '<img src="'+imgPath+'del.gif" border="0" style="filter:Alpha(Opacity=20)" align="absmiddle">';
		
	innerWrite(elemBtnDel, text);
}
{/literal}
</script>