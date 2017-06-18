{include file="site/inc/top_home.tpl"}
<!--  Content -->
<div class="content full-height">
    <!--full-height wrap -->
    <div class="full-height-wrap">
        <div class="full-width-slider-holder">
            <div  class="full-width-slider owl_carousel">

                {foreach from=$slides item=item key=key}
                    <!-- 1-->
                        <div class="item">
                            <div class="bg bg-slider" style="background-image:url(/slide-home/{$item->slide})"></div>
                            <div class="overlay"></div>
                            <!-- enter-wrap -->
                            <div class="enter-wrap-holder cent-holder wht-bg">
                                <div class="enter-wrap">
                                    <h3>{$item->title}</h3>
                                    <a href="/portofoliu/" class="ajax btn anim-button   trans-btn   transition "><span>Vezi proiecte</span><i class="fa fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                            <!-- enter-wrap end  -->
                        </div>
                    <!-- 1 end -->
                {/foreach}
            </div>
            <!--  navigation -->
            <div class="customNavigation">
                <a class="prev-slide transition"><i class="fa fa-angle-left"></i></a>
                <a class="next-slide transition"><i class="fa fa-angle-right"></i></a>
            </div>
            <!--  navigation end-->
        </div>
    </div>
    <!-- full-height-wrap end  -->
</div>
<!-- Content   end -->
<!-- share  -->
<div class="share-inner">
    <div class="share-container  isShare"  data-share="['facebook','googleplus','twitter','linkedin']"></div>
    <div class="close-share"></div>
</div>
<!-- share end -->
</div>
<!-- Content holder  end -->
</div>
<!-- wrapper end -->
{include file="site/inc/bottom.tpl"}