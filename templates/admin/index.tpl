{include file="admin/inc/top.tpl"}
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div align="center">
	{if $numar == 0}
		Nu ai nici un mesaj necitit.
	{else}
		Ai {$numar} mesaje necitite. Click <a href="/admin/index.php?obj=contact&action=page_list">aici</a> pentru a le vedea.
	{/if}
</div>
<br>
{if $redirectToIndex==1}
	<script language="javascript">window.location='index.php';</script>
{/if}
{include file="admin/inc/bottom.tpl"}
