{include file="admin/inc/top.tpl"}
{include file="admin/inc/mce.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">SEO Manager</a><span class="divider"></span></li>
    <li class="active">{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<div class="col-md-6 pull-left">
    <fieldset>
        <legend>{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
        <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',0);" enctype="multipart/form-data">
            <input type="hidden" name="act" value="{$form_act.act}">
            <input type="hidden" name="{$idName}" value="{$form_act.$idName}">  

            <div class="form-group row col-md-11">
                <label class="pull-left" for="seo_url" style="margin-right: 10px">URL</label>
                <select name="seo_domain" class="form-control pull-left" style="width: 159px;">
                    <option value="{$smarty.const.ROOT_HOST}">{$smarty.const.ROOT_HOST}</option>
                </select>
                <input type="text" class="form-control pull-right" id="seo_url" name="seo_url" value="{$form_act.seo_url}" style="width: 200px;">
            </div>

            <div class="form-group row col-md-8">
                <label for="seo_title">Titlu Pagina</label>
                <input type="text" class="form-control pull-right" id="seo_title" name="seo_title" value="{$form_act.seo_title}" style="width: 200px;">
            </div>

            <div class="form-group row col-md-8">
                <label for="seo_h">H1 Pagina</label>
                <input type="text" class="form-control pull-right" id="seo_h" name="seo_h" value="{$form_act.seo_h}" style="width: 200px;">
            </div>

            <div class="form-group row col-md-12">
                <label for="seo_description">Descriere</label>
                <textarea name="seo_description" id="seo_description" rows="5" class="mceEditor pull-right">{$form_act.seo_description}</textarea>
            </div>

            <div class="form-group row col-md-12">
                <label for="seo_keywords">Keywords</label>
                <textarea name="seo_keywords" id="seo_keywords" rows="5" class="mceEditor pull-right">{$form_act.seo_keywords}</textarea>
            </div>

            <div class="form-group row col-md-6">
                <label for="seo_active">{#txtActiv#}</label>
                <input type="checkbox" style="margin-left: 120px" name="seo_active" id="seo_active" value="1" {if $form_act.seo_active!=="0"}checked{/if}> 
            </div>

            <div class="form-group col-md-12">
                <label>inapoi la formul de editare</label>
                <input type="checkbox" name="backToEditForm" checked>
                <button type="submit" class="btn btn-primary">salveaza</button>
                {if $form_act.act=='upd'}
                    <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'">go to list</button>
                {/if}
            </div>

        </form>
    </fieldset>
</div>
{include file="admin/inc/bottom.tpl"}