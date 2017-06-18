<div class="col-md-6 pull-right">
	{if $form_act.act=="upd"}
			<iframe width="100%" height="700" id="uplphotos" name="uplphotos" src="index.php?obj=uplphoto&action=page_list&owner={$tableName}&owner_id={$form_act.$idName}&noRecPage=8&noColumns=4&title={$title}" frameborder="0" scrolling="no" style="margin:0px; padding:0px"></iframe>
	{/if}
</div>