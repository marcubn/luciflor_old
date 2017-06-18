{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtTexte#}</a><span class="divider"></span></li>
    <li class="active">{#txtList#}</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
{include file="admin/inc/search_start.tpl"}               
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="text_titlu">Category</label>
                <select name="cid" style="width:223px;" class="form-control">
                    <option value="">- all -</option>
                    {foreach from=$categories item=item}
                    <option value="{$item.texte_cat_id}" {if isset($moduleSession.search.cid) && $moduleSession.search.cid==$item.texte_cat_id} selected="selected"{/if}>{$item.texte_cat_name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="text_titlu">{#txtTitle#}</label>
                <input type="text" class="form-control col-md-4" id="text_titlu" name="text_titlu" value="{if isset($moduleSession.search.text_titlu)}{$moduleSession.search.text_titlu}{/if}">
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="text_titlu">COD</label>
                <input type="text" class="form-control col-md-4" id="text_alias" name="text_alias" value="{if isset($moduleSession.search.text_alias)}{$moduleSession.search.text_alias}{/if}">
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="text_text">{#txtText#}</label>
                <input type="text" class="form-control col-md-4" id="text_text" name="text_text" value="{if isset($moduleSession.search.text_text)}{$moduleSession.search.text_text}{/if}">
            </div>
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="text_status">{#txtActiv#}</label>
                <select name="text_status" style="width:223px;" class="form-control">
                    <option value=""></option>
                    <option value="1" {if isset($moduleSession.search.text_status) && $moduleSession.search.text_status==1}selected{/if}>{#txtYes#}</option>
                    <option value="0" {if isset($moduleSession.search.text_status) && $moduleSession.search.text_status==="0"}selected{/if}>{#txtNo#}</option>
                </select>
            </div>
        </div>
    </div>
{include file="admin/inc/search_end.tpl"}
<!-- MOTOR DE CAUTARE -->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>{#txtAdd#}</button>
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj=texte_category&action=page_list'">{#txtTextCategory#}</button>
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