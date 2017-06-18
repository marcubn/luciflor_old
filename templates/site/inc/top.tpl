{include file="site/inc/header.tpl"}
    {include file="site/inc/app_message.tpl"}
	<!--=============== header ===============-->
	<header>
		<!-- Nav button-->
		<div class="nav-button">
			<span  class="nos"></span>
			<span class="ncs"></span>
			<span class="nbs"></span>
		</div>
		<!-- Nav button end -->
		<!-- Logo-->
		<div class="logo-holder">
			<a href="/" class="ajax"><img src="/images/logo.png" alt="Pure Mess Design"></a>
		</div>
		<!-- Logo  end-->
		<!-- Header  title -->
		<div class="header-title">
			<h2><a class="ajax" href="/"></a></h2>
		</div>
		<!-- Header  title  end-->
		<!-- share -->
		<div class="show-share isShare">
			<span>Share</span>
			<i class="fa fa-chain-broken"></i>
		</div>
		<!-- share  end-->
	</header>
	<!-- Header   end-->
	<!--=============== wrapper ===============-->
	<div id="wrapper">
		<!--=============== content-holder ===============-->
		<div class="content-holder elem scale-bg2 transition3">
			<!-- Page title -->
			<div class="dynamic-title">Pure Mess Design</div>
			<!-- Page title  end-->
			{include file="site/inc/menu.tpl" page=$page}