<script type="text/javascript">
	$(function() {
	    $( ".datepicker" ).datepicker({
	      showOn: "button",
	      buttonImage: "/img/calendar_day.png",
	      buttonImageOnly: true,
	      dateFormat: 'yy-mm-dd'
	    });
  	});
</script>
<input type="text" style="width: 180px;" name="{$dateElemName}" id="{$dateElemId|default:$dateElemName}" class="datepicker form-control col-md-4" value="{if $dateElemValue!='0000-00-00'}{$dateElemValue}{/if}" 
	readonly class="form-control col-md-4" /><a href="javascript:applyStyle('{$dateElemId|default:$dateElemName}', 'value', '', 0); void(false);"><img src="{$smarty.const.ROOT_HOST}/img/admin/utile/icon-delete.png" hspace="0" border="0" align="middle" alt="" /></a>