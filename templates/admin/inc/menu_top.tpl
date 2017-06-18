<div style="max-width: 340px;" >
    <ul class="nav nav-list">
      {if $smarty.const.ADMINMENU_SHOW==1}
          <li class="nav-header">System Config</li>
          <li><a href="index.php?obj=menuadmin&action=page" {if $smarty.get.obj=='menuadmin'}class="selected"{/if}> Menu List/Edit</a></li>
          <li><a href="index.php?obj=smenuadmin&action=page" {if $smarty.get.obj=='smenuadmin'}class="selected"{/if}> Sub-Menu List/Edit</a></li>
      {/if}
      {foreach from=$menuTop item=itemM key=keyM}
          <li class="nav-header">{$itemM.menuadmin_name}</li>
          {foreach from=$itemM.SM item=itemSM key=keySM}
              <li {if !$itemSM.access} class="disabled" {/if}>
              {if $itemSM.access}
          			<a href="{$itemSM.smenuadmin_link}" {if $itemSM.smenuadmin_link==$smarty.server.REQUEST_URI|replace:'/admin/':''} class="selected"{/if}> {$itemSM.smenuadmin_name}</a>

          		{else}
          			<a href="#"> &Oslash; {$itemSM.smenuadmin_name}</a>
          		{/if}
              </li>
          {/foreach}
      {/foreach}
    </ul>
</div>