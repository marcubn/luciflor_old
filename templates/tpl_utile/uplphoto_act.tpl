{include file="admin/inc/popup_top.tpl"}
<script type="text/javascript" src="/js/jquery-pack.js"></script>
<script type="text/javascript" src="/js/jquery.imgareaselect.min.js"></script>
<fieldset>
    <legend>{if $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
    <form class="form-horizontal" role="form" name="form_act" method="post" enctype="multipart/form-data" action="index.php?obj=uplphoto&action=add_upd&owner={$smarty.get.owner}&owner_id={$smarty.get.owner_id}" onSubmit="return formValidate('form_act',1);">
        <input type="hidden" name="act" value="{$form_act.act}">
        <input type="hidden" name="id" value="{$form_act.id}" />
<div class="col-md-6 pull-left">
    
        <div class="form-group col-md-12">
            <label class="col-sm-4">Tip</label>
            
            <label for="foto" class="col-md-2">Foto</label>
            <input type="radio" class="media_type" name="media_type" id="foto" value="image" {if $form_act.media_type=="image"}checked{/if}> 
            
            <label for="video" class="col-md-2">Video</label>
            <input type="radio" class="media_type" name="media_type" id="video" value="video_embed" {if $form_act.media_type=="video_embed"}checked{/if}> 
        </div>
    
        <div class="foto" style="display: none;">
            <div class="form-group col-md-12">
                <label for="file" class="col-sm-4">Foto</label>
                <input type="file" class="col-sm-8" name="file[]" multiple style="display: initial;">
            </div>
            
            {if $form_act.file!=''}
                <div class="form-group col-md-12">
                    <label for="" class="col-sm-2">&nbsp;</label>
                    <a href="{$smarty.const.PHOTOS_UPLOAD_URL}{$form_act.file}" target="_blank" class="link1"><img src="{$smarty.const.IMAGES_URL}admin/utile/view.gif" border="0" align="absmiddle" style="margin-right:5px;">view real size</a>
                </div>
            {/if}
        </div>
    
        <div class="video" style="display: none;">
            <div class="form-group col-md-12">
                <label class="col-sm-4">Video source</label>
            
                <label for="youtube" class="col-md-2">Youtube</label>
                <input type="radio" class="video_src" name="video_src" id="youtube" value="youtube" {if $form_act.video_src=="youtube"}checked{/if}> 
            
                <label for="vimeo" class="col-md-2">Vimeo</label>
                <input type="radio" class="video_src" name="video_src" id="vimeo" value="vimeo" {if $form_act.video_src=="vimeo"}checked{/if}> 
            </div>
            
            <div class="form-group col-md-12">
                <label for="video_code" class="col-sm-4">Video Code</label>
                <input type="text" class="form-control col-sm-8" id="video_code" name="video_code" value="{$form_act.video_code}" style="width: 200px; display: initial;">
            </div>
        </div>
    
        <div class="form-group col-md-12">
            <label for="gallery_id" class="col-sm-4">Galerie</label>
            <input type="text" class="form-control col-sm-8" id="gallery_id" name="gallery_id" value="{$form_act.gallery_name}" style="width: 200px; display: initial;">
        </div>

        <div class="form-group col-md-12">
            <label for="title" class="col-sm-4">Titlu</label>
            <input type="text" class="form-control col-sm-8" id="title" name="title" value="{$form_act.title}" style="width: 200px; display: initial;">
        </div>

        <div class="form-group col-md-12">
            <label for="priority" class="col-sm-4">{#txtPriority#}</label>
            <input type="text" class="form-control col-md-8" id="priority" name="priority" value="{$form_act.priority}" style="width: 50px; display: initial;">
        </div>
                        
        <div class="form-group row col-md-12">
            <label for="def" class="col-md-2">Default</label>
            <input type="checkbox" name="def" id="def" value="1" {if $form_act.def!=="0"}checked{/if}> 
        </div>

        <div class="form-group row col-md-12">
            <label for="active" class="col-md-2">Activ</label>
            <input type="checkbox" name="active" id="active" value="1" {if $form_act.active!=="0"}checked{/if}> 
        </div>

        <div class="form-group col-md-12">
            <label>inapoi la formul de editare</label>
            <input type="checkbox" name="backToEditForm" checked>
            <button type="submit" class="btn btn-primary">Save</button>
            {*<button type="submit" class="btn btn-primary" onclick="document.getElementById('add_new').value=1; document.form_act.submit();">Save and new</button>*}
            {if $form_act.act=="upd"}
                <button type="button" class="btn btn-danger" onClick="window.close();">Anuleaza</button>
            {/if}
        </div>
</div>
    </form>
</fieldset>    
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript">   
    $(document).ready(function() {
       if($("#foto").is(":checked"))
        {
            $('.foto').show();
            $('.video').hide();
        }
        else if($("#video").is(":checked")) {
            $('.video').show();
            $('.foto').hide();
        } 
    });
    
    jQuery("#gallery_id").autocomplete(
        {
        source: "index.php?obj=ajax&action=ajx_gallery_select",
        selectFirst: true,
        minLength: 2
        }
    );
    
    $( ".media_type" ).change(function() {
        if($("#foto").is(":checked"))
        {
            $('.foto').show();
            $('.video').hide();
        }
        else if($("#video").is(":checked")) {
            $('.video').show();
            $('.foto').hide();
        }
    });
    
    $( ".video_src" ).change(function() {
        
    });
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