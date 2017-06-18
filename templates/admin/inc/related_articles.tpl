{if $smarty.get.act=="pop"}
	<select multiple="multiple" name="rel" style="width:400px; height:100px;">
		{foreach from=$catArticles item=item key=key}
			<option value="{$item.article_id}">{$item.article_title}</option>
		{/foreach}
	</select>
{else}
	{if $relatedArticles|@count>0}
		<table width="100%" class="tableListing">
			<tr align="center">
				<th>Articol</th>
				<th width="50">Delete</th>
			</tr>
			{foreach from=$relatedArticles item=item key=key}
				<tr>
					<td>{$item.article_title}</td>
					<td align="center">
						<a href="javascript: document.getElementById('ifr_utile').src='index.php?obj=article&action=related_articles&act=del&id={$item.id}'; ajaxRe.send(); void(false);">
							<img src="../img/admin/utile/del.gif" align="absmiddle" border="0" />
						</a>
					</td>
				</tr>
			{/foreach}
		</table>
	{/if}
{/if}