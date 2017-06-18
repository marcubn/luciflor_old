{include file="admin/inc/popup_top.tpl"}
<table class="table table-bordered">
<thead>
	<th>Date</th>
	<th>User ID</th>
	<th>Modifications</th>
</thead>
<tbody>
{foreach from=$data item=item}
<tr>
	<td>{$item->data}</td>
	<td>{$item->uid}</td>
	<td>{$item->descr}</td>
</tr>
{foreachelse}
<tr>
<td colspan="3">No modifications made at this moment</td>
</tr>
{/foreach}
</tbody>
</table>
</body>
</html>