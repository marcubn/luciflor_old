{include file="site/inc/top.tpl"}
    {include file="site/inc/menu_top.tpl"}
        <div class="section fullbackground">
        	<img src="/images/headers/services.png" class="fullbackground" align="">
            <div class="inner-section group">
            	{include file="site/inc/breadcrumbs.tpl"}
                <div class="box span_6_of_12 headers">
                    <h1>
                        <i class="size35-prod {if isset($parametru->icon_class_large) && $parametru->icon_class_large!=""}{$parametru->icon_class_large}{else}uniqablack{/if}"></i>
                        {$page->page_titlu}</h1>
                </div>
            </div>

            <div class="inner-section group">
                <div class="box span_9_of_12 to-upper">
                    {$page->page_text}
                </div>
                <div class="box span_3_of_12 contact-box">
                    {include file="site/inc/default_product_pages.tpl"}
                </div>
            </div>
            {*}
            <div class="inner-section group">
                <div class="box span_12_of_12">
                    {include file="site/inc/benefits.tpl"}
                </div>
            </div>
            {*}
        </div>
{include file="site/inc/bottom.tpl"}

