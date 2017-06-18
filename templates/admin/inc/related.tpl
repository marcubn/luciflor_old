{if $relatedList|@count>0}
	<table width="100%" class="tableListing">
		<tr align="center">
			<th>Title</th>
			<th>Link</th>
			<th width="50">Delete</th>
		</tr>
		{foreach from=$relatedList item=item key=key}
			<tr>
				<td>{$item.title}</td>
				<td><a href="{$item.link}" target="_blank">{$item.link}</a></td>
				<td align="center">
					<a href="javascript: document.getElementById('ifr_utile').src='index.php?obj={$smarty.get.obj}&action=related&act=del&id={$item.id}'; ajaxRe.send(); void(false);">
						<img src="../img/admin/utile/del.gif" align="absmiddle" border="0" />
					</a>
				</td>
			</tr>
		{/foreach}
	</table>
{/if}