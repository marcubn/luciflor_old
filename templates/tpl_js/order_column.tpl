<th {if $width!=""}width="{$width}"{/if} onclick="document.form_list.field_sort.value='{$field}'; document.form_list.submit();" style="cursor:pointer;" title="Sort by {$name}">
	{$name}
	{if $moduleSession.sort.field_sort==$field}
		{if $moduleSession.sort.sense_sort=="ASC"}
			<img src="{$smarty.const.IMAGES_URL}admin/utile/asc.png" />
		{elseif $moduleSession.sort.sense_sort=="DESC"}
			<img src="{$smarty.const.IMAGES_URL}admin/utile/desc.png" />
		{/if}
	{/if}
</th>