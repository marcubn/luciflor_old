{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Categorii</a><span class="divider"></span></li>
    <li class="active">List/Edit</li>
</ul> 
<!-- [-]Module name-->

<div class="form-group col-md-12">
    <button data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#myModal" class="btn btn-success "><span class="glyphicon glyphicon-plus"></span>Adauga categorie noua pe primul nivel</button>
    <div class="pull-right">{#pgNoRecords#}: {$recList|@count}</div>
</div>

{if $recList|@count>0}
    <table class="table table-hover table-bordered"> 
		<form name="form_list" method="post">
			<input type="hidden" name="act" value="">
                <thead>
					<tr>
						<th>No Crt / Nume</th>
						<th width="50">Articole</th>
						<th width="150">Adauga pe nivelul urmator</th>
						<th width="120">Order</th>
						<th width="40">Edit</th>
						<th width="40" align="right">
							<span id="btnDel"><img src="../img/admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
							<input type="checkbox" onClick="switchMultipleCheckBox(document.form_list, 'ids'); verifyRowChecked(document.form_list, 'ids');" class="bd0">
						</th>
					</tr>
                </thead>
                <tbody>
					{assign var=section value=""}
					{foreach from=$recList item=item key=key}
						{if $section!=$item.section_name}
							<tr class="warning">
								<td colspan="6" style="font-size:14px; font-weight:bold; padding-left:10px;"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;{$item.section_name}</td>
							</tr>
							{assign var=section value=$item.section_name}
						{/if}
						<tr onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
							<td align="left" style="padding-left:{$item.level*35+40}px; {if $item.level==0}font-weight:bold;{/if}">
								{$item.idx}. {$item.category_name}
							</td>
							<td>{$item.no_art|default:"--"}</td>
							<td>
                                <a href="index.php?obj={$smarty.get.obj}&action=page&parent={$item.$idName}" class="btn btn-default">
                                    <span class="glyphicon glyphicon-plus-sign"></span>Adauga
                                </a>
                            </td>
							<td>
								<div class="btn-group">
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=up&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                    <button onclick="window.location='index.php?obj={$smarty.get.obj}&action=update_order&act=down&{$idName}={$item.$idName}'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                </div>
							</td>
							<td>
                                <a href="index.php?obj={$smarty.get.obj}&action=page&{$idName}={$item.$idName}&act=upd">
                                    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
                                </a>
                            </td>
							<td align="right"><input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0" {if !$item.del_op}disabled{/if}></td>
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
        {if $moduleSession.search.act=='search'}{#txtSResultNull#}{else}{#txtResultNull#}{/if}
    </div> 
{/if}

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">{if $form_act.act=='upd'}Edit{else}Add{/if}</h4>
            </div>
             <form class="form-inline" role="form" name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',1);">
                <div class="modal-body">
                    <input type="hidden" name="act" value="{$form_act.act}" />
                    <input type="hidden" name="{$idName}" value="{$form_act.$idName}" />

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="category_section">Sectiune</label>
                            {if $form_act.act=="upd"}
                                <select name="category_section" style="width:180px;" class="form-control">
                                    {html_options values=$secList.0 selected=$form_act.category_section output=$secList.1}
                                </select>
                            {else}
                                <strong>{$form_act.sectionName}</strong>
                                <input type="hidden" name="category_section" value="{$form_act.category_section|default:0}">
                            {/if}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="category_section">Parent</label>
                            {if $form_act.parentName=="root"}
                                <select name="category_parent" style="width:180px;" class="form-control">
                                    <option value=""></option>
                                    {foreach from=$recList item=item key=key}
                                        <option value="{$item.$idName}" {if $form_act.category_parent==$item.$idName}selected="selected"{/if}>{$item.space}{$item.category_name}</option>
                                    {/foreach}
                                </select>
                            {else}
                                <strong>{$form_act.parentName}</strong>
                                <input type="hidden" name="category_parent" value="{$form_act.category_parent|default:0}">
                            {/if}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="category_name">Name</label>
                            <input type="text" class="form-control col-md-4" id="category_name" name="category_name" value="{$form_act.category_name}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="category_seo_name">SEO Name</label>
                            <input type="text" class="form-control col-md-4" id="category_seo_name" name="category_seo_name" value="{$form_act.category_seo_name}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-7">
                            <label for="{$priorityName}" class="col-md-2">Ordine</label>
                            <button onclick="document.form_act.{$priorityName}.value= '{$minOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-up"></span></button>
                            <button onclick="document.form_act.{$priorityName}.value= '{$maxOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-down"></span></button>
                            <input type="text" class="form-control pull-right" id="{$priorityName}" name="{$priorityName}" value="{$form_act.$priorityName|default:$maxOrder}" style="width: 50px;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label class="col-md-5">inapoi la formul de editare</label>
                            <input type="checkbox" name="backToEditForm" checked class="pull-left">
                            <button type="submit" class="btn btn-primary">salveaza</button>
                            {if $form_act.act=='upd'}
                                <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page'">go to list</button>
                            {/if}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    // Modal Link
    {if $smarty.get.category_id || $smarty.get.parent}
        $('#myModal').modal({
            keyboard: false,
            backdrop: 'static'
        });
        $('#myModal').on('hidden.bs.modal', function (e) {
            window.location='index.php?obj={$smarty.get.obj}&action=page';
        })
    {/if}
</script>
{include file="admin/inc/bottom.tpl"}