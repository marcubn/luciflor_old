<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{literal}
	<style type="text/css">
		p				{ font:normal 11px arial; color:#343434; margin:4px 0!important;}
		p a				{ text-decoration:none; color:#8badca;}
		p a:hover		{ text-decoration:underline; }
		p a:visited		{ color:#8badca;}
		p span.label	{ font-weight:bold; color:#545454; padding-right:5px;}
		img				{ border:none;}		
		td.logo			{ background:#8badca; padding:0!important;}
		td.logo	a img	{ border:none;}
		td				{ padding:10px!important;}
	</style>
{/literal}
</head>

<body style="background:#fff;">
	<table width="500" border="0" style="width:500px; display:block; position:relative; margin:0 auto; border:solid 1px #ccc;">
		<tr>
			<td class="logo">
				<a href="{$smarty.const.ROOT_HOST}" title="{$smarty.const.ROOT_HOST}">
					<img src="{$smarty.const.ROOT_HOST}/img/admin/logo.jpg" />
				</a>
			</td>
		</tr>
		<tr>
			<td>
				<p>{$infoMail.mesaj}</p>
			</td>
		</tr>
		<tr>
			<td>
				{foreach from=$infoMail.items item=item key=key}
					<p><span class="label">{$item.label}: </span><span>{$item.text}</span></p>
				{/foreach}
			</td>
		</tr>
		<tr>
			<td>
				<p>
					{$infoMail.mesaj_bottom}
					{if $infoMail.signature==1}
						<br />
						echipa <a href="{$smarty.const.ROOT_HOST}" title="{$smarty.const.ROOT_HOST}">{$smarty.const.ROOT_HOST}</a><br />
						e-mail: <a href="mailto:{$smarty.const.EMAIL_CONTACT_ADDR}" title="{$smarty.const.EMAIL_CONTACT_ADDR}">{$smarty.const.EMAIL_CONTACT_ADDR}</a><br />
					{/if}
				</p>
			</td>
		</tr>
		<tr>
			<td class="logo"></td>
		</tr>
	</table>
</body>
</html>