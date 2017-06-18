{config_load file="admin_system_vars.cfg" section="en"}
{if !$jsCssPath}{assign var=jsCssPath value="../"}{else}{assign var=jsCssPath value=""}{/if}
<html>
<head>
    <title>{$smarty.const.PAGE_TITLE_ADMIN}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache" />
     <!-- JQUERY -->
      <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <!-- JQUERY -->

  <!-- JQUERY UI -->
    <link href="/js/jquery-ui-1.10.4.custom/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
    <script src="/js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
  <!-- JQUERY UI -->

  <!-- BOOTSTRAP -->
      <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <link rel="stylesheet" href="{$smarty.const.ROOT_HOST}css/bootstrap/bootstrap-responsive.css" type="text/css" />
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  <!-- BOOTSTRAP -->

  <!-- CSS -->
    <link href="/css/mystyle.css" rel="stylesheet">
  <!-- CSS -->

  <!-- JS -->
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/utile/browser.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/utile/form_validation.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/utile/utile.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}js/utile/select_operations.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}/js/jquery/jquery.tools.min.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}/js/jquery/jquery.carousel.min.js"></script>
    <script type="text/javascript" src="{$smarty.const.ROOT_HOST}/js/fct.js"></script>
  <!-- JS -->
</head>
<body style="margin:0px; padding:0px; overflow-y:scroll;">
<fieldset>
    <legend>Photos</legend>
    <button class="btn" onclick="popup('index.php?obj={$smarty.get.obj}&action=page_act&owner={$smarty.get.owner}&owner_id={$smarty.get.owner_id}&title={$smarty.get.title|urlencode}', 500, 500, 'uplimg', 1);"><i class="icon-file"></i> upload</button>
    <br /><br />
    <ul class="thumbnails">
    {if $moduleSession.paging.noRowsResult>0}
    	{if $recList|@count>0}
            {foreach from=$recList item=item key=key}
                <li class="span1" style="float: left; width:150px; height:180px; {if $item.def==1}border:1px solid #000; border-radius: 3px;{/if}">
                <table class="ctrls" id="ctrl_{$item.$idName}" style="width:140px; position: absolute; display:none;">
                  <tr>
                      <td>
                        <button type="button" onclick="window.location='index.php?obj={$smarty.get.obj}&action=switch&{$idName}={$item.$idName}&fieldName={$flagName}{$paging_options}'" style="width: 35px;" class="btn btn-default" title="Switch status"><i class="glyphicon glyphicon-{if $item.$flagName==1}lock{else}ok{/if}">&nbsp;</i></button>
                      </td>
                      <td align="center">
                        <button type="button" onclick="popup('index.php?obj={$smarty.get.obj}&action=page_act&owner={$smarty.get.owner}&owner_id={$smarty.get.owner_id}&{$idName}={$item.$idName}', 500, 500, 'uplimg', 1); void(false);" title="Editeaza proprietati" style="width: 35px;" class="btn btn-default"><i class="glyphicon glyphicon-edit">&nbsp;</i></button>
                      </td>
                      <td align="left">
                        <button type="button" onclick="popup('index.php?obj={$smarty.get.obj}&action=page_edit&owner={$smarty.get.owner}&owner_id={$smarty.get.owner_id}&{$idName}={$item.$idName}', 700, 700, 'editlimg', 1); void(false);" title="Prelucreaza fotografia" style="width: 35px;" class="btn btn-default"><i class="glyphicon glyphicon-picture">&nbsp;</i></button>
                      </td>
                      <td align="right">
                        <button type="button" onclick="javascript:if(confirm('Are you sure?')==1) document.location='index.php?obj={$smarty.get.obj}&action=delete&{$idName}={$item.$idName}{$paging_options}'" title="Sterge fotografia" style="width: 35px;" class="btn btn-danger"><i class="glyphicon glyphicon-remove">&nbsp;</i></button>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="4">
                            <p class="well" style="font-size:10px; padding:0px;">
                                {$item.file|truncate:20} ({$item.file_size})
                            </p>
                      </td>
                  </tr>
                </table>
                <a class="thumbnail" style="height:180px; width:140px; overflow:hidden;" onmouseover="$('.ctrls').hide(); $('#ctrl_{$item.$idName}').show(); " href="javascript:popup('{$smarty.const.PHOTOS_UPLOAD_URL}/{$item.file}', 550, 350, 'uplimg', 1); void(false);" title="Click to preview">
                    <img src="{$smarty.const.PHOTOS_UPLOAD_URL}{$item.file}?time={$smarty.now}" alt="" />
                </a>
              </li>
            {/foreach}
        {/if}
    {/if}
    </ul>
    <div class="clearfix"></div>
    <div class="form-group col-md-12 panel-info">
        {include file="tpl_utile/paging_admin_upl.tpl" pagingVariables=$moduleSession.paging}
    </div>
</fieldset>
</body>
</html>