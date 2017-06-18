<ul class="nav nav-list">
  {if $smarty.const.ADMINMENU_SHOW==1}
  <li class="nav-header">System Config</li>
  <li><a href="index.php?obj=menuadmin&action=page"><i class="icon-play"></i> Menu List/Edit</a></li>
  <li><a href="index.php?obj=smenuadmin&action=page"><i class="icon-play"></i> Sub-Menu List/Edit</a></li>
  {/if}
  {foreach from=$menuTop item=itemM key=keyM}
  <li class="nav-header">{$itemM.menuadmin_name}</li>
  {foreach from=$itemM.SM item=itemSM key=keySM}
    <li {if !$itemSM.access} class="disabled" {/if}>
    {if $itemSM.access}
		<a href="{$itemSM.smenuadmin_link}"> <i class="icon-play"></i> {$itemSM.smenuadmin_name}</a>
	{else}
		<a href="#"> {$itemSM.smenuadmin_name}</a>
	{/if}
    </li>
  {/foreach}
  {/foreach}
</ul>
