{include file="admin/inc/top.tpl"}
{include file="tpl_js/calendar.js.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}
<!-- [+]Module name -->
	<ul class="breadcrumb">
	    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Users</a><span class="divider"></span></li>
	    <li class="active">{#txtLogs#}</li>
	</ul> 
<!-- [+]Module name -->

<!-- MOTOR DE CAUTARE -->
<div class="form-group col-md-12 panel-info panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Motor de cautare</a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <form class="form-inline" role="form" name="form_search" action="index.php?obj={$smarty.get.obj}&action={$smarty.get.action}" method="post" onSubmit="return formValidate('form_search', 0);">
                <input type="hidden" name="act" value="search">
                <div class="panel-body col-md-5">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="userlog_userid">{#txtUserId#}</label>
                            <input type="text" class="form-control col-md-4" id="userlog_userid" name="userlog_userid" value="{if isset($moduleSession.search.userlog_userid)}{$moduleSession.search.userlog_userid}{/if}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="userlog_name">{#txtName#}</label>
                            <input type="text" class="form-control col-md-4" id="userlog_name" name="userlog_name" value="{if isset($moduleSession.search.userlog_name)}{$moduleSession.search.userlog_name}{/if}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="userlog_ip">IP</label>
                            <input type="text" class="form-control col-md-4" id="userlog_ip" name="userlog_ip" value="{if isset($moduleSession.search.userlog_ip)}{$moduleSession.search.userlog_ip}{/if}">
                        </div>
                    </div>
                </div>
                <div class="panel-body col-md-5">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="date11">{#txtLoginDate#} >= </label>
                            {if isset($moduleSession.search.date11)}
                                {include file="tpl_utile/date_elem.tpl" dateElemName="date11" dateElemValue=$moduleSession.search.date11}
                            {else}
                                {include file="tpl_utile/date_elem.tpl" dateElemName="date11" dateElemValue=""}
                            {/if}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-4" for="date12">{#txtLoginDate#} <= </label>
                            {if isset($moduleSession.search.date12)}
                                {include file="tpl_utile/date_elem.tpl" dateElemName="date12" dateElemValue=$moduleSession.search.date12}
                            {else}
                                {include file="tpl_utile/date_elem.tpl" dateElemName="date12" dateElemValue=""}
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="panel-footer col-md-12">
                    <div class="form-group col-md-4">
                        Paginare: <input type="text" name="noRowsDisplayed" class="form-control" value="{$moduleSession.paging.noRowsDisplayed}" style="width: 50px;"> inregistrari pe pagina
                    </div>
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-primary">cauta</button>
                        <button type="button" class="btn btn-danger" onClick="formReset('form_search')">reseteaza</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MOTOR DE CAUTARE -->
<div class="form-group col-md-12">
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
							<th>{#txtLoginDate#}</th>
							<th>{#txtLogoutDate#}</th>
							<th>{#txtUserId#}</th>
							<th>{#txtName#}</th>
							<th>IP</th>								
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
								<td>{$item.userlog_datelogin|date_format:$DTF}</td>
								<td>{if $item.userlog_datelogout!='0000-00-00 00:00:00' && $item.userlog_datelogout!=''}{$item.userlog_datelogout|date_format:$DTF}{else}--{/if}</td>
								<td>{if $item.userlog_userid|lower=='root'}<strong>{$item.userlog_userid}</strong>{else}{$item.userlog_userid}{/if}</td>
								<td>{$item.userlog_name}</td>
								<td>{$item.userlog_ip|default:'&nbsp;'}</td> 	
								<td align="right"><input type="checkbox" name="ids[]" value="{$item.$idName}" onClick="verifyRowChecked(document.form_list, 'ids')" class="bd0 jsx_checkbox {if !$item.del_op}disabled{/if}"></td>
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