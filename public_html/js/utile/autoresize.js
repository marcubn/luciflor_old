//tot contentul trebuie pus intr-un div numit content

function resizeToHeight(offset) {
	if (document.getElementById) {
		var windowHeight = getWindowHeight();
		if (windowHeight>0) {
			var tmp = findDOM('content', 0);
			var contentHeight = tmp.offsetHeight;
			window.resizeBy(0, contentHeight + offset - getWindowHeight());
		}
	}
}

function getWindowHeight() {
	var myWidth = 0, myHeight = 0;
	if (typeof(window.innerWidth) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} 
	else 
		if (document.documentElement &&
			(document.documentElement.clientWidth || document.documentElement.clientHeight)) {
			//IE 6+ in 'standards compliant mode'
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		} 
		else 
			if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
				//IE 4 compatible
				myWidth = document.body.clientWidth;
				myHeight = document.body.clientHeight;
			}
	
	return myHeight;
}

function getWindowWidth() {
	var myWidth = 0, myHeight = 0;
	if (typeof(window.innerWidth) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} 
	else 
		if (document.documentElement &&
			(document.documentElement.clientWidth || document.documentElement.clientHeight)) {
			//IE 6+ in 'standards compliant mode'
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		} 
		else 
			if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
				//IE 4 compatible
				myWidth = document.body.clientWidth;
				myHeight = document.body.clientHeight;
			}
	
	return myWidth;
}

function centerWindow() {
	var size = new Array(2);
	size[0] = getWindowWidth();
	size[1] = getWindowHeight();
	window.moveTo(Math.round(screen.width/2 - size[0]/2), Math.round(screen.height/2 - size[1]/2));
}