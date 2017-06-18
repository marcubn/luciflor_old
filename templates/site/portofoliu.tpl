{include file="site/inc/top.tpl" page='portofoliu'}
<!--  Content  -->
<div class="content ">
    <section class="no-padding no-border no-bg">
        <!-- Filters-->
        <div class="filter-holder filter-vis-line">
            <div class="gallery-filters">
                <a href="#" class="gallery-filter gallery-filter-active"  data-filter="*">All</a>
                {foreach from=$categories item=item key=key}
                    <a href="#" class="gallery-filter " data-filter=".{$item->seo_name}">{$item->name}</a>
                {/foreach}
            </div>
        </div>
        <!-- filters end -->
        <!-- gallery-items -->
        <div class="gallery-items    hid-port-info grid-small-pad">
            {foreach from=$projects item=item key=key}
                <!-- 1 -->
                    <div class="gallery-item {$item->seo_name}">
                        <div class="grid-item-holder">
                            <div class="box-item">
                                <a
                                {if $item->main_type == 'project'}
                                    href="/proiect/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                {else}
                                    href="/proiect-camera/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                {/if}
                                    <img  src="/poze-projects/cover/{$item->photos}"   alt="{$item->title}">
                                </a>
                            </div>
                            <div class="grid-item">
                                <h3>
                                    <a
                                    {if $item->main_type == 'project'}
                                        href="/proiect/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                    {else}
                                        href="/proiect-camera/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                    {/if}
                                        {$item->title}
                                    </a>
                                </h3>
                                <span>{$item->name}</span>
                            </div>
                        </div>
                    </div>
                <!-- 1 end -->
            {/foreach}
        </div>
        <!-- end gallery items -->
    </section>
</div>
<!--  Content  end -->
{include file="site/inc/share.tpl"}
</div>
<!-- Content holder  end -->
</div>
<!-- wrapper end -->
{include file="site/inc/bottom.tpl"}