{include file="admin/inc/top.tpl"}
{include file="tpl_js/delete_multiple.js.tpl"}
{include file="tpl_js/calendar.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Articles</a><span class="divider"></span></li>
    <li class="active">List/Edit</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
    {include file="admin/inc/search_start.tpl"}
        <div class="panel-body col-md-5">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="article_title">Title</label>
                    <input type="text" class="form-control col-md-4" id="article_title" name="article_title" value="{$moduleSession.search.article_title}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="tag">Tag</label>
                    <input type="text" class="form-control col-md-4" id="tag" name="tag" value="{$moduleSession.search.tag}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="article_date_1">Date >= </label>
                    {include file="tpl_utile/date_elem.tpl" dateElemName="article_date_1" dateElemValue=$moduleSession.search.article_date_1}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="article_date_2">Date <= </label>
                    {include file="tpl_utile/date_elem.tpl" dateElemName="article_date_2" dateElemValue=$moduleSession.search.article_date_2}
                </div>
            </div>
        </div>
        <div class="panel-body col-md-5">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="category">Category</label>
                    <select name="category" style="width:180px;" class="form-control">
                        {assign var=section value=""}
                        {foreach from=$catList item=item key=key}
                            {if $section!=$item.section_name}
                                {if $key>0}</optgroup>{/if}<optgroup label="{$item.section_name}">
                                {assign var=section value=$item.section_name}
                            {/if}
                            <option value="{$item.category_id}" {if $item.category_id==$moduleSession.search.category}selected="selected"{/if}>{$item.space}{$item.category_name}</option>
                        {/foreach}
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="article_status">Publicat</label>
                    <select name="article_status" style="width:180px;" class="form-control">
                        <option value=""></option>
                        <option value="1" {if $moduleSession.search.article_status==1}selected{/if}>{#txtYes#}</option>
                        <option value="0" {if $moduleSession.search.article_status==="0"}selected{/if}>{#txtNo#}</option>
                    </select>
                </div>
            </div>
             <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="article_summary">Summary</label>
                    <input type="text" class="form-control col-md-4" id="article_summary" name="article_summary" value="{$moduleSession.search.article_summary}">
                </div>
            </div>
             <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4" for="text">Text</label>
                    <input type="text" class="form-control col-md-4" id="text" name="text" value="{$moduleSession.search.text}">
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
			<form name="form_status" method="post">
				<input type="hidden" name="change_status" value="true" />
				<input type="hidden" name="{$idName}" id="_{$idName}" value="" />
				<input type="hidden" name="status" value="" />
				<input type="hidden" name="reason" value="" />
			</form>
			<form name="form_list" method="post">
				<input type="hidden" name="act" value="">
				<input type="hidden" name="field_sort" value="">
				<thead>
                    <tr>    
						<th width="50">Nr. Crt.</th>
						{include name="Title" field="article_title" file="tpl_js/order_column.tpl"}
						{include name="Date" field="article_date" width="150" file="tpl_js/order_column.tpl"}
						<th width="150">
                        Comentarii <br />
                        In asteptare / Toate
                        </th>
						<th width="70">Status</th>
						<th width="70">Home</th>
						<th width="40">Preview</th>
						<th width="40">Edit</th>
						<th width="40" align="right">
							<span id="btnDel"><img src="../img/admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
							<input type="checkbox" onClick="switchMultipleCheckBox(document.form_list, 'ids'); verifyRowChecked(document.form_list, 'ids');" class="bd0">
						</th>
					</tr>
                </thead>
                <tbody>
					{foreach from=$recList item=item key=key}
                        {assign var="itemid" value=$item.article_id}
						{math equation="1+x+((y-1)*z)" assign=idx x=$key y=$moduleSession.paging.pgNo z=$moduleSession.paging.noRowsDisplayed}
						<tr align="center" onmouseover="this.className='bg4'" onmouseout="this.className='bg0'">
							<td valign="top">{$idx}.</td>
							<td align="left">
                            {$item.article_title}
                            <br />
                            &nbsp;&nbsp;&raquo;<span class="blue">{$item.section_name}</span> <span class="blue">{$item.category_name}</span> 
                            </td>
							<td>{$item.article_date}</td>
							<td>
                            {$comments_moderate.$itemid->nr_c|default:"0"}
                            /
                            {$comments.$itemid->nr_c|default:"0"}
                            </td>
							<td>
                                <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName=article_status&{$idName}={$item.$idName}" title="{#txtTitleSwitch#}">
                                    {if 1==$item.article_status}
                                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                                    {else}
                                        <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                                    {/if}
                                </a>
                            </td>
							<td>
                                <a href="index.php?obj={$smarty.get.obj}&action=switch&fieldName=article_home&{$idName}={$item.$idName}" title="{#txtTitleSwitch#}">
                                    {if 1==$item.article_home}
                                        <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
                                    {else}
                                        <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>
                                    {/if}
                                </a>
                            </td>
							<td align="center">
                                <a href="javascript: popup('index.php?obj={$smarty.get.obj}&action=preview&{$idName}={$item.$idName}', 600, 600, '', 'yes'); void(false);">
                                    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-zoom-in"></span></button>
                                </a>
                            </td>
							<td align="center">
                                <a href="index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$item.$idName}&act=upd">
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
            {#pgInvalidNoPage#}
        </div>
    {/if}
{else}
    <div class="form-group col-md-12 panel-info">
        {if $moduleSession.search.act=='search'}{#txtSResultNull#}{else}{#txtResultNull#}{/if}
    </div>      
{/if}
{include file="admin/inc/bottom.tpl"}