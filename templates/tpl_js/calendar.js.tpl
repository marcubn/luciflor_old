<!-- begin calendar code -->
<link type="text/css" rel="stylesheet" href="{$smarty.const.ROOT_HOST}/js/jscalendar/calendar-blue.css">
<script type="text/javascript" src="{$smarty.const.ROOT_HOST}/js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="{$smarty.const.ROOT_HOST}/js/jscalendar/lang/calendar-en.js"></script>

{literal}
<script type="text/javascript">
var oldLink = null;
function setActiveStyleSheet(link, title) 
{
	var i, a, main;
	for(i=0; (a = document.getElementsByTagName("link")[i]); i++) 
	{
		if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) 
		{
			a.disabled = true;
			if(a.getAttribute("title") == title) a.disabled = false;
		}
	}
	if (oldLink) oldLink.style.fontWeight = 'normal';
	oldLink = link;
	link.style.fontWeight = 'bold';
	
	return false;
}

function selected(cal, date) 
{
	cal.sel.value = date;
	if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
		cal.callCloseHandler();
}

function closeHandler(cal) 
{
  cal.hide();
  calendar = null;
}

function showCalendar(id, format, showsTime, showsOtherMonths) 
{	
	var el = document.getElementById(id);
	if (calendar != null) 
	{
		calendar.hide();
	} 
	else 
	{
		// first-time call, create the calendar.		
		var cal = new Calendar(true, null, selected, closeHandler);
		// uncomment the following line to hide the week numbers
		// cal.weekNumbers = false;
		if (typeof showsTime == "string") 
		{			
			cal.showsTime = true;
			cal.time24 = (showsTime == "24");
		}		
		if (showsOtherMonths) 
		{
			cal.showsOtherMonths = true;
		}
		
		calendar = cal;
		cal.setRange(1900, 2070);
		cal.create();
	}
	
	calendar.setDateFormat(format);
	calendar.parseDate(el.value);
	calendar.sel = el;
	calendar.showAtElement(el.nextSibling, "Br");
	
	return false;
}
</script>
{/literal}
<!-- end calendar code -->