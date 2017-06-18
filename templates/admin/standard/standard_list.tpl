{if isset($modal) && $modal!=""}
    {include file="admin/inc/top_modal.tpl"}
{else}
    {include file="admin/inc/top.tpl"}
{/if}
{include file="tpl_js/delete_multiple.js.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{$moduleTitle}</a><span class="divider"></span></li>
    <li class="active">{#txtList#}</li>
</ul> 
<!-- [-]Module name-->

<!-- MOTOR DE CAUTARE -->
{*include file="admin/inc/search_start.tpl"}               
    <div class="panel-body col-md-5">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="sample1_titlu">{#txtTitle#}</label>
                <input type="text" class="form-control col-md-4" id="sample1_titlu" name="sample1_titlu" value="{if isset($moduleSession.search.sample1_titlu)}{$moduleSession.search.sample1_titlu}{/if}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="sample1_text">{#txtText#}</label>
                <input type="text" class="form-control col-md-4" id="sample1_text" name="sample1_text" value="{if isset($moduleSession.search.sample1_text)}{$moduleSession.search.sample1_text}{/if}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="sample1_status">{#txtActiv#}</label>
                <select name="sample1_status" style="width:180px;" class="form-control">
                    <option value=""></option>
                    <option value="1" {if isset($moduleSession.search.sample1_status) && $moduleSession.search.sample1_status==1}selected{/if}>{#txtYes#}</option>
                    <option value="0" {if isset($moduleSession.search.sample1_status) && $moduleSession.search.sample1_status==="0"}selected{/if}>{#txtNo#}</option>
                </select>
            </div>
        </div>
    </div>
{include file="admin/inc/search_end.tpl"*}
<!-- MOTOR DE CAUTARE -->

<div class="form-group col-md-12">
    {if isset($smarty.get.parent_id)}
        <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act&parent_id={$smarty.get.parent_id}'"><span class="glyphicon glyphicon-plus"></span>{#txtAdd#}</button>
    {else}
     <button type="button" class="btn btn-success" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_act'"><span class="glyphicon glyphicon-plus"></span>{#txtAdd#}</button>
    {/if}

    {if isset($export) && $export!=""}
        <a href="index.php?obj={$smarty.get.obj}&action=page_export" target="_blank"  class="btn btn-success"><i class="glyphicon glyphicon-download-alt"></i>Export</a>
    {/if}
    <!--<div class="pull-right">{#pgNoRecords#}: </div>-->
</div>

<table class="table table-hover table-bordered" id="table"> 
    <form name="form_list" method="post">
        <input type="hidden" name="act" value="">
        <input type="hidden" name="field_sort" value="">
            <thead>	
                <tr>							
                    {foreach from=$list_fields item=field}
                        <th {if $field->type=="switch"}width="50"{elseif $field->type=="order"}width="100"{/if}>{$field->label}</th>
                    {/foreach}
                    <th width="50">{#txtEdit#}</th>
                    <th width="50" style="vertical-align: middle;">
                        <span id="btnDel"><img src="{$smarty.const.IMAGES_URL}admin/utile/del.gif" style="filter:Alpha(Opacity=20)" align="absmiddle"></span>
                        <input type="checkbox" class="bd0" id="selecctall">
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </form>
</table>
<script type="text/javascript">
    var parent = "{$smarty.get.parent_id}";
    if(!parent)
        var parent = null;
    $(document).ready(function() {
        var table = $('#table').dataTable({
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [-1, -2] /* 1st one, start by the right */
            }],
            "bProcessing": true,
            "bServerSide": false,
            "stateSave": true,
            "ajax": {
                "url": "/admin/index.php?obj={$smarty.get.obj}&action=ajax_table",
                "type": "POST",
                "data": {
                    "parent": parent
                }
            }
        });
    } );
    
</script>
{if isset($modal) && $modal!=""}
    {include file="admin/inc/bottom_modal.tpl"}
{else}
    {include file="admin/inc/bottom.tpl"}
{/if}