{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtPages#}</a><span class="divider"></span></li>
    <li class="active">{#txtList#}</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
{include file="admin/inc/search_start.tpl"}               
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="page_titlu">{#txtTitle#}</label>
                <input type="text" class="form-control col-md-4" id="page_titlu" name="page_titlu" value="{if isset($moduleSession.search.page_titlu)}{$moduleSession.search.page_titlu}{/if}">
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="page_category">{#txtCategory#}</label>
                <select name="page_category" style="width:223px;" class="form-control">
                    <option value=""></option>
                    {foreach from=$categories item=item key=key}
                          <option value="{$key}" {if isset($moduleSession.search.page_category) && ($moduleSession.search.page_category==$key)}selected{/if}>{str_replace(","," / ",$item.nume)}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="page_status">{#txtActiv#}</label>
                <select name="page_status" style="width:223px;" class="form-control">
                    <option value=""></option>
                    <option value="1" {if isset($moduleSession.search.page_status) && $moduleSession.search.page_status==1}selected{/if}>{#txtYes#}</option>
                    <option value="0" {if isset($moduleSession.search.page_status) && $moduleSession.search.page_status==="0"}selected{/if}>{#txtNo#}</option>
                </select>
            </div>
        </div>
    </div>
{include file="admin/inc/search_end.tpl"}
<!-- MOTOR DE CAUTARE -->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>{#txtAdd#}</button>
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj=pages_category&action=page_list'">{#txtPageCategory#}</button>
    <!--<div class="pull-right">{#pgNoRecords#}: </div>-->
</div>
        
<div class="pull-right">{#pgNoRecords#}: {$recList|@count}</div>
{if $moduleSession.paging.noRowsResult > 0}
    {if $recList|@count>0}
        <table class="table table-hover table-bordered" id="table"> 
            <form name="form_list" method="post">
                <input type="hidden" name="act" value="">
                <input type="hidden" name="field_sort" value="">
                <thead>
                    <tr>
                        <tr> 								
                            <th width="30">Nr. Crt.</th>
                            
                            <th>Title</th>
                            <th style="vertical-align: middle; text-align:center;" width="120">Order</th>
                            <th style="vertical-align: middle; text-align:center;" width="40">Activ</th>
                            <th style="vertical-align: middle; text-align:center;" width="30">{#txtEdit#}</th>
                            <th width="50" style="vertical-align: middle;">
                                <span id="btnDel"><img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
                                <input type="checkbox" class="bd0" id="selecctall">
                            </th>
                        </tr>
						{assign var="categorie" value=""}
                    </tr>
                </thead>
                <tbody>
                    {assign var="cat" value=""}
                     {foreach from=$recList item=item key=key}
                        {math equation="1+x+((y-1)*z)" assign=idx x=$key y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
						{if $cat!= $item.page_category}
                            <tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
                                <td colspan="6" align="left">
                                    {if isset($categories[$item.page_category]['nume'])}
                                        <span>>></span> &nbsp;{str_replace(","," / ",$categories[$item.page_category]['nume'])|default:"uncategorised"|upper}
                                    {else}
                                        <span>>></span> &nbsp;{"uncategorised"|upper}
                                    {/if}
                                </td>
                            </tr>
                            {assign var="cat" value=$item.page_category}
                        {/if}
                        <tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
                            <td>{$idx}.</td>
                            
                            <td align="left">
                            {foreach from=$item.lkeys item=ilang key=ikey}
                                <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd&lang={$ilang|default:'ro'}"><img src="/img/admin/flags/{$SITE_LANGS.{$ilang|default:'1'}->language_flag}" style="vertical-align:middle;" /></a>
                                {$item.titles.$ikey}<br />
                            {/foreach}
                            </td>
                             <td style="vertical-align: middle; text-align:center;">
                                <div class="btn-group">
                                      <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=up&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                      <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=down&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                    </div>
                            </td>
                            <td style="vertical-align: middle; text-align:center;">
                                <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName={$flagName}&{$idName}={$item.$idName}" title="{#txtTitleSwitch#}" class="btn">
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
                            <td align="right"><input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0 jsx_checkbox" {if !$item.del_op}disabled{/if}></td>
                        </tr>
                    {/foreach}
                </tbody>
                </form>
        </table>
        <table class="pull-right">
            <tr>
                <td>{include file="tpl_utile/paging_admin.tpl" pagingVariables=$moduleSession.paging}</td>
            </tr>
        </table>
    {else}
        <table class="table table-bordered">
            <tr> 
                <td align="center">{#txtResultNull#}</td>
            </tr>
        </table>
    {/if}
    {else}
        <table class="table table-bordered">
            <tr>
                <td align="center">{if isset($moduleSession.search.act) && $moduleSession.search.act=='search'}{#txtSResultNull#}{else}{#txtResultNull#}{/if}</td>
            </tr>	
        </table>
    {/if}
{include file="admin/inc/bottom.tpl"}