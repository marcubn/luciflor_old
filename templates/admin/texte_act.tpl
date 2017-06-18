{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtTexte#}</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-6 pull-left">
    <fieldset>
        <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
        <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
            <input type="hidden" name="act" value="{if isset($form_act.act)}{$form_act.act}{/if}">
            <input type="hidden" name="{$idName}" value="{if isset($form_act.$idName)}{$form_act.$idName}{/if}">  
            
            <div class="form-group row col-md-8">
                <label for="text_lang">{#txtLang#}</label>
                {if isset($smarty.get.lang)}
                    {assign var="lang" value=$smarty.get.lang}
                {else}
                    {assign var="lang" value=1}
                {/if}
                <select name="text_lang" onchange="document.location='index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={if isset($form_act.$idName)}{$form_act.$idName}{/if}&act={$form_act.act}&lang='+this.value;" class="form-control pull-right">
                    {foreach from=$SITE_LANGS item=item key=key}
                        <option value="{$item->language_id}" {if isset($lang) && ($lang==$item->language_id)} selected="selected" {/if}>{$item->language_name}</option>
                    {/foreach}
                </select>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="text_titlu">{#txtTitle#}</label>
                <input type="text" class="form-control pull-right" id="text_titlu" {if !$canEdit} disabled="disabled" {/if} name="text_titlu" value="{if isset($form_act.text_titlu)}{$form_act.text_titlu}{/if}" style="width: 216px;">
            </div>
            
            <div class="form-group row col-md-8">
                <label for="text_alias">{#txtCode#}</label>
                <input type="text" class="form-control pull-right" {if !$canEdit} disabled="disabled" {/if} id="text_alias" name="text_alias" value="{if isset($form_act.text_alias)}{$form_act.text_alias}{/if}" style="width: 216px;">
                <br /><label>Internal unique code for text definitions</label>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="text_category">{#txtCategory#}</label>
                <select name="text_category" class="form-control pull-right" {if !$canEdit} disabled="disabled" {/if}>
                    {foreach from=$category item=item key=key}
                        <option value="{$item.texte_cat_id}" {if isset($form_act.text_category) && $form_act.text_category==$item.texte_cat_id} selected="selected" {/if}>{$item.texte_cat_name}</option>
                    {/foreach}
                </select>
            </div>
        
            <div class="form-group row col-md-8">
                <label for="text_text">{#txtText#}</label>
                <textarea name="text_text" id="text_text" rows="5" class="mceEditor pull-right">{if isset($form_act.text_text)}{$form_act.text_text}{/if}</textarea>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="text_text">{#txtText#} Mobile</label>
                <textarea name="text_text_mobile" id="text_text_mobile" rows="5" class="mceEditor pull-right">{if isset($form_act.text_text_mobile)}{$form_act.text_text_mobile}{/if}</textarea>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="text_status">{#txtActiv#}</label>
                <input type="checkbox" style="margin-left: 120px" name="text_status" id="text_status" value="1" {if isset($form_act.text_status) && $form_act.text_status!=="0"}checked{/if}> 
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