{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtPageCategory#}</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-6 pull-left">
    <fieldset>
        <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
        <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
            <input type="hidden" name="act" value="{$form_act.act}">
            <input type="hidden" name="{$idName}" value="{$form_act.$idName}">  
            
            <div class="form-group row col-md-8">
                <label for="pages_cat_lang">{#txtLang#}</label>
                {if isset($smarty.get.lang)}
                    {assign var="lang" value=$smarty.get.lang}
                {else}
                    {assign var="lang" value=1}
                {/if}
                <select name="pages_cat_lang" onchange="document.location='index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$form_act.$idName}&act={$form_act.act}&lang='+this.value;" class="form-control pull-right">
                    {foreach from=$SITE_LANGS item=item key=key}
                        <option value="{$item->language_id}" {if $lang==$item->language_id} selected="selected" {/if}>{$item->language_name}</option>
                    {/foreach}
                </select>
            </div>
            
            <div class="form-group row col-md-7">
                <label for="pages_cat_name">{#txtName#}</label>
                <input type="text" class="form-control pull-right" id="pages_cat_name" name="pages_cat_name" value="{if isset($form_act.pages_cat_name)}{$form_act.pages_cat_name}{/if}" style="width: 200px;">
            </div>

             <div class="form-group col-md-12">
                <label>{#txtBackToEditForm#}</label>
                <input type="checkbox" name="backToEditForm" checked>
                <button type="submit" class="btn btn-primary">{#bSave#}</button>
                <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'">{#txtGoToList#}</button>
            </div>
        </form>
    </fieldset>
</div>
{include file="admin/inc/bottom.tpl"}