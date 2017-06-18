{include file="site/inc/top.tpl" page='portofoliu'}
            <!--  Content -->
            <div class="content full-height no-padding">
                <!--  fixed-info-container -->
                <div class="fixed-info-container">
                    <!--  content-nav -->
                    <div class="content-nav">
                        <ul>
                            {*<li><a href="portfolio-single2.html" class="ajax ln"><i class="fa fa fa-angle-left"></i></a></li>*}
                            <li>
                                <div class="list">
                                    <a href="/portofoliu/" class="ajax">
                                                        <span>
                                                        <i class="b1 c1"></i><i class="b1 c2"></i><i class="b1 c3"></i>
                                                        <i class="b2 c1"></i><i class="b2 c2"></i><i class="b2 c3"></i>
                                                        <i class="b3 c1"></i><i class="b3 c2"></i><i class="b3 c3"></i>
                                                        </span></a>
                                </div>
                            </li>
                            {*<li><a href="portfolio-single3.html" class="ajax rn"><i class="fa fa fa-angle-right"></i></a></li>*}
                        </ul>
                    </div>
                    <!--  content-nav end-->
                    <h3>{$project->title}</h3>
                    <div class="separator"></div>
                    <div class="clearfix"></div>
                    {if $project->text !=""}
                        {$project->text}
                    {/if}
                    {if $project->location!="" || $project->client!="" || $project->status_proiect!="" || ( $project->date!="" && $project->date!='0000-00-00')}
                        <h4>Info</h4>
                        <ul class="project-details">
                            {if ($project->date!="" && $project->date!='0000-00-00')}<li><span>Date :</span> {$project->date|date_format:"%d.%m.%Y"} </li>{/if}
                            {if $project->client!=""}<li><span>Client :</span>  {$project->client} </li>{/if}
                            {if $project->status_proiect!=""}<li><span>Status :</span> {$project->status_proiect} </li>{/if}
                            {if $project->location!=""}<li><span>Location : </span>  {$project->location}</li>{/if}
                        </ul>
                    {/if}
                    {*<a href="#" class=" btn anim-button   trans-btn   transition  fl-l" target="_blank"><span>View Project</span><i class="fa fa-eye"></i></a>*}
                </div>
                <!--  fixed-info-container end-->
                <!--  resize-carousel-holder-->
                <div class="resize-carousel-holder vis-info gallery-horizontal-holder">
                    <!--  gallery_horizontal-->
                    <div id="gallery_horizontal" class="gallery_horizontal owl_carousel">
                        {foreach from=$poze item=item key=key}
                            <!-- gallery Item-->
                            <div class="horizontal_item">
                                <div class="zoomimage"><img src="/project-picture/{$item.file}" class="intense" alt="{$project->title}"><i class="fa fa-expand"></i></div>
                                <img src="/project-picture/{$item.file}" alt="{$project->title}">
                                {*<div class="show-info">
                                    <span>Info</span>
                                    <div class="tooltip-info">
                                        <h5>Nulla blandit</h5>
                                        <p>Aat posuere sem accumsan nec. Sed non arcu non sem commodo ultricies. Sed non nisi viverra </p>
                                    </div>
                                </div>*}
                            </div>
                            <!-- gallery item end-->
                        {/foreach}
                    </div>
                    <!--  resize-carousel-holder-->
                    <!--  navigation -->
                    <div class="customNavigation">
                        <a class="prev-slide transition"><i class="fa fa-angle-left"></i></a>
                        <a class="next-slide transition"><i class="fa fa-angle-right"></i></a>
                    </div>
                    <!--  navigation end-->
                </div>
                <!--  gallery_horizontal end-->
            </div>
            <!--  Content  end -->
        </div>
        {include file="site/inc/share.tpl"}
    </div>
    <!-- Content holder  end -->
</div>
<!-- wrapper end -->
{include file="site/inc/bottom.tpl"}