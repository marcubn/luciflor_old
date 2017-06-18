		<form name="form_search" action="index.php?obj={$smarty.get.obj}&action={$smarty.get.action}" method="post" onSubmit="applyStyle('toggleFS', 'visibility', 'visible', 1); return formValidate('form_search', 0);">
		<fieldset>
			<legend>
				{#txtSearchEngine#}
				<a href="javascript:onoffToggle('toggleFS', 'imgToggleFS', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_open.gif', '{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_closed.gif');" onMouseOver="window.status='Expand/Collapse'; return true;" onMouseOut="window.status=''; return true">
				<img name="imgToggleFS" id="imgToggleFS" src="{$smarty.const.IMAGES_URL}admin/utile/acdsee_toggle_closed.gif" border="0" align="absmiddle"></a>
			</legend>
			<table width="100%" border="0" cellpadding="5" cellspacing="0" id="toggleFS" style="position:absolute; visibility:hidden; display:none;">
				<input type="hidden" name="act" value="search">
				<tr>
					<td align="left">

