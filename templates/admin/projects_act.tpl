{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Projects</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-6 pull-left">
    <fieldset>
        <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
        <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
            <input type="hidden" name="act" value="{$form_act.act}">
            <input type="hidden" name="{$idName}" value="{$form_act.$idName}">  

            <!-- GENERAL SETTINGS ABOUT PROJECT -->
                <div class="panel panel-default">
                  <div class="panel-heading">General Settings</div>
                  <div class="panel-body">
                    {*<div class="form-group row col-md-8">
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
                    </div>*}
                      {*$form_fields|pr*}
                    <div class="form-group row col-md-8">
                        <label for="category_id">{#txtCategory#}</label>
                        <select name="category_id" class="form-control pull-right">
                            <option value="">{#txtSelectCat#}</option>
                            {foreach from=$form_fields.0->options item=item key=key}
                                <option value="{$item->value}" {if $form_act.category_id==$item->value} selected="selected" {/if}>{$item->name}</option>
                            {/foreach}
                        </select>
                    </div>
            
                    <div class="form-group row col-md-7">
                        <label for="title">Title</label>
                        <input type="text" class="form-control pull-right" id="title" name="title" value="{if isset($form_act.title)}{$form_act.title}{/if}" style="width: 200px;">
                    </div>
            
                    <div class="form-group row col-md-8">
                        <label for="text">{#txtText#}</label>
                        <textarea name="text" id="text" rows="5" class="mceEditor pull-right">{if isset($form_act.text)}{$form_act.text}{/if}</textarea>
                    </div>

                    <div class="form-group row col-md-8">
                        <label for="text">Date</label>
                        <a href="javascript:applyStyle('date', 'value', '', 0); void(false);" class="pull-right">
                          <img src="{$smarty.const.ROOT_HOST}/img/admin/utile/icon-delete.png" hspace="0" border="0" align="middle" alt="" />
                        </a>
                        <input type="text" style="width: 180px;" name="date" id="date" class="datepicker form-control pull-right" value="{if isset($form_act.date)}{$form_act.date}{/if}"
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
                      </div>

                      <div class="form-group row col-md-7">
                          <label for="client">Client</label>
                          <input type="text" class="form-control pull-right" id="client" name="client" value="{if isset($form_act.client)}{$form_act.client}{/if}" style="width: 200px;">
                      </div>

                      <div class="form-group row col-md-7">
                          <label for="location">Locatie</label>
                          <input type="text" class="form-control pull-right" id="location" name="location" value="{if isset($form_act.location)}{$form_act.location}{/if}" style="width: 200px;">
                      </div>

                      <div class="form-group row col-md-7">
                          <label for="status_proiect">Status</label>
                          <input type="text" class="form-control pull-right" id="status_proiect" name="status_proiect" value="{if isset($form_act.status_proiect)}{$form_act.status_proiect}{/if}" style="width: 200px;">
                      </div>

                      <div class="form-group row col-md-7">
                          <label for="status">Activ</label>
                          <input type="checkbox" style="margin-left: 120px" name="status" id="status" value="1" {if $form_act.status!=="0"}checked{/if}>
                      </div>

                      <div class="form-group row col-md-7">
                          <label for="status">Picture</label>
                          <div class="controls">
                              {include file="tpl_utile/tpl_upload_act.tpl" file_name="photos" url="{$smarty.const.UPLOAD_URL}photos/"}
                          </div>
                      </div>

                	</div>
                </div>
            <!-- -->

            {*<div class="form-group row col-md-8">
			    <label for="{$priorityName}">Ordine</label>
			    <button onclick="document.form_act.{$priorityName}.value= '{$minOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-up"></span></button>
	          	<button onclick="document.form_act.{$priorityName}.value= '{$maxOrder}'; void(false);" type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-down"></span></button>
	          	<input type="text" class="form-control pull-right" id="{$priorityName}" name="{$priorityName}" value="{if isset($form_act.$priorityName)}{$form_act.$priorityName}{else}1{/if}" style="width: 50px;">
		  	</div>	*}

            <div class="form-group col-md-12">
                <label>{#txtBackToEditForm#}</label>
                <input type="checkbox" name="backToEditForm" checked>
                <button type="submit" class="btn btn-primary">{#bSave#}</button>
                <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'">{#txtGoToList#}</button>
            </div>
        </form>
    </fieldset>
</div>

{include file="admin/inc/uplinc.tpl"}

<div class="col-md-12 pull-left">
    <!-- ROOMS INCLUDED IN PROJECT -->
    <div class="panel panel-default">
        <div class="panel-heading">ROOMS</div>
        <div class="panel-body" style="overflow-y: visible">
            <iframe src='/admin/index.php?obj=subprojects&action=page_list&parent_id={$smarty.get.$idName}' width='100%' frameborder='none' style="overflow-y: visible; overflow-x: hidden!important;" height='500px' overflow-x='hidden' overflow-y='scroll' marginwidth='0' marginheight='0' scrolling="yes"/>

        </div>
    </div>
    <!-- -->
</div>
{*if isset($form_act.page_titlu)}
    {include file="admin/inc/uplinc.tpl" title=$form_act.page_titlu}
{else}
    {include file="admin/inc/uplinc.tpl"}
{/if*}

{include file="admin/inc/bottom.tpl"}