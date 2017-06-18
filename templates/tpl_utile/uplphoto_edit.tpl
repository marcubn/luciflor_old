{include file="admin/inc/popup_top.tpl"}
<script type="text/javascript" src="/js/jquery-pack.js"></script>
<script type="text/javascript" src="/js/jquery.imgareaselect.min.js"></script>
<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center"> 
    <tr> 		
            <td>
                {$thumb = $smarty.const.PHOTOS_UPLOAD_DIR|cat:'thumb_'|cat:$form_act.file}
                {if file_exists($thumb)}
                    Thumb: <img src="{$smarty.const.PHOTOS_UPLOAD_URL}thumb_{$form_act.file}"/>
                {/if}

                {if $form_act.file!=''}
                        <div align="center">
                            <table>
                                <tr>
                                    <td>
                                        <img src="{$smarty.const.PHOTOS_UPLOAD_URL}{$form_act.file}?time={$smarty.now}" style="margin-right: 10px; border:1px solid #000FFA;" id="thumbnail" alt="Create Thumbnail" />
                                    </td>
                                    <td style="width:130px;">
                                        <div style="border:1px #e5e5e5 solid;  overflow:hidden; width:100px; height:100px;">
                                            <img id="thumbnail_preview" src="{$smarty.const.PHOTOS_UPLOAD_URL}{$form_act.file}?time={$smarty.now}" alt="Thumbnail Preview" class="no_max_width"/>
                                        </div>
                                        <br style="clear:both;"/>
                                        <table>
                                            <tr>
                                                <td>Marime selectie:</td>
                                                <td><span id="selection_size">Faceti o selectie!</span></td>
                                            </tr>
                                        </table>
                                        <form name="thumbnail" action="index.php?obj=uplphoto&action=create_thumbnail" method="post">
                                            <input type="hidden" name="id" value="{$form_act.id}" />
                                            <input type="hidden" name="poza" value="{$form_act.file}" />
                                            <input type="hidden" name="x1" value="" id="x1" />
                                            <input type="hidden" name="y1" value="" id="y1" />
                                            <input type="hidden" name="x2" value="" id="x2" />
                                            <input type="hidden" name="y2" value="" id="y2" />
                                            <input type="hidden" name="w" value="" id="w" />
                                            <input type="hidden" name="h" value="" id="h" />
                                            <input type="submit" name="upload_thumbnail" disabled value="Salveaza" class="btn btn-primary"  id="save_thumb" />
                                            <input type="submit" name="restore_original" value="Restore" class="btn btn-primary"  id="restore_original" />
                                        </form>
                                    </td>
                                </tr>
                                    
                            </table>
                        </div>
                {/if}
                
                {if $smarty.get.reload==1 || $reload==1}

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
                
		</td>
	</tr>
</table>

{if strlen($large_photo_exists)>0}

	<script type="text/javascript">
            
	function preview(img, selection) { 
        
            var scaleX = 100 / selection.width; 
            var scaleY = 100 / selection.height;
            

            $('#thumbnail_preview').css({ 
                    width: Math.round(scaleX * {$current_large_image_width}) + 'px', 
                    height: Math.round(scaleY * {$current_large_image_height}) + 'px',
                    marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
                    marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
            });
            $('#x1').val(selection.x1);
            $('#y1').val(selection.y1);
            $('#x2').val(selection.x2);
            $('#y2').val(selection.y2);
            $('#w').val(selection.width);
            $('#h').val(selection.height);
            $('#selection_size').html( "W:"+selection.width+", H:"+selection.height );
            $('#save_thumb').attr("disabled", "");
            $('#save_thumb').removeClass("disabled");
            
	} 

	$(document).ready(function () { 
            $('#save_thumb').click(function() {
                    var x1 = $('#x1').val();
                    var y1 = $('#y1').val();
                    var x2 = $('#x2').val();
                    var y2 = $('#y2').val();
                    var w = $('#w').val();
                    var h = $('#h').val();
                    if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
                        alert("You must make a selection first");
                        return false;
                    }else{
                        return true;
                    }
            });
	}); 

	$(window).load(function () { 
            $('#thumbnail').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview }); 
	});

	</script>

{/if}
{include file="admin/inc/popup_bottom.tpl"}