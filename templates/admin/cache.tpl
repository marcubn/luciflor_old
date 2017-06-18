{include file="admin/inc/top.tpl"}
<!-- [+]Module name-->
    <ul class="breadcrumb">
        <li><a href="index.php?obj=index&action=page_settings">System settings</a><span class="divider"></span></li>
        <li class="active">Cache</li>
    </ul> 
<!-- [-]Module name-->

<a href="/admin/index.php?obj=cache&action=home&item=photos"><i class="icon-wrench"></i> Clear photo cache</a>
<br/>
<br/>
<a href="/admin/index.php?obj=cache&action=home&item=files"><i class="icon-wrench"></i> Clear smarty cache</a>
{include file="admin/inc/bottom.tpl"}