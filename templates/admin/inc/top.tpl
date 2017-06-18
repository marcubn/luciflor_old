{* Top Module - do not modify it *}
{include file="admin/inc/header.tpl"}
<body>
    <div class="container-fluid main_container">
    	<div class="navbar-inner main_top_container">
            <div class="row">
                <div class="col-md-2 logos_top">
                    <a class="logo_client" href="/" target="_blank"><img src="{$smarty.const.IMAGES_URL}admin/logo.jpg" style="width:80%; margin:auto;" /></a>
                </div>
                <div class="col-md-10 helper_top">
                    <div class="site_admin">
                        <strong><a href="index.php?obj=index" class="text1">{$smarty.const.PAGE_TITLE_ADMIN}</a></strong>
                    </div>
                    <span> | </span>
                    <div class="lg_date">
                        <strong>{#txtLoginDate#}:</strong> {$UL.login_time|date_format:$DTF}
                    </div>
                    <span> | </span>
                    <div class="url_site">
                        <strong>{#txtURLSite#}:</strong> <a href="{$smarty.const.ROOT_HOST}" class="text1" target="_blank">{$smarty.const.SITE_NAME}</a>
                    </div>
                    <div class="logout_btns">
                        <i style="margin-right: 10px;" class="glyphicon glyphicon-user"></i><strong>{#txtUser#}:</strong> {$UL.user_userid} / {$UL.user_name} 
                        <a href="index.php?obj=user&action=logout" class="link2">{#txtLogout#}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 well sidebar_left">
                  {include file="admin/inc/menu_top.tpl"}
                </div>
                <div class="col-md-10">
                  {include file="admin/inc/app_message.tpl"}
                  <!-- begin content -->

