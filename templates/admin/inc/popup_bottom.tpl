<script type="text/javascript">
	var falgPrint = '{$smarty.get.print}';
	
	{literal}
	function redirect_back()
	{
		history.go(-1);
	}
	
	if(falgPrint=='1')
	{
		window.print();
		setInterval("redirect_back()",1000);
	}
	{/literal}
</script>
</div>
</body>
</html>
