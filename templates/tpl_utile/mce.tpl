{assign var=jsCssPath value="root"}
{include file="admin/inc/popup_top.tpl" pageTitle="Rich Text Editor"}

<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
{* simple
	{literal}
		<script type="text/javascript">
			tinyMCE.init({
				mode : "textareas",
				theme : "simple"
			});
		</script>
	{/literal}
*}
{literal}
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			mode : "textareas",
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
	
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
	
			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",
			auto_resize : false,
	
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
	
			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	</script>
{/literal}

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="295">
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width:100%; height:100%;">{$text}</textarea>
		</td>
	</tr>
	<tr>
		<td align="right" style="padding-top:5px;">
			<input type="button" value=" {#bClose#} " onClick="window.close();" {$buttonStyle}> 
			&nbsp;
			<input type="button" value=" {#bUpd#} " onclick="window.opener.document.form_act.{$elemField}.value=tinyMCE.get('elm1').getContent(); window.close();" {$buttonStyle}>
		</td>
	</tr>
</table>
{include file="admin/inc/popup_bottom.tpl"}