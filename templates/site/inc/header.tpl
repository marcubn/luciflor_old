<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--=============== basic  ===============-->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{if isset($meta_title)}{$meta_title}{elseif isset($site_meta_title)}{$site_meta_title}{else}{$SDoc->getPageTitle()}{/if}</title>
        {if isset($site_meta_description) && $site_meta_description!=""}
            <meta name="description" content="{$site_meta_description|default:'Pure Mess Design'}">
        {else}
            {$SDoc->displayMetaTags()}
        {/if}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, follow"/>
        <meta http-equiv="Cache-Control" content="max-age=200" />

        <!--=============== css  ===============-->
        <link type="text/css" rel="stylesheet" href="/css/reset.css">
        <link type="text/css" rel="stylesheet" href="/css/plugins.css">
        <link type="text/css" rel="stylesheet" href="/css/style.css">
        <link type="text/css" rel="stylesheet" href="/css/yourstyle.css">
        <link type="text/css" rel="stylesheet" href="/js/jquery-ui-1.10.4.custom/css/smoothness/jquery-ui-1.10.4.custom.min.css">
        <!--=============== favicons ===============-->
        <link rel="shortcut icon" href="/images/favicon.ico">
        {$SDoc->systemHead()}

        {literal}
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-40043718-4', 'auto');
                ga('send', 'pageview');

            </script>
        {/literal}
    </head>
    <body>
    {literal}
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-40043718-4', 'auto');
            ga('send', 'pageview');

        </script>
    {/literal}

    <div class="loader">
        <div class="tm-loader">
            <div id="circle"></div>
        </div>
    </div>
    <!--================= main start ================-->
    <div id="main">