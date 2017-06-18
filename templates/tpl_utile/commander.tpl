{literal}
<script type="text/javascript">
function confirmDelete(x)
{
	if(confirm("Sure?")) window.location=x;
}
</script>
{/literal}
<html>
<body>
{if $recList|@count>0}
	<table cellpadding="4" cellspacing="4" style="border-width:thin; border-color:#000066; border-style:dotted;">
		<tr style="border-width:1; border-color:#000066; border-style:solid; background-color:#FFCCCC;">
			<th align="center" width="300">Name</th>
			<th align="center" width="100">Type</th>
			<th align="center" width="200">Date</th>
			<th align="center" width="150">Size</th>
			<th align="center" width="80">Delete</th>
		</tr>
		{foreach from=$recList item=item key=key}
			{if ($item.name!="." && $item.type=="Director")}
				<tr style="font-family:Verdana; font-size:12px;">
					<td style="font-weight:bold;"><a href="index.php?obj=index&action=commander&path={$smarty.get.path|default:"."}/{$item.name}" style="color:#000000; text-decoration:none;">{$item.name}</a></td>
					<td>{$item.type}</td>
					<td>{$item.date|date_format:$DF}</td>
					<td>--</td>
					<td align="center">
						<a onClick='confirmDelete("commander.php?del={$smarty.get.path}/{$item.name}&path={$smarty.get.path}");' style="cursor:pointer;">del</a>
					</td>
				</tr>
			{/if}
		{/foreach}
		
		{foreach from=$recList item=item key=key}
			{if ($item.name!="." && $item.type=="Fisier")}
				<tr style="font-family:Verdana; font-size:12px;">
					<td style="font-weight:bold;"><a href="{$smarty.get.path}/{$item.name}" style="color:#000000; text-decoration:none;">{$item.name}</a></td>
					<td>{$item.type}</td>
					<td>{$item.date|date_format:$DF}</td>
					<td>{$item.size/1024|number_format:"%2.f"} kb</td>
					<td align="center">
						<a onClick='confirmDelete("index.php?obj=index&action=commander&del={$smarty.get.path|default:"."}/{$item.name}&path={$smarty.get.path|default:"."}");' style="cursor:pointer;">del</a>
					</td>
				</tr>
			{/if}
		{/foreach}
	</table>
{/if}

<form name="" action="index.php?obj=index&action=commander&upl={$smarty.get.path}" method="post" enctype="multipart/form-data">
	Pick file: &nbsp;
	<input type="file" name="file"> &nbsp;
	<input type="submit" value="Upload">
</form>
</body>
</html>