{include file="admin/inc/popup_top.tpl"}
<script type="text/javascript" src="/js/jquery-pack.js"></script>
<script type="text/javascript" src="/js/jquery.imgareaselect.min.js"></script>
<fieldset>
    <legend>{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
    <form name="form_act" method="post" enctype="multipart/form-data" action="index.php?obj=uplphoto&action=add_upd&owner={$smarty.get.owner}&owner_id={$smarty.get.owner_id}" onSubmit="return formValidate('form_act',1);">
        <input type="hidden" name="act" value="{$form_act.act}">
        <input type="hidden" name="id" value="{$form_act.id}" />

        <div class="form-group row col-md-7">
            <label for="file" class="col-md-3 pull-left">Foto</label>
            {*<input type="file" class="col-md-3 pull-left" name="file" style="width:230px;">*}
            <input type="file" class="col-md-3 pull-left" name="file[]" style="width:230px;" onChange="getFileSize(this.name, this.name+'_size');" multiple>
            <input type="text" name="file_size" value="0" style="width:55px;text-align:right" readonly onMouseOver="style.cursor='hand';  if(this.value > 0) this.title=Math.round((this.value/1024)*100)/100+' Kbytes\n'+Math.round((this.value/1024/1024)*100)/100+' Mbytes'"> 
            <b>B</b>
        </div>

        <div class="form-group row col-md-7">
            <label for="article_title" class="col-md-5 pull-left">Titlu</label>
            <input type="text" class="form-control pull-left" id="article_title" name="article_title" value="{$form_act.article_title}" style="width: 200px;">
        </div>

        {if $form_act.file!=''}
            <div class="form-group row col-md-7">
                <label for="" class="col-md-5">&nbsp;</label>
                <a href="{$smarty.const.PHOTOS_UPLOAD_URL}{$form_act.file}" target="_blank" class="link1"><img src="{$smarty.const.IMAGES_URL}admin/utile/view.gif" border="0" align="absmiddle" style="margin-right:5px;">view real size</a>
            </div>
        {/if}

        <div class="form-group row col-md-7">
            <label for="priority" class="col-md-5 pull-left">{#txtPriority#}</label>
            <input type="text" class="form-control pull-left" id="priority" name="priority" value="{$form_act.priority}" style="width: 50px;">
        </div>
                        
        <div class="form-group row col-md-8">
            <label for="def" class="col-md-3">Default</label>
            <input type="checkbox" name="def" id="def" value="1" {if $form_act.def!=="0"}checked{/if}> 
        </div>

        <div class="form-group row col-md-8">
            <label for="active" class="col-md-3 pull-left">Activ</label>
            <input type="checkbox" name="active" id="active" value="1" {if $form_act.active!=="0"}checked{/if}> 
        </div>

        <div class="form-group col-md-12">
            <label>inapoi la formul de editare</label>
            <input type="checkbox" name="backToEditForm" checked>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" class="btn btn-primary" onclick="document.getElementById('add_new').value=1; document.form_act.submit();">Save and new</button>
            {if $form_act.act=="upd"}
                <button type="button" class="btn btn-danger" onClick="window.close();">Anuleaza</button>
            {/if}
        </div>
    </form>
</fieldset>
<script type="text/javascript">
    {if $form_act.act!="upd"}
        document.form_act.file.oblig="true";
        document.form_act.file.format="image";
    {/if}
    document.form_act.priority.format="integer";
</script>
{if $smarty.get.reload==1}
    <script type="text/javascript">
        window.opener.location.reload();
    </script>
{/if}

{if $smarty.get.close==1}
    <script type="text/javascript">
        window.opener.location.reload();
        window.close();
    </script>
{/if}
{include file="admin/inc/popup_bottom.tpl"}