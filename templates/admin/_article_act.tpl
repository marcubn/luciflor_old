{include file="admin/inc/top.tpl"}
{include file="tpl_js/calendar.js.tpl"}
{include file="admin/inc/mce.tpl"}

<script type="text/javascript">
    $(document).ready(function() {
        if(window.location.href.indexOf("#") > -1)
        {
            var tab = $(location).attr('href').split('#');
            $('#myTab a[href="#'+tab[1]+'"]').tab('show');
        }
    });
</script>
<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Articles</a><span class="divider"></span></li>
    <li class="active">{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->

<!-- Nav tabs -->
<ul id="myTab" class="nav nav-tabs">
  <li class="active"><a href="#info_articol" data-toggle="tab">Info articol</a></li>
  {if $form_act.act=='upd' || $form_act.act=='adv'}
      <li><a href="#pages_articol" data-toggle="tab">Subcapitole</a></li>
      <li><a href="#articles_pics" data-toggle="tab">Imagini articol</a></li>
      {*}<li><a href="#articles_comments" data-toggle="tab">Comentarii articol</a></li>{*}
  {/if}
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane fade in active" id="info_articol">
        <div class="col-md-6 pull-left">
            <fieldset>
                <legend>{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
                <form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act', 1);">
                    <input type="hidden" name="act" value="{$form_act.act}">
                    <input type="hidden" name="{$idName}" value="{$form_act.$idName}">
                    <input type="hidden" name="article_keywords" value="{$form_act.article_keywords}">
                    <input type="hidden" name="article_description" value="{$form_act.article_description}">
                    <input type="hidden" name="reason" value="">

                    <div class="form-group row col-md-9">
                        <label for="article_title" class="col-md-5">Title</label>
                        <input type="text" class="form-control pull-right" id="article_title" name="article_title" value="{$form_act.article_title}" style="width: 200px;">
                    </div>

                    <div class="form-group row col-md-9">
                        <label for="categories" class="col-md-5">Category</label>
                        <select name="categories" style="width:200px;" class="form-control">
                            <option value="">alege categorie</option>
                            {assign var=section value=""}
                            {foreach from=$catList item=item key=key}
                                {if $section!=$item.section_name}
                                    {if $key>0}</optgroup>{/if}<optgroup label="{$item.section_name}">
                                    {assign var=section value=$item.section_name}
                                {/if}
                                <option value="{$item.category_id}" {if $item.selected=='1'}selected="selected"{/if}>{$item.space}{$item.category_name}</option>
                            {/foreach}
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group row col-md-12">
                        <label for="article_summary">Summary</label>
                        <textarea name="article_summary" id="article_summary" rows="5" class="mceEditor pull-right">{$form_act.article_summary}</textarea>
                    </div>

                    <div class="form-group row col-md-12">
                        <label for="article_videohtml">Embed Code</label>
                        <textarea name="article_videohtml" id="article_videohtml" rows="5" class="mceEditor pull-right">{$form_act.article_videohtml}</textarea>
                    </div>

                    <div class="form-group row col-md-12">
                        <label class="col-md-4" for="article_date">Date active </label>
                        {include file="tpl_utile/date_elem.tpl" dateElemName="article_date" dateElemValue=$form_act.article_date|default:$today}
                    </div>

                    <div class="form-group row col-md-8">
                        <label for="article_status" class="col-md-3">Activ</label>
                        <input type="checkbox" style="margin-left: 120px" name="article_status" id="article_status" value="1" {if $form_act.article_status!=="0"}checked{/if}> 
                    </div>

                    <div class="form-group col-md-12">
                        <label>inapoi la formul de editare</label>
                        <input type="checkbox" name="backToEditForm" checked>
                        <button type="submit" class="btn btn-primary">salveaza</button>
                        {if $form_act.act=="upd"}
                            <input type="button" value="preview" onClick="popup('index.php?obj={$smarty.get.obj}&action=preview&{$idName}={$form_act.$idName}', 600, 600, '', 'yes');" {$buttonStyle}>
                        {/if}
                        <button type="button" class="btn btn-danger" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'">go to list</button>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>

    <div class="tab-pane fade in" id="pages_articol">
        <iframe src="index.php?obj=article&action=pages&article_id={$form_act.$idName}" width="100%" height="600" frameborder="0" scrolling="auto" style='min-width:500px;'></iframe>
    </div>

    <div class="tab-pane fade in" id="articles_pics">
        <iframe width="100%" height="500" id="uplphotos" name="uplphotos" src="index.php?obj=uplphoto&action=page_list&owner={$tableName}&owner_id={$form_act.$idName}&noColumns=5&noRecPage=5&title={$form_act.article_title}" width="100%" frameborder="0" scrolling="auto" style='min-width:350px;'></iframe>
    </div>

    {*}<div class="tab-pane fade in" id="articles_comments">
        <iframe width="100%" height="500" id="comments" name="comments" src="index.php?obj=comment&action=page_list&item_table={$tableName}&item_id={$form_act.$idName}&noColumns=5&noRecPage=5&title={$form_act.article_title}" width="100%" frameborder="0" scrolling="auto" style='min-width:350px;'></iframe>
    </div>{*}
</div>
{include file="admin/inc/bottom.tpl"}