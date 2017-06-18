{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Slider</a><span class="divider"></span></li>
    <li class="active">{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-7 pull-left">
	<fieldset>
		<legend>{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
		<form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
			<input type="hidden" name="act" value="{$form_act.act}">
			<input type="hidden" name="{$idName}" value="{$form_act.$idName}">	

            <div class="form-group row col-md-7">
                <label for="lang">Limba</label>
                {assign var="lang" value=$smarty.get.lang|default:"ro"}
                <select name="lang" id="lang" class="form-control pull-right" style="width: 200px;" onchange="document.location='index.php?obj={$smarty.get.obj}&action=page_act&{$idName}={$form_act.$idName}&act={$form_act.act}&lang='+this.value;">
                    {foreach from=$SITE_LANGS item=item key=key}
                        <option value="{$item->language_id}" {if $lang==$item->language_id} selected="selected" {/if}>{$item->language_name}</option>
                    {/foreach}
                </select>
            </div>
            
			<div class="form-group row col-md-7">
			    <label for="link">Link</label>
			    <input type="text" class="form-control pull-right" id="link" name="link" value="{$form_act.link}" style="width: 200px;">
		  	</div>

			<div class="form-group row col-md-7">
			    <label for="text_buton">Text buton</label>
			    <input type="text" class="form-control pull-right" id="text_buton" name="text_buton" value="{$form_act.text_buton}" style="width: 200px;">
		  	</div>

			<div class="form-group row col-md-7">
			    <label for="culoare">Culoare scris</label>
			    <input type="text" class="form-control pull-right" id="culoare" name="culoare" value="{$form_act.culoare}" style="width: 200px;">
		  	</div>

		  	<div class="form-group row col-md-12">
			    <label for="text">Text</label>
			    <textarea name="text" id="text" rows="5" class="mceEditor pull-right">{$form_act.text}</textarea>
		  	</div>

		  	<div class="form-group row col-md-12">
			    <label for="text">Home banner</label>
			    {include file="tpl_utile/tpl_upload_act.tpl" file_name="banner" url=$smarty.const.UPLOAD_URL|cat:"banners" location="banner"}
		  	</div>

		  	<div class="form-group row col-md-8">
			    <label for="afiseaza_detalii">Afiseaza text pe banner</label>
				<input type="checkbox" style="margin-left: 120px" name="afiseaza_detalii" id="afiseaza_detalii" value="1" {if $form_act.afiseaza_detalii!=="0"}checked{/if}> 
		  	</div>

		  	<div class="form-group row col-md-6">
			    <label for="{$priorityName}">Ordine</label>
			    <button onclick="document.form_act.{$priorityName}.value= '{$minOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-up"></span></button>
	          	<button onclick="document.form_act.{$priorityName}.value= '{$maxOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-down"></span></button>
	          	<input type="text" class="form-control pull-right" id="{$priorityName}" name="{$priorityName}" value="{$form_act.$priorityName|default:$maxOrder}" style="width: 50px;">
		  	</div>

		  	<div class="form-group row col-md-8">
			    <label for="status">Status</label>
				<input type="checkbox" style="margin-left: 120px" name="status" id="status" value="1" {if $form_act.status!=="0"}checked{/if}> 
		  	</div>

		  	<div class="form-group col-md-12">
			  	<label>inapoi la formul de editare</label>
			    <input type="checkbox" name="backToEditForm" checked>
				<button type="submit" class="btn btn-primary">salveaza</button>
				<button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'">go to list</button>
		  	</div>
		</form>
	</fieldset>
</div>
{include file="admin/inc/bottom.tpl"}