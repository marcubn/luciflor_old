{if isset($modal) && $modal!=""}
    {include file="admin/inc/top_modal.tpl"}
{else}
    {include file="admin/inc/top.tpl"}
{/if}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list&parent_id={$smarty.get.parent_id}">{$moduleTitle}</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->
<div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
            <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
                <input type="hidden" name="act" value="{$form_act.act}">
                <input type="hidden" name="{$idName}" value="{$form_act.$idName}">  

                {foreach from=$form_fields item=field}
                    {assign var="field_name" value=$field->name}
                <div class="form-group row col-md-8">
                    <label for="{$field->name}">{$field->label}</label>
                {if $field->type=="lang"}
                    {assign var="lang" value=$smarty.get.lang|default:"ro"}
                    <select name="{$field->name}" id="{$field->name}" onchange="document.location='index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$form_act.$idName}&act={$form_act.act}&lang='+this.value;" class="form-control pull-right">
                        {foreach from=$SITE_LANGS item=item key=key}
                            <option value="{$item->language_id}" {if $lang==$item->language_id} selected="selected" {/if}>{$item->language_name}</option>
                        {/foreach}
                    </select>
                {elseif $field->type=="list"}

                    <select name="{$field_name}" class="form-control pull-right" >
                        {foreach from=$field->options item=item key=key}
                            <option value="{$item->value}" {if isset($form_act.$field_name) && $form_act.$field_name==$item->value}selected="selected"{/if}>{$item->name}</option>
                        {/foreach}
                    </select>
                {elseif $field->type=="text"}
                    <input type="text" class="form-control pull-right" id="{$field->name}" name="{$field->name}" value="{if isset($form_act.$field_name)}{$form_act.$field_name}{/if}" style="width: 200px;">
                {elseif $field->type=="hidden"}
                    <input type="hidden" class="form-control pull-right" id="{$field->name}" name="{$field->name}" value="{if isset($field->params.default)}{$field->params.default}{else}{if isset($form_act.$field_name)}{$form_act.$field_name}{/if}{/if}" style="width: 200px;">
                {elseif $field->type=="order"}
                    <input type="text" class="form-control pull-right" id="{$field->name}" name="{$field->name}" value="{if isset($form_act.$field_name)}{$form_act.$field_name}{else}{$maxOrder}{/if}" style="width: 50px;">
                {elseif $field->type=="datepicker"}

                    <a href="javascript:applyStyle('{$field->name}', 'value', '', 0); void(false);" class="pull-right">
                        <img src="{$smarty.const.ROOT_HOST}/img/admin/utile/icon-delete.png" hspace="0" border="0" align="middle" alt="" />
                    </a>
                    <input type="text" style="width: 180px;" name="{$field->name}" id="{$field->name}" class="datepicker form-control pull-right" value="{if isset($form_act.$field_name)}{$form_act.$field_name}{/if}"
                           readonly />
                {literal}
                    <script type="text/javascript">
                        $(function() {
                            $( ".datepicker" ).datepicker({
                                buttonImage: "/img/calendar_day.png",
                                buttonImageOnly: true,
                                dateFormat: 'yy-mm-dd'
                            });
                        });
                    </script>
                {/literal}
                {elseif $field->type=="timepicker"}
                    <script type="text/javascript" src="/js/jquery/timepicker_addon/jquery-ui-timepicker-addon.js"></script>
                    <link rel="stylesheet" type="text/css" href="/js/jquery/timepicker_addon/jquery-ui-timepicker-addon.css" />
                    <input type="text" class="form-control pull-right" id="{$field->name}" name="{$field->name}" value="{if isset($form_act.$field_name)}{$form_act.$field_name}{/if}" style="width: 200px;">
                {literal}
                    <script type="text/javascript">
                        $('#{/literal}{$field->name}{literal}').datetimepicker({
                            timeFormat: "hh:mm:ss",
                            dateFormat:"yy-mm-dd"
                        });
                    </script>
                {/literal}
                {elseif  $field->type=="textarea"}
                    <div class="controls">
                        <textarea rows="3" class="form-control" name="{$field->name}" >{$form_act.$field_name}</textarea>
                    </div>
                {elseif  $field->type=="upload"}
                    <div class="controls">
                        {include file="tpl_utile/tpl_upload_act.tpl" file_name="{$field->name}" url={$field->params.url}}
                    </div>
                {elseif  $field->type=="editor"}
                        {include file="admin/inc/mce.tpl"}
                        <textarea name="{$field->name}" id="{$field->name}" rows="5" class="mceEditor pull-right">{$form_act.$field_name}</textarea>
                {elseif  $field->type=="switch"}
                    <input type="checkbox" style="margin-left: 120px" name="{$field->name}" id="{$field->name}" value="1" {if $form_act.$field_name!=="0"}checked{/if}> 
                {elseif  $field->type=="modified"}
                {$form_act.modified} ( {$form_act.modified_by_usr} )
                {else}
                    Error: Unkown field {$field->type}
                {/if}
                </div>
                {/foreach}


                <div class="form-group col-md-12">
                    <label>inapoi la formul de editare</label>
                    <input type="checkbox" name="backToEditForm" checked>
                    <button type="submit" class="btn btn-primary">save</button>
                    <button type="button" class="btn btn-primary" onClick="popup('index.php?obj={$smarty.get.obj}&action=view_table_log&oid={$form_act.$idName}', 700, 700, 'logger', 1); void(false);">history</button>
                    <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list&parent_id={$smarty.get.parent_id}'">go to list</button>
                </div>

            </form>
        </fieldset>
    </div>
    {if isset($uplphoto) && $uplphoto!=""}
        {include file="admin/inc/uplinc.tpl"}
    {/if}
</div>
{if isset($modal) && $modal!=""}
    {include file="admin/inc/bottom_modal.tpl"}
{else}
    {include file="admin/inc/bottom.tpl"}
{/if}