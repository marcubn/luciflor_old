<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
{literal}
	<style>
		p{ margin:0px; padding:0px;}
		span { font-family:Times New Roman; }
	</style>
{/literal}
</head>

<body style="font-family:Times New Roman; font-size:23px; color:#000000">
	<table align="center" border="0">
		<tr>
			<td style="font-size:40px;">
				<img src="{$smarty.const.ROOT_HOST}img/admin/logo.jpg" />
				<br /><br />
				<strong><span style="color:#FF0000;">{$info.newsletter_name}</span></strong>
			</td>
		</tr>
		<tr>
			<td>	
				{$info.newsletter_content|nl2br}
				<br /><br />
				<center>
					Pentru dezabonare newsletter click 
					<a href="{$smarty.const.ROOT_HOST}admin/index.php?obj=newsletter&action=unsubscribe&member=###MEMBER_CODE###" target="_blank">
						aici
					</a>
				</center>
			</td>
		</tr>
	</table>
	<img src="{$smarty.const.ROOT_HOST}utile.php?obj=image&action=thumbnail&imgSrc=img/admin/spacer.gif&toW=1&toH=1&news={$info.newsletter_id}&member=###MEMBER###&time=###TIME###" style="display:none;" />
</body>
</html>
<!-- not needed 
	{$info.newsletter_name}
	{$info.newsletter_content|nl2br}
	Pentru dezabonare newsletter click <a href="{$smarty.const.ROOT_HOST}admin/index.php?obj=newsletter&action=unsubscribe&member=###MEMBER_CODE###">aici</a><img src="{$smarty.const.ROOT_HOST}utile.php?obj=image&action=thumbnail&imgSrc=img/admin/spacer.gif&toW=1&toH=1&news={$info.newsletter_id}&member=###MEMBER###&time=###TIME###" style="display:none;" />
-->