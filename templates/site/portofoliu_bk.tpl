{include file="site/inc/top.tpl" page='portofoliu'}
        <!--  Content  -->
        <div class="content ">
            <section class="no-padding no-border">
                <!-- Filters-->
                <div class="filter-holder filter-nvis-column">
                    <div class="gallery-filters at">
                        <a href="#" class="gallery-filter gallery-filter-active"  data-filter="*">All</a>
                        {foreach from=$categories item=item key=key}
                            <a href="#" class="gallery-filter " data-filter=".{$item->seo_name}">{$item->name}</a>
                        {/foreach}
                    </div>
                </div>
                <!-- filters end -->
                <!--  filter-button-->
                <div class="filter-button vis-fc">Filter</div>
                <!--  filter-button end -->
                <!--  gallery-items -->
                <div class="gallery-items   hid-port-info">

                    {foreach from=$projects item=item key=key}
                        {if ($key+1)%11==4}
                            <div class="gallery-item gallery-item-second {$item->seo_name}">
                                <div class="grid-item-holder">
                                    <div class="box-item">
                                        <div class="wh-info-box">
                                            <div class="wh-info-box-inner at">
                                                {if $item->main_type == 'project'}
                                                    <a href="/proiect/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                                {else}
                                                    <a href="/proiect-camera/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                                {/if}
                                                    {$item->title}
                                                </a>
                                                <span class="folio-cat">{$item->seo_name}</span>
                                            </div>
                                        </div>
                                        <img  src="/poze-projects/cover/{$item->photos}"   alt="{$item->title}">
                                    </div>
                                </div>
                            </div>
                        {else}
                            <div class="gallery-item {$item->seo_name}">
                                <div class="grid-item-holder">
                                    <div class="box-item">
                                        <div class="wh-info-box">
                                            <div class="wh-info-box-inner at">
                                                {if $item->main_type == 'project'}
                                                    <a href="/proiect/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                                {else}
                                                    <a href="/proiect-camera/{seo_link($item->title, $item->project_id)}.html" class="ajax">
                                                {/if}
                                                    {$item->title}
                                                 </a>
                                                <span class="folio-cat">{$item->seo_name}</span>
                                            </div>
                                        </div>
                                        <img  src="/poze-projects/cover/{$item->photos}"   alt="{$item->title}">
                                    </div>
                                </div>
                            </div>
                        {/if}

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