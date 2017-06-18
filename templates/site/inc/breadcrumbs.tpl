
<div class="page_info border-bottom">
	<div class="container-md">
		<div class="row">
			<ul class="col-xs-12">
				{$i=1}
				{foreach from=$breadcrumbs item=item key=key}
					<li {if $i==$breadcrumbs|@count}class="last"{/if}><a {if $item!=""}href="{$item}"{/if}>{$key}</a></li>
					{$i=$i+1}
				{/foreach}
			</ul>
		</div>
	</div>
</div> <!-- end page_info -->