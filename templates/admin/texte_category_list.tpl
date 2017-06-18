{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtTextCategory#}</a><span class="divider"></span></li>
    <li class="active">{#txtList#}</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
{include file="admin/inc/search_start.tpl"}               
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="texte_cat_name">{#txtName#}</label>
                <input type="text" class="form-control col-md-4" id="texte_cat_name" name="texte_cat_name" value="{$moduleSession.search.texte_cat_name}">
            </div>
        </div>
    </div>
{include file="admin/inc/search_end.tpl"}
<!-- MOTOR DE CAUTARE -->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>{#txtAdd#}</button>
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
                                <th width="30">{#txtNoCrt#}</th>

                                <th>{#txtName#}</th>

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
                         {foreach from=$recList item=item key=key}
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