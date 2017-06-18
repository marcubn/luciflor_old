{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtPages#}</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-6 pull-left">
    <fieldset>
        <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
        <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
            <input type="hidden" name="act" value="{$form_act.act}">
            <input type="hidden" name="{$idName}" value="{$form_act.$idName}">  
            
                <div class="panel panel-default">
                  <div class="panel-heading">General Settings</div>
                  <div class="panel-body">
            <div class="form-group row col-md-8">
                <label for="page_lang">{#txtLang#}</label>
                {if isset($smarty.get.lang)}
                    {assign var="lang" value=$smarty.get.lang}
                {else}
                    {assign var="lang" value=1}
                {/if}
                <select name="page_lang" onchange="document.location='index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$form_act.$idName}&act={$form_act.act}&lang='+this.value;" class="form-control pull-right">
                    {foreach from=$SITE_LANGS item=item key=key}
                        <option value="{$item->language_id}" {if isset($lang) && $lang==$item->language_id} selected="selected" {/if}>{$item->language_name}</option>
                    {/foreach}
                </select>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="page_category">{#txtCategory#}</label>
                <select name="page_category" class="form-control pull-right">
                    <option value="">{#txtSelectCat#}</option>
                    {foreach from=$categories item=item key=key}
                        <option value="{$item.pages_cat_id}" {if $form_act.page_category==$item.pages_cat_id} selected="selected" {/if}>{$item.pages_cat_name}</option>
                    {/foreach}
                </select>
            </div>
            
            <div class="form-group row col-md-7">
                <label for="page_template">{#txtTpl#}</label>
                <input type="text" class="form-control pull-right" id="page_template" name="page_template" value="{if isset($form_act.page_template)}{$form_act.page_template}{/if}" style="width: 200px;">
            </div>
            
            <div class="form-group row col-md-7">
                <label for="page_titlu">{#txtTitle#}</label>
                <input type="text" class="form-control pull-right" id="page_titlu" name="page_titlu" value="{if isset($form_act.page_titlu)}{$form_act.page_titlu}{/if}" style="width: 200px;">
            </div>
            
            <div class="form-group row col-md-7">
                <label for="page_titlu">Subtitle</label>
                <input type="text" class="form-control pull-right" id="page_subtitlu" name="page_subtitlu" value="{if isset($form_act.page_subtitlu)}{$form_act.page_subtitlu}{/if}" style="width: 200px;">
            </div>
            
            
            <div class="form-group row col-md-7">
                <label for="page_alias">{#txtAlias#}</label>
                <input type="text" class="form-control pull-right" id="page_alias" name="page_alias" value="{if isset($form_act.page_alias)}{$form_act.page_alias}{/if}" style="width: 200px;">
            </div>
            
            <div class="form-group row col-md-7">
                <label for="page_url">Url</label>
                <input type="text" class="form-control pull-right" id="page_url" name="page_url" value="{if isset($form_act.page_url)}{$form_act.page_url}{/if}" style="width: 200px;">
            </div>
            
            <div class="form-group row col-md-8">
                <label for="page_home_text">{#txtTextHome#}</label>
                ...<img style="cursor:pointer;" src="/img/admin/utile/edit.gif" onclick="$('#editor').toggle( 'slow' );" />
                <div id="editor" style="display:none;">
                    <textarea name="page_home_text" id="page_home_text" rows="5" class="mceEditor pull-right">{$form_act.page_home_text}</textarea>
                </div>
            </div>
            
            <div class="form-group row col-md-8">
                <label for="page_text">{#txtText#}</label>
                <textarea name="page_text" id="page_text" rows="5" class="mceEditor pull-right">{if isset($form_act.page_text)}{$form_act.page_text}{/if}</textarea>
            </div>
            
                	</div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">Design</div>
                  <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td>Icon color</td>
                            <td>
                            	<select name="params[icon_color]" class="form-control" >
                            		<option value="mainColor" {if isset($params.icon_color) && $params.icon_color=="mainColor"}selected{/if}>Main color (blue)</option>
                            		<option value="green" {if isset($params.icon_color) && $params.icon_color=="green"}selected{/if}>Greeen</option>
                            		<option value="orange" {if isset($params.icon_color) && $params.icon_color=="orange"}selected{/if}>Orange</option>
                            		<option value="greyA" {if isset($params.icon_color) && $params.icon_color=="greyA"}selected{/if}>Grey</option>
                            		<option value="livingColor" {if isset($params.icon_color) && $params.icon_color=="livingColor"}selected{/if}>Living Color (orange)</option>
                            	</select>
                            </td>
                        </tr>
                        <tr>
                            <td>Icon class small</td>
                            <td>
                            	<select name="params[icon_class]" class="form-control" >
                                    {foreach from=$icon_small_classes key=key item=item}
                                    <option value="{$key}" {if isset($params.icon_class) && $params.icon_class==$key}selected{/if}>{$item}</option>
                                    {/foreach}
                            	</select>
                            </td>
                        </tr>
                        <tr>
                            <td>Icon class large</td>
                            <td>
                            	<select name="params[icon_class_large]" class="form-control" >
                                {foreach from=$icon_large_classes key=key item=item}
                                    <option value="{$key}" {if isset($params.icon_class_large) && $params.icon_class_large==$key}selected{/if}>{$item}</option>
                                {/foreach}
                            	</select>
                            </td>
                        </tr>
                    </table>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading">SEO Settings</div>
                  <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label">Meta Title</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="seo[meta_title]" value="{if isset($seo.meta_title)}{$seo.meta_title}{/if}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon_class">Meta keywords</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="seo[meta_keywords]" value="{if isset($seo.meta_keywords)}{$seo.meta_keywords}{/if}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon_class">Meta description</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="seo[meta_description]" value="{if isset($seo.meta_description)}{$seo.meta_description}{/if}">
                            </div>
                        </div>
                  </div>
                </div>            
            
            <div class="form-group row col-md-8">
                <label for="page_status">{#txtActiv#}</label>
                <input type="checkbox" style="margin-left: 120px" name="page_status" id="page_status" value="1" {if isset($form_act.page_status) && $form_act.page_status!=="0"}checked{/if}> 
            </div>
            
            <div class="form-group row col-md-8">
			    <label for="{$priorityName}">Ordine</label>
			    <button onclick="document.form_act.{$priorityName}.value= '{$minOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-up"></span></button>
	          	<button onclick="document.form_act.{$priorityName}.value= '{$maxOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-down"></span></button>
	          	<input type="text" class="form-control pull-right" id="{$priorityName}" name="{$priorityName}" value="{if isset($form_act.$priorityName)}{$form_act.$priorityName}{else}1{/if}" style="width: 50px;">
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

{if isset($form_act.page_titlu)}
    {include file="admin/inc/uplinc.tpl" title=$form_act.page_titlu}
{else}
    {include file="admin/inc/uplinc.tpl"}
{/if}
{include file="admin/inc/bottom.tpl"}