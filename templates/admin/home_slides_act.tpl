{include file="admin/inc/top.tpl"}
<script type="text/javascript" src="/js/jquery/timepicker_addon/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery/timepicker_addon/jquery-ui-timepicker-addon.css" />

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{#txtMainTeasers#}</a><span class="divider"></span></li>
    <li class="active">{if $form_act.act=='upd' && $form_act.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
{include file="admin/inc/mce.tpl"}

<form class="form-horizontal" name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onSubmit="return formValidate('form_act',1);" enctype="multipart/form-data">
    <input type="hidden" name="act" value="{$form_act.act}" />
    <input type="hidden" name="{$idName}" value="{$form_act.$idName}" />
    <fieldset>
        <legend>{if $form_act.act=='upd'}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>

    <div class="row">
        <div class="col-md-6">
        <div class="well">
            <h4>{#txtInfo#}</h4>
            <div class="control-group">
                 <label class="control-label">{#txtInternalName#}</label>
                 <div class="controls">
                     <input type="text" name="name" value="{$form_act.name}" class="form-control">
                 </div>
            </div>
            <div class="control-group">
                <label class="control-label">{#txtActiv#}</label>
                <div class="controls">
                    <input type="checkbox" name="status" value="1" {if $form_act.status!=="0"}checked{/if} class="input-mini" >
                </div>
            </div>
            <div class="control-group">
                 <label class="control-label">{#txtPublicInterval#}:</label>
                 <div class="controls">
                     <input type="text" id="publish_date" name="publish_date" value="{$form_act.publish_date}" class="input-large">
                     &raquo;
                      <input type="text" id="unpublish_date" name="unpublish_date" value="{$form_act.unpublish_date}" class="input-large">
                      <div class="muted info-box">{#txtLeaveEmpty#}</div>
                 </div>
            </div>
            <div class="control-group">
                <label class="control-label">{#txtDisplayTime#}</label>
                <div class="controls">
                    <div class="input-append">
                        <input type="text" name="display_time" value="{$form_act.display_time}" class="form-control appendedInputButtons" style="width: 100px;">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">{#txtOrdering#}</label>
                <div class="controls">
                    <div class="input-append">
                    <input type="text" name="ordering" value="{$form_act.ordering}" class="form-control appendedInputButtons" style="width: 50px;">
                        <a href="javascript: document.form_act.{$priorityName}.value= '{$minOrder}'; void(false);" title="Set First" class="inline btn"><i class="icon-arrow-up"></i></a>
                        <a href="javascript: document.form_act.{$priorityName}.value= '{$maxOrder}'; void(false);" title="Set Last" class="inline btn"><i class="icon-arrow-down"></i></a>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">{#txtLastModified#}:</label>
                <div class="controls">
                    {if $form_act.modified_by}
                    {$form_act.modified_date} ( {$form_act.modified_by_usr} )
                    {/if}
                </div>
            </div>
            <div class="control-group">
           
                <div class="controls">
                    <label class="checkbox">
                        {#txtBackToEditForm#}
                        <input type="checkbox" name="backToEditForm" class="bd0 backtoeditform">
                    </label>
                </div>
            </div>
            <div class="controls">
                <input type="submit" value="Save" class="btn btn-primary" />
                {if $form_act.act=='upd'}<input type="button" value="Cancel" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'" {$buttonStyle} />{/if}
            </div>  
        </div>
        </div>
        <div class="col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#romanian" data-toggle="tab"><i class="glyphicon glyphicon-wrench"></i> {#txtRomanian#}</a></li>
              	<li><a href="#english" data-toggle="tab"><i class="glyphicon glyphicon-wrench"></i> {#txtEnglish#}</a></li>
            </ul>            
            <div class="tab-content  well">
                <div class="tab-pane active" id="romanian" >
                    <h4>{#txtRomanian#}</h4>
                    <div class="control-group" id="slider_link">
                         <label class="control-label">{#txtLink#}</label>
                         <div class="controls">
                             <input type="text" name="link_ro" value="{$form_act.link_ro}" class="form-control">
                         </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">{#txtSlider#}</label>
                        <div class="controls">
                            {include file="tpl_utile/tpl_upload_act.tpl" file_name="foto_ro" url=$smarty.const.UPLOAD_URL|cat:"home_slides"}
                            <br />
                            <div class="muted info-box">( 650px x 360px )</div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">{#txtPictureAltText#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="picture_alt_text_ro" value="{$form_act.picture_alt_text_ro}" class="form-control appendedInputButtons" style="width: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="control-group" id="slider_descriere">
                        <label class="control-label">Text HTML</label>
                        <div class="controls">
                            <textarea rows="3" class="form-control" name="text_ro" >{$form_act.text_ro}</textarea>
                            <div class="muted info-box">
                               ( 1-160 {#txtCharacters#} )
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="categories">{#txtPositionTextBox#}</label>
                        <select name="params_ro[txt_position]" style="width:200px;" class="form-control">
                            <option value="left" {if $params_ro.txt_position=='left'}selected="selected"{/if}>Stanga</option>
                            <option value="right" {if $params_ro.txt_position=='right'}selected="selected"{/if}>Dreapta</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">{#txtHeadline#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_ro[headline]" value="{$params_ro.headline}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-40 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Subtitlu</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_ro[subheadline]" value="{$params_ro.subheadline}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-40 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="categories">{#txtBtnSelect#}</label>
                        <select name="params_ro[btn_selector]" style="width:200px;" class="form-control">
                            <option value="no_btn" {if $params_ro.btn_selector=='no_btn'}selected="selected"{/if}>no button</option>
                            <option value="mainColor" {if $params_ro.btn_selector=='mainColor'}selected="selected"{/if}>blue</option>
                            <option value="orange" {if $params_ro.btn_selector=='orange'}selected="selected"{/if}>orange</option>
                            <option value="green" {if $params_ro.btn_selector=='green'}selected="selected"{/if}>green</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">{#txtBtnText#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_ro[btn_text]" value="{$params_ro.btn_text}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-30 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="english" >
                    <h4>{#txtEnglish#}</h4>
                    <div class="control-group" id="slider_link">
                         <label class="control-label">{#txtLink#}</label>
                         <div class="controls">
                             <input type="text" name="link_en" value="{$form_act.link_en}" class="form-control">
                         </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">{#txtSlider#}</label>
                        <div class="controls">
                            {include file="tpl_utile/tpl_upload_act.tpl" file_name="foto_en" url=$smarty.const.UPLOAD_URL|cat:"home_slides"}
                            <div class="muted info-box">( 650px x 360px )</div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">{#txtPictureAltText#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="picture_alt_text_en" value="{$form_act.picture_alt_text_en}" class="form-control appendedInputButtons" style="width: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="control-group" id="slider_descriere">
                        <label class="control-label">{#txtCopyText#}</label>
                        <div class="controls">
                            <textarea rows="3" class="form-control" name="text_en" >{$form_act.text_en}</textarea>
                            <div class="muted info-box">
                               ( 1-160 {#txtCharacters#} )
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="categories">{#txtPositionTextBox#}</label>
                        <select name="params_en[txt_position]" style="width:200px;" class="form-control">
                            <option value="left_top" {if $params_en.txt_position=='left_top'}selected="selected"{/if}>Left top</option>
                            <option value="left_bottom" {if $params_en.txt_position=='left_bottom'}selected="selected"{/if}>Left bottom</option>
                            <option value="centred_top" {if $params_en.txt_position=='centred_top'}selected="selected"{/if}>Centred top</option>
                            <option value="centred_bottom" {if $params_en.txt_position=='centred_bottom'}selected="selected"{/if}>Centred bottom</option>
                            <option value="right_top" {if $params_en.txt_position=='right_top'}selected="selected"{/if}>Right top</option>
                            <option value="right_bottom" {if $params_en.txt_position=='right_bottom'}selected="selected"{/if}>Right bottom</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">{#txtHeadline#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_en[headline]" value="{$params_en.headline}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-20 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">Subtitlu</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_en[subheadline]" value="{$params_en.subheadline}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-40 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="categories">{#txtBtnSelect#}</label>
                        <select name="params_en[btn_selector]" style="width:200px;" class="form-control">
                            <option value="no_btn" {if $params_en.btn_selector=='no_btn'}selected="selected"{/if}>no button</option>
                            <option value="mainColor" {if $params_en.btn_selector=='mainColor'}selected="selected"{/if}>blue</option>
                            <option value="orange" {if $params_en.btn_selector=='orange'}selected="selected"{/if}>orange</option>
                            <option value="green" {if $params_en.btn_selector=='green'}selected="selected"{/if}>green</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">{#txtBtnText#}</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="params_en[btn_text]" value="{$params_en.btn_text}" class="form-control appendedInputButtons" style="width: 200px;">
                                <div class="muted info-box">( 1-30 {#txtCharacters#} )</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group">
           
                <div class="controls">
                    <label class="checkbox">
                        {#txtBackToEditForm#}
                        <input type="checkbox" name="backToEditForm" class="bd0 backtoeditform">
                    </label>
                </div>
            </div>
            <div class="controls">
                <input type="submit" value="Save" class="btn btn-primary" />
                {if $form_act.act=='upd'}<input type="button" value="Cancel" onclick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'" {$buttonStyle} />{/if}
            </div>  
        </div>
                
                
    </div>
    </fieldset>
</form> 

<!-- [+]Editable Region -->
{literal}
<script type="text/javascript">
            
        $('#publish_date').datetimepicker({
                timeFormat: "hh:mm:ss",
                dateFormat:"yy-mm-dd"
        });
            
        $('#unpublish_date').datetimepicker({
                timeFormat: "hh:mm:ss",
                dateFormat:"yy-mm-dd"
        });            
</script>
{/literal}
<!-- [-]Editable Region -->
{include file="admin/inc/bottom.tpl"}