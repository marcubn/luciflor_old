{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
	<ul class="breadcrumb">
	    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Users</a><span class="divider"></span></li>
	    <li class="active">List/Edit</li>
	</ul> 
<!-- [+]Module name -->

<!-- MOTOR DE CAUTARE -->
{include file="admin/inc/search_start.tpl"}
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="user_userid">{#txtUserId#}</label>
                <input type="text" class="form-control col-md-4" id="user_userid" name="user_userid" value="{if isset($moduleSession.search.user_userid)}{$moduleSession.search.user_userid}{/if}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="user_name">{#txtName#}</label>
                <input type="text" class="form-control col-md-4" id="user_name" name="user_name" value="{if isset($moduleSession.search.user_name)}{$moduleSession.search.user_name}{/if}">
            </div>
        </div>
    </div>
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="user_email">{#txtEmail#}</label>
                <input type="text" class="form-control col-md-4" id="user_email" name="user_email" value="{if isset($moduleSession.search.user_email)}{$moduleSession.search.user_email}{/if}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="user_active">{#txtStatus#}</label>
                <select name="user_active" id="user_active" class="form-control" style="width:180px;"> 
                    <option value=""></option> 
                    <option value="1" {if isset($moduleSession.search.user_active) && $moduleSession.search.user_active=='1'}selected{/if}>{#txtActiv#}</option> 
                    <option value="0" {if isset($moduleSession.search.user_active) && $moduleSession.search.user_active=='0'}selected{/if}>{#txtBlocked#}</option> 
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
    {if $listRecords|@count>0}
        <table class="table table-hover table-bordered"> 
            <form name="form_list" method="post">
                <input type="hidden" name="act" value="">
                <input type="hidden" name="field_sort" value="">
                    <thead> 
                        <tr> 
							<th>{#txtNoCrt#}</th>
							<th>{#txtCreationDate#}</th> 
							<th>{#txtName#}</th> 
							<th>{#txtUserId#}</th> 
							{*<th>{#txtPass#}</th>*}
							<th>{#txtEmail#}</th> 
							<th>{#txtPermissions#}</th> 
							<th>{#txtCreatedBy#}</th>
							<th>{#txtStatus#}</th> 
							<th>{#txtEdit#}</th>
							<th width="50" style="vertical-align: middle;">
								<span id="btnDel"><img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
								<input type="checkbox" class="bd0" id="selecctall">
							</th>
						</tr> 
					</thead>
					<tbody>
						{foreach from=$listRecords item=item key=key}
							{math equation="1+x+((y-1)*z)" assign=idx x=$key y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
							<tr align="center"> 
								<td>{$idx}.</td>
								<td>{$item.user_datec|date_format:$DF}</td>
								<td>{$item.user_name}</td> 
								<td>{if $item.user_userid|lower=='root' || $UL.user_userid|lower=='ciprian.susanu'}<strong>{$item.user_userid}</strong>{else}{$item.user_userid}{/if}</td> 
								{*<td>{if $UL.user_userid|lower=='root' || $UL.user_userid|lower=='ciprian.susanu' || $UL.$idName == $item.$idName}{$item.pass_dec}{else}<font color="#CCCCCC">******</font>{/if}</td>*}
								<td>{$item.user_email|default:'&nbsp;'}</td> 	
								<td>
									<button type="button" class="btn btn-default example" data-container="body" data-html="true" data-toggle="popover" data-placement="bottom" data-content="
									<ul class='list-group' style='margin-bottom: 0'>
										{foreach from=$item.permiss.assigned.name item=itemP}
											<li class='list-group-item'>
												{$itemP}
											</li>
										{/foreach}
									</ul>
									">{$item.no_permiss} - view</button>
								</td>
								<td>
                                    {if isset($item.created_by_name)}
                                        {$item.created_by_name}
                                    {else}
                                        {#txtSystem#}
                                    {/if}
                                </td>
								<td>
									{if $item.flag_op}
										<a href="index.php?obj={$smarty.get.obj}&action=switch_status&{$idName}={$item.$idName}">
											{if 1==$item.$flagName}
	                                            <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
	                                        {else}
	                                            <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
	                                        {/if}
										</a>
									{else}
										{if 1==$item.$flagName}
                                            <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                                        {else}
                                            <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                                        {/if}
									{/if}
								</td>
								<td align="center">
									{if $item.upd_op}
									<a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd">
										<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
									</a>
									{else}
										<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
									{/if}									
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
<script type="text/javascript">
	$('.example').popover();
</script>
<style type="text/css">
	.popover-content {
		padding: 0 !important;
	}
	.popover {
		border: 0 !important;
	}
</style>
{include file="admin/inc/bottom.tpl"}