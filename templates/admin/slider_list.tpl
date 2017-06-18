{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Slider</a><span class="divider"></span></li>
    <li class="active">List</li>
</ul> 
<!-- [-]Module name-->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>Adauga</button>
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
                        <th width="30">Nr. Crt.</th>
                        <!--<th width="30">{#txtEdit#}</th>-->
                        <th>Link</th>
                        <th width="100">Poza</th>
                        <th width="100">Ordine</th>
                        <th width="70">Activ</th>
                        <th width="40" align="right">
                            <span id="btnDel"><img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
                            <input type="checkbox" onClick="switchMultipleCheckBox(document.form_list, 'ids'); verifyRowChecked(document.form_list, 'ids');" class="bd0">
                        </th>
                    </tr>
                </thead>
                <tbody>   
                    {foreach from=$recList item=li key=ke}
                        {math equation="1+x+((y-1)*z)" assign=idx x=$ke y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
                        <tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
                            <td>{$idx}.</td>
                            <!--<td align="center">
                                <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$li.id}&act=upd">
                                    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
                                </a>
                            </td>-->
                            <td align="left">
                                {foreach from=$li.lkeys item=ilang key=ikey}
                                    <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$li.$idName}&act=upd&lang={$ilang|default:'ro'}"><img src="/img/admin/flags/{$SITE_LANGS.{$ilang|default:'1'}->language_flag}" style="vertical-align:middle;" /></a>
                                    {$li.titles.$ikey|strip_tags}<br />
                                {/foreach}
                            </td>
                            <td>
                                {if isset($li.banner) && $li.banner!=""}
                                    <a href="/upl/banners/{$li.banner}" class="fancybox">
                                        <img src="/upl/banners/{$li.banner}" width="40">
                                    </a>
                                {else}
                                    -
                                {/if}
                            </td>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=up&{$idName}={$li.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=down&{$idName}={$li.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                </div>
                            </td>
                            <td>
                                <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName={$flagName}&{$idName}={$li.id}" title="{#txtTitleSwitch#}">
                                    {if 1==$li.status}
                                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                                    {else}
                                        <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                                    {/if}
                                </a>
                            </td>
                            <td align="right"><input type="checkbox" name="ids[]" value="{$li.id}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0" ></td>
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