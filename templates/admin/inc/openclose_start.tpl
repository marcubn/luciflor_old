		{assign var=pname value=$name|replace:" ":"_"|replace:"\"":"_"|replace:"'":"_"}
		<fieldset style="width:{$width|cat:"px"|default:"97%"};">
			<legend>
				{$name}
				<a onclick="$('#{$pname}').toggle('slow');" onMouseOver="window.status='Expand/Collapse'; return true;" onMouseOut="window.status=''; return true">
				<img name="img{$pname}" id="img{$pname}" src="../img/admin/utile/acdsee_toggle_closed.gif" border="0" align="absmiddle"></a>
			</legend>
			<table width="{$width|default:"100%"}" border="0" cellpadding="0" cellspacing="0" id="{$pname}" style="display:none;">
				<tr>
					<td>