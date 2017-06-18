{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">SEO Manager</a><span class="divider"></span></li>
    <li class="active">List/Edit</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
{include file="admin/inc/search_start.tpl"}         
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="seo_title">Titlu</label>
                <input type="text" class="form-control col-md-4" id="seo_title" name="seo_title" value="{$moduleSession.search.seo_title}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="seo_url">Url</label>
                <input type="text" class="form-control col-md-4" id="seo_url" name="seo_url" value="{$moduleSession.search.seo_url}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="seo_active">Activ</label>
                <select name="seo_active" style="width:180px;" class="form-control">
                    <option value=""></option>
                    <option value="1" {if $moduleSession.search.seo_active==1}selected{/if}>{#txtYes#}</option>
                    <option value="0" {if $moduleSession.search.seo_active==="0"}selected{/if}>{#txtNo#}</option>
                </select>
            </div>
        </div>
    </div>
{include file="admin/inc/search_end.tpl"}
<!-- MOTOR DE CAUTARE -->

<div class="form-group col-md-12">
    <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>Adauga</button>
    <div class="pull-right">{#pgNoRecords#}: {$moduleSession.paging.noRowsResult}</div>
</div>
{if $moduleSession.paging.noRowsResult > 0}
    {if $recList|@count>0}
        <table class="table table-hover table-bordered"> 
            <form name="form_list" method="post">
                <input type="hidden" name="act" value="">
                <input type="hidden" name="field_sort" value="">
                    <thead>	
                        <tr>	
							<th width="40">Nr. Crt.</th>
							<th>Seo Url</th>
							<th>Seo Title</th>
							<th width="80">Active</th>
							<th width="80">{#txtEdit#}</th>
							<th width="80" align="center">
								<span id="btnDel">
                                    <img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle">
                                </span>
                                <input type="checkbox" onClick="switchMultipleCheckBox(document.form_list, 'ids'); verifyRowChecked(document.form_list, 'ids');" class="bd0">
                                {*}
		                            <span class="btn btn-default"><i class="glyphicon glyphicon-trash"></i></span>
		                            <input style="text-align: center;" type="checkbox" onClick="switchMultipleCheckBox(document.form_list, 'ids'); verifyRowChecked(document.form_list, 'ids');">
	                            {*}
	                        </th>
						</tr>
	                </thead>
	                <tbody>
						{foreach from=$recList item=item key=key}
							{math equation="1+x+((y-1)*z)" assign=idx x=$key y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
	                        <tr>
								<td>{$idx}.</td>
								<td align="left">{$item.seo_url}</td>
								<td align="left">{$item.seo_title}</td>
								<td>
									<a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName={$flagName}&{$idName}={$item.$idName}" title="{#txtTitleSwitch#}">
										{if 1==$item.$flagName}
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
								<td align="center"><input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0" {if !$item.del_op}disabled{/if}></td>
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
{include file="admin/inc/bottom.tpl"}