{include file="admin/inc/top.tpl"}

<!-- [+]Module name -->
<ul class="breadcrumb">
    <li><a href="index.php?obj={$smarty.get.obj}&action=page_list">{$moduleTitle}</a><span class="divider"></span></li>
    <li class="active">{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</li>
</ul> 
<!-- [-]Module name-->
<div class="row">
    <div class="col-md-12">
		<table class="table">
        <tr>
        <td>
        <fieldset>
            <legend>{if isset($smarty.get.act) && $smarty.get.act=='upd' && $smarty.get.$idName!=''}{#txtEdit#}{else}{#txtAdd#}{/if}</legend>
				<form name="form_act" action="index.php?obj={$smarty.get.obj}&action=add_upd" method="post" enctype="multipart/form-data">
				<input type="hidden" name="act" value="{$form_act.act}">
				<input type="hidden" name="{$idName}" value="{$form_act.$idName}">
                <label>Client / Admin</label>
                <select name="mails_to" class="form-control">
                    <option {if $form_act.mails_to=="client"} selected="selected" {/if} >Client</option>
                    <option {if $form_act.mails_to=="admin"} selected="selected" {/if} >Admin</option>
                </select>
                <br />
                <label>Email to:</label>
                <input type="text" name="mails_emails" value="{$form_act.mails_emails}"  class="form-control"/>
                <br /><em class="info">
                Entry the emails comma separated if they are more then one.<br />
                If the notification is a Client type, the emails will be sent both to the client and the emails above.
                </em>
                <br /><br />
                <label>Notification ID:</label>
                <input type="text" name="mails_type" value="{$form_act.mails_type}" class="form-control" />
                <br />
                <label>Info:</label>
                <input type="text" name="mails_about" value="{$form_act.mails_about}" class="form-control" />
                <br />
                <label>Template:</label>
                <select name="mail_tpl" class="form-control">
                    <option value=""> - default</option>
                    <option value="mail.tpl" {if $form_act.mail_tpl=="mail.tpl"}selected{/if}>- Product purchase</option>
                    <option value="mail_my_account.tpl" {if $form_act.mail_tpl=="mail_my_account.tpl"}selected{/if}>- My Account</option>
                </select>
                <br />
        </fieldset>
        <br />
        <fieldset>
            <legend>EMail content</legend>
                <label>Subject:</label>
                <input type="text" name="mails_subject" value="{$form_act.mails_subject}" class="form-control" />
                {*}
                <br />
                <label>Title:</label>
                <input type="text" name="mails_title" value="{$form_act.mails_title}" class="form-control pull-right" />
                {*}
                <br />
                <label>Content:</label>
                {include file="admin/inc/mce.tpl"}
                <textarea name="mails_content" class="mceEditor" rows="25" style="width:800px;">{$form_act.mails_content}</textarea>
                <br />
                <label>Status</label>
                <input type="checkbox" name="mails_status" value="1" {if $form_act.mails_status!=="0"}checked="checked"{/if} />
                <br />
				<table border="0" cellpadding="3" cellspacing="0">
					<!-- [-]Editable Region -->
					<tr>
						<td colspan="2" align="right">
							{#txtBackToEditForm#} <input type="checkbox" name="backToEditForm" checked class="bd0">
							<input type="submit" value="{#bSave#}" {$buttonStyle} />
                            <input type="button" value="Preview" onClick="popup('index.php?obj={$smarty.get.obj}&action=preview&{$idName}={$form_act.$idName}', 600, 600, '', 'yes');"  {$buttonStyle} />
							<input type="button" value="go to list" onClick="window.location='index.php?obj={$smarty.get.obj}&action=page_list'" {$buttonStyle}>
						</td>
					</tr>
					</form>
				</table>
			</fieldset>
		</td>
        <td style="vertical-align:top !important;">
            <fieldset>
                <legend>Mail Shortcuts</legend>
                <table>
                    <tr>
                        <td colspan="2"><strong>Policy Daa</strong></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%AWB_ID%');">%AWB_ID%</a></td>
                        <td>AWB Code</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%POLICY_NUMBER%');">%POLICY_NUMBER%</a></td>
                        <td>Policy Number</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%POLICY_END_DATE%');">%POLICY_END_DATE%</a></td>
                        <td>Policy End Date</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%POLICY_START_DATE%');">%POLICY_START_DATE%</a></td>
                        <td>Policy Start Date</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%POLICY_PRICE%');">%POLICY_PRICE%</a></td>
                        <td>Policy Price</td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><strong>Members</strong></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%NAME%');">%NAME%</a></td>
                        <td>Name</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%SURNAME%');">%SURNAME%</a></td>
                        <td>Surname</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%EMAIL%');">%EMAIL%</a></td>
                        <td>Email</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%PASSWORD%');">%PASSWORD%</a></td>
                        <td>Password - this should be only in registration or password recovery mails to own user</td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><strong>Links</strong></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%LINK_SITE%');">%LINK_SITE%</a></td>
                        <td>Link to Site</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%LINK_ACTIVATE%');">%LINK_ACTIVATE%</a></td>
                        <td>Activation Link</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%LINK_RECOMEND%');">%LINK_RECOMEND%</a></td>
                        <td>Recomend Agent Link</td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><strong>Custom Block</strong></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:ClickShortcut('%BLOCK%');">%BLOCK%</a></td>
                        <td>Block Custom</td>
                    </tr>
                </table>
                
                
            
            </form>
        </fieldset>
    </div>
</div>
<script type="text/javascript">
    function ClickShortcut(shortcut)
    {
        if (typeof tinyMCE  == 'undefined') return;
        if (shortcut.indexOf('LINK%'))
            shortcut='<a href="'+shortcut+'">'+shortcut+'</a>';
        tinyMCE.execCommand("mceInsertContent",false,shortcut);
    } 
</script>
{include file="admin/inc/bottom.tpl"}
