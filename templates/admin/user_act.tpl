<script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/utile/select_operations.js"></script>
{include file="admin/inc/top.tpl"}


<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">Users</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.action) && $smarty.get.action=='page_myaccount'}
        {#txtMyAccount#}
        {elseif isset($smarty.get.act) &&$smarty.get.act=='upd' && $smarty.get.$idName!=''}
        {#txtEdit#} {#txtUser#}
        {else}
        {#txtAdd#} {#txtUser#}
        {/if}
    </li>
</ul> 


<form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" onsubmit="copyConcatContentFromSelectElemToTextElem(document.form_act.assigned, document.form_act.shared_str); return formValidate('form_act',1);">
	<input type="hidden" name="act" value="{$form_act.act}">
	<input type="hidden" name="{$idName}" value="{$form_act.$idName}">
	<input type="hidden" name="shared_str" value="">

	<div class="row col-md-12 container">
		<div class="col-md-6">
			<p class="pull-right"><strong>{#txtUserLoginData#}</strong></p>
			<div class="form-group col-md-12">
			    <input type="text" class="form-control pull-right" id="user_userid" name="user_userid" value="{if isset($form_act.user_userid)}{$form_act.user_userid}{/if}" style="width: 200px;">
			    <label for="user_userid" class="pull-right">{#txtUser#}&nbsp;</label>
		  	</div>

		  	<div class="form-group col-md-12">
			    <input type="password" class="form-control pull-right" id="user_pass" name="user_pass" value="{if isset($form_act.pass_dec)}{$form_act.pass_dec}{/if}" style="width: 200px;">
			    <label for="user_pass" class="pull-right">{#txtPass#}&nbsp;</label>
		  	</div>

		  	<div class="form-group col-md-12">
		    	<input type="password" class="form-control pull-right" id="c_pass" name="c_pass" value="" style="width: 200px;">
			    <label for="user_pass" class="pull-right">{#txtCPass#}&nbsp;</label>
		  	</div>

		  	<div class="form-group col-md-12">
		  		<select name="user_active" id="flag" class="form-control pull-right" style="width: 200px;"> 
					<option value="1" {if isset($form_act.user_active) && $form_act.user_active=="1"}selected{/if}>{#txtActiv#}</option> 
					<option value="0" {if isset($form_act.user_active) && $form_act.user_active=="0"}selected{/if}>{#txtBlocked#}</option> 
				</select>
			    <label for="user_active" class="pull-right">{#txtStatus#}&nbsp;</label>
		  	</div>

		  	{assign var="user_cookie" value=$smarty.const.VAR_COOKIE_USER} 
			{assign var="pass_cookie" value=$smarty.const.VAR_COOKIE_PASS}							
			{if isset($smarty.cookies.$user_cookie) && $smarty.cookies.$user_cookie!='' && $smarty.cookies.$user_cookie==$form_act.user_userid && $smarty.cookies.$pass_cookie==$form_act.user_pass}
				<div class="form-group col-md-12">
				    <input type="checkbox" class="form-control pull-right" id="del_autologin" name="del_autologin" value="1" style="width: 200px;">
				    <label for="del_autologin" class="pull-right">{#txtPass#}&nbsp;</label>
			  	</div>
			{/if}
		</div>

		<div class="col-md-5">
			<div class="form-group col-md-8">
				<p><strong>{#txtUserPersonalData#}</strong></p>
			    <input type="text" class="form-control pull-right" id="user_name" name="user_name" value="{if isset($form_act.user_name)}{$form_act.user_name}{/if}" style="width: 200px;">
			    <label for="user_name" class="pull-right">{#txtName#}&nbsp;</label>
		  	</div>

		  	<div class="form-group col-md-8">
			    <input type="text" class="form-control pull-right" id="user_email" name="user_email" value="{if isset($form_act.user_email)}{$form_act.user_email}{/if}" style="width: 200px;">
			    <label for="user_email" class="pull-right">{#txtEmail#}&nbsp;</label>
		  	</div>

		  	<div class="form-group col-md-8">
			    <input type="text" class="form-control pull-right" id="user_tel" name="user_tel" value="{if isset($form_act.user_tel)}{$form_act.user_tel}{/if}" style="width: 200px;">
			    <label for="user_tel" class="pull-right">{#txtTel#}&nbsp;</label>
		  	</div>
            
            <div class="form-group col-md-8">
		  		<select name="user_group" id="flag" class="form-control pull-right" style="width: 200px;"> 
                   <option value="0" {if isset($form_act.user_group) && $form_act.user_group=="0"}selected{/if}>{#txtNoGroup#}</option>
                    {foreach from=$user_group key=key item=item}
					   <option value="{$item.group_id}" {if isset($form_act.user_group) && $form_act.user_group==$item.group_id}selected{/if}>{$item.group_name}</option> 
                    {/foreach}
				</select>
			    <label for="user_group" class="pull-right">{#txtUserGroup#}&nbsp;</label>
		  	</div>
		</div>
	</div>

	<div class="row col-md-12 container">
		<h4 class="col-md-6 pull-right"><strong>{#txtPermissions#}</strong></h4>	
	</div>
	<div class="row col-md-12 container">

		<div class="col-md-5 pull-right">
			<label for="assigned" class="container">{#txtAssigned#}&nbsp;</label>
			<select name="assigned" id="assigned" multiple size="10" style="width:250px"> 											                  
				{html_options values=$vectPermiss.assigned.id output=$vectPermiss.assigned.name}				                 
			</select>
		</div>

		<div class="col-md-1 pull-right">
		<br />
			{if $accessUpdatePermiss}
	            <input type="button" style="width:70px;" value="{#txtAll#} >" onClick="copyContentBetweenSelectElem(document.form_act.available, document.form_act.assigned, true)" {$buttonStyle} />
	            <br><br>
				<input type="button" style="width:70px;" value="==>" onClick="copyContentBetweenSelectElem(document.form_act.available, document.form_act.assigned, false)" {$buttonStyle} />
				<br><br> 
				<input type="button" style="width:70px;" value="<==" onClick="copyContentBetweenSelectElem(document.form_act.assigned, document.form_act.available, false)" {$buttonStyle} /> 
				<br><br> 
				<input type="button" style="width:70px;" value="< {#txtAll#}" onClick="copyContentBetweenSelectElem(document.form_act.assigned, document.form_act.available, true)" {$buttonStyle} />
			{else}
				<input type="button" style="width:70px;" value="{#txtAll#} >" disabled> 
				<br><br> 
				<input type="button" style="width:70px;" value="==>" disabled> 
				<br><br> 
				<input type="button" style="width:70px;" value="<==" disabled> 
				<br><br> 
				<input type="button" style="width:70px;" value="< {#txtAll#}" disabled>
			{/if}
		</div>

		<div class="col-md-3 pull-right">
			<label for="available" class="container">{#txtAvailable#}&nbsp;</label>
			<select name="available" id="available" multiple size="10" style="width:250px"> 											                  
				{html_options values=$vectPermiss.available.id output=$vectPermiss.available.name}
			</select>
		</div>
	</div>

	<div class="row col-md-12 container">
		<div class="pull-right col-md-3">
		<input type="submit" name="b1" value="{#bSave#}" {$buttonStyle}>
		{if $smarty.get.action!='page_myaccount'}
			<input type="button" name="r1" value="{#bBackToList#}" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'" {$buttonStyle}> 
		{/if}
		</div>
	</div>

</form>

<script type="text/javascript">
	document.form_act.shared_str.oblig = "true";
	document.form_act.user_userid.oblig = "true";
	document.form_act.user_name.oblig = "true";
	document.form_act.user_pass.oblig = "true";			
	document.form_act.c_pass.oblig = "true";
	document.form_act.c_pass.equiv = "user_pass";
	document.form_act.user_email.format = "email";
</script> 
{include file="admin/inc/bottom.tpl"}
