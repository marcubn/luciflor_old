<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
{literal}
<style>
img{border:none;}
</style>
{/literal}
</head>
<body>
<table style=" background:url({$smarty.const.ROOT_HOST}img/bgr.jpg) repeat; width:540px;" cellpadding="0" cellspacing="0">
<tr>
<td>
	<table style="width:500px; margin:0 auto;" cellpadding="0" cellspacing="0">
		<tr height="18"><td></td></tr>
		<tr style=" background-color:#fff;">
			<td colspan="2">
				<table>					
					<tr>
						<td colspan="2" style="width:320px; height:86px; padding:0 0 0 20px;">
							<a href="{$smarty.const.ROOT_HOST}"><img src="{$smarty.const.ROOT_HOST}img/logo.jpg" /></a>
						</td>
						<td width="195" colspan="2" style="height:60px; width:140px; padding:0px 0 0 0;">
							<p style="font-family:'Times New Roman', Times, serif; font-size:14px; color:#000; text-align:right; padding-right:20px; padding-top:40px;"><strong>{$subtitlu}</strong></p>
						</td>	
					</tr>
				</table>
			</td>		
		</tr>
		<tr height="18"><td></td></tr>
		<tr style="background-color:#fc3637; height:30px;">
			<td style=" padding:0 0 0 20px;"><p style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:14px; color:#fff; margin:0; padding:0;">{$nume}, bine ai venit pe TripStarter.ro</p></td>
			<td><p style="margin:0; padding:0 10px 0 0; font-family:Georgia, 'Times New Roman', Times, serif; font-size:10px; color:#fff; text-align:right;">{$email}</p></td>
			
		</tr>
	</table>
	<table style="width:540px;" cellpadding="0" cellspacing="18">
		<tr>
			<td style="background-color:#fff; padding:0 0 15px 0;">
				<p style="margin:10px 0 0 0; padding:0 20px 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;">Felicitari, contul dumneavoastra a fost creat cu succes. Click <a href="{$smarty.const.ROOT_HOST}" style="color:#000;">aici</a> pentru a beneficia de noile oportunitati.<br  /><br  />
				<span style="color:#797979;">{$mesaj|nl2br}</span>
				</p>
				
			</td>			
		</tr>					
	</table>	
</td>
</tr>
</table>


</body>
</html>
