<div class="gallery_wrap">
    <div id="gallery_preview">
    	<img id="id_waid" src="'.ROOT_HOST.'/js/gallery/simple_gallery/ajax-loader.gif" style="display:none; margin-top:50px; margin-left:50px;" />
    	<a class="modal" id="preview_pic_big" href="'.$img_processed_big.'"><img id="preview_pic" src="'.$img_processed.'" /></a>
    </div>
    <div id="gallery_thumblist">
        {foreach from=$images item=image key=k}
            <div class="img_thumb">
            <img src="{$image}" onclick="picSelect(this);" border="0" width="100" />
            </div>
        {/foreach}
    </div>
    <div style="clear:both;">&nbsp;</div>
</div>