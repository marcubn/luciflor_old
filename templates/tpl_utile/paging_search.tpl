{assign var=images_paging value=$smarty.const.IMAGES_URL|@cat:"admin/paging/"}

{if !$nameElemPaging}{assign var=nameElemPaging value="no_pag"}{/if}

{assign var=noRowsResult value=$pagingVariables.noRowsResult}
{assign var=noRowsDisplayed value=$pagingVariables.noRowsDisplayed}
{assign var=pgNo value=$pagingVariables.pgNo}
{assign var=pgAction value=$pagingVariables.action}
{assign var=pages value=$pagingVariables.pages}

{math equation="ceil(x/y)" assign=noPages x=$noRowsResult y=$noRowsDisplayed}

{if $noRowsResult==0 || $noPages==1}
	
{else}
	<div class="pagination">
		{if $pgNo==1}
			<a href="javascript:;" class="prev">Pagina anterioara</a>
		{else}
			<a href="{if $pgAction!=""}/{$pgAction}{/if}&pgNo={math equation="x-1" x=$pgNo}" class="prev">Pagina anterioara</a>
		{/if}
		<div class="pages">
			{foreach from=$pages item=page key=key}
				{if $page==$pgNo}
					<a class="selectedPage" title="{$pgNo}">{$pgNo}</a>
				{else}
					<a href="{if $pgAction!=""}/{$pgAction}{/if}&pgNo={$page}" title="Pagina {$page}">{$page}</a>
				{/if}
			{/foreach}
		</div>
		{if $pgNo==$noPages}
			<a href="javascript:;" class="next">Pagina urmatoare</a>
		{else}
			<a href="{if $pgAction!=""}/{$pgAction}{/if}&pgNo={math equation="x+1" x=$pgNo}" class="next">Pagina urmatoare</a>
		{/if}
		<div class="clearAll"></div>
	</div>
{/if}