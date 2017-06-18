{assign var=images_paging value=$smarty.const.IMAGES_URL|@cat:"admin/paging/"}

{if !$nameElemPaging}{assign var=nameElemPaging value="no_pag"}{/if}

{assign var=noRowsResult value=$pagingVariables.noRowsResult}
{assign var=noRowsDisplayed value=$pagingVariables.noRowsDisplayed}
{assign var=pgNo value=$pagingVariables.pgNo}
{assign var=pgAction value=$pagingVariables.action}

{if $noRowsResult==0}
	<span>
		<span>{$noRowsResult} {#pgResults#} </span>
		<span><i class="glyphicon glyphicon-chevron-left"></i></span>
		<span>0 / 0</span>
		<span><i class="glyphicon glyphicon-chevron-right"></i></span>
	</span>
{else}
{math equation="ceil(x/y)" assign=noPages x=$noRowsResult y=$noRowsDisplayed}
<script language="javascript">
	var noPages = parseInt('{$noPages}');
	var invalidNoPage = '{#pgInvalidNoPage#}';
	var pgUrl = 'index.php?obj={$smarty.get.obj}&action={$pgAction}&pgNo=';
	var pgOptions = '{$paging_options}';
</script>

<form class="form-inline pull-right" role="form" onsubmit="return false;" style="min-width: 200px;">
  <div class="form-group">
    <label>{$noRowsResult} {#pgResults#}</label>
	    <label>{if $pgNo==1}<i class="glyphicon glyphicon-chevron-left"></i>{else}<a href="index.php?obj={$smarty.get.obj}&action={$pgAction}&pgNo={math equation="x-1" x=$pgNo}{$paging_options}" title="{#pgPreviousPage#}" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-backward"></i> </a>{/if}</label>
	    <label>page {$pgNo} / {$noPages}</label>
	    <label>
	    	{if $pgNo==$noPages}
	            <i class="glyphicon glyphicon-chevron-right"></i>
	        {else}
	            <a href="index.php?obj={$smarty.get.obj}&action={$pgAction}&pgNo={math equation="x+1" x=$pgNo}{$paging_options}" title="{#pgNextPage#}" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-forward"></i>&nbsp;</a>
	        {/if}
	    </label>
	    <label>{#pgGoTo#}</label>
  </div>
  <div class="form-group">
    <input type="text" class="form-control input-sm" name="{$nameElemPaging}" id="{$nameElemPaging}" onKeyPress="
                        if(event.keyCode==13) 
                            if(!isFinite(this.value) || parseInt(this.value)!=this.value || this.value=='' || this.value>noPages || this.value<=0)
                                alert(invalidNoPage);
                            else 
                                window.location=pgUrl+this.value+pgOptions;" style="width: 50px; float: left">
	<button style="float: left" class="btn btn-default btn-sm" type="button" onMouseOut="style.cursor='text'; this.src='{$images_paging}go_page_off.gif';" onMouseOver="style.cursor='pointer'; this.src='{$images_paging}go_page_on.gif';" onClick="pgNo = getStyle('{$nameElemPaging}', 'value', 0);if(!isFinite(pgNo) || parseInt(pgNo)!=pgNo || pgNo=='' || pgNo>noPages || pgNo<=0)alert(invalidNoPage);else window.location=pgUrl+pgNo+pgOptions;"><i class="glyphicon glyphicon-fast-forward"></i></button>
  </div>
</form>


{/if}