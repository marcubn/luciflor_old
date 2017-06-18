{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtMainTeasers#}</a><span class="divider"></span></li>
    <li class="active">List</li>
</ul> 
<!-- [-]Module name-->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>{#txtAddSlide#}</button>
    <div class="pull-right">{#pgNoRecords#}: {$moduleSession.paging.noRowsResult}</div>
</div>

{if $moduleSession.paging.noRowsResult > 0}
    {if $recList|@count>0}
        <form name="form_list" method="post">
            <input type="hidden" name="act" value="">
            <input type="hidden" name="field_sort" value="">
            <table class="table table-hover table-bordered" >
                <thead>
                    <tr> 	
                        <th >{#txtName#}</th>
                        <th width="120">{#txtOrdering#}</th>
                        <th width="130">{#txtInterval#}</th>
                        <th width="50">{#txtStatus#}</th>
                        <th width="50">{#txtEdit#}</th>
                        <th width="50" style="vertical-align: middle;">
                            <span id="btnDel"><img src="/img/admin/utile/del.gif" border="0" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
                            <input type="checkbox" class="bd0" id="selecctall">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$recList item=item key=key}
                        <tr align="center" >
                            <td align="left" style="{if $item->level==1}font-weight:bold;{/if}">{$item.name}</td>
                            <td>
                                <div class="btn-group">
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=up&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=down&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                {if $item.status==1}
                                    {if $item.publish_date=="0000-00-00 00:00:00"}
                                        <em>{#txtAlways#}</em>
                                    {else}
                                        {$item.publish_date|date_format:"%d %B @%H:%M"} <br /> {$item.unpublish_date|date_format:"%d %B @%H:%M"}
                                    {/if}
                                {else}
                                    <em>nepublicat</em>
                                {/if}
                            </td>
                            <td align="left">
                                <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName={$flagName}&{$idName}={$item.id}" title="{#txtTitleSwitch#}">
                                    {if 1==$item.status}
                                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                                    {else}
                                        <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                                    {/if}
                                </a>
                            </td>
                            <td align="center">
                                <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd" class="btn">
	                            	<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
	                            </a>
                            </td>
                            <td align="right"><input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0 jsx_checkbox" {if !$item.del_op}disabled{/if}></td>
                        </tr>
                    {/foreach}
                </tbody>
                </form>
            </table>
            <div class="form-group col-md-12 panel-info">
                {include file="tpl_utile/paging_admin.tpl" pagingVariables=$moduleSession.paging}
            </div>
    {else}
        <div class="form-group col-md-12 panel-info">
            {#pgInvalidNoPage#}
        </div>
    {/if}
{else}
    <div class="form-group col-md-12 panel-info">
        {if $moduleSession.search.act=='search'}{#txtSResultNull#}{else}{#txtResultNull#}{/if}
    </div>      
{/if}

{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>
{/literal}
{include file="admin/inc/bottom.tpl"}