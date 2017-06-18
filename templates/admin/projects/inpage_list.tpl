{include file="admin/inc/top_modal.tpl"}
<table class="table table-hover table-bordered" id="table">
    <form name="form_list" method="post">
        <input type="hidden" name="act" value="">
        <input type="hidden" name="field_sort" value="">
        <thead>
        <tr>
            <th width="30">{#txtNoCrt#}</th>

            <th>{#txtTitle#}</th>
            <th style="vertical-align: middle; text-align:center;" width="40">{#txtActiv#}</th>
            <th style="vertical-align: middle; text-align:center;" width="30">{#txtEdit#}</th>
            <th width="50" style="vertical-align: middle;">
                <span id="btnDel"><img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
                <input type="checkbox" class="bd0" id="selecctall">
            </th>
        </tr>
        {assign var="categorie" value=""}
        </thead>
        <tbody>
        {foreach from=$recList item=i key=k}

            <tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
                <td colspan="5">{$k}</td>
            </tr>

            {foreach from=$i item=item key=key}
                {math equation="1+x+((y-1)*z)" assign=idx x=$key y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
                <tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
                    <td>{$idx}.</td>

                    <td align="left">
                        {foreach from=$item.lkeys item=ilang key=ikey}
                            <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd&lang={$ilang|default:'ro'}"><img src="/img/admin/flags/{$SITE_LANGS.{$ilang|default:'1'}->language_flag}" style="vertical-align:middle;" /></a>
                            {$item.titles.$ikey}<br />
                        {/foreach}
                    </td>
                    <td style="vertical-align: middle; text-align:center;">
                        <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName={$flagName}&{$idName}={$item.$idName}" title="Title Switch" class="btn">
                            {if $item.$flagName==1}
                                <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                            {else}
                                <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                            {/if}
                        </a>
                    </td>
                    <td style="vertical-align: middle; text-align:center;">
                        <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd" class="btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button></a>
                    </td>
                    <td align="right">
                        <input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0 jsx_checkbox" {if !$item.del_op}disabled{/if}>
                    </td>
                </tr>
            {/foreach}
        {/foreach}
        </tbody>
    </form>
</table>
{include file="admin/inc/bottom_modal.tpl"}