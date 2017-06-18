// JavaScript Document

var isDHTML=0;
var isId=0;
var isAll=0;
var isLayers=0;

if(document.getElementById) 
{
	isId=1;
	isDHTML=1;
}
else 
{
	if(document.all) 
	{
		isAll=1;
		isDHTML=1;
	}
	else 
	{
		browserVersion=parseInt(navigator.appVersion);
		if(navigator.appName.indexOf('Netscape')!=-1 && (browserVersion==4)) 
		{
			isLayers=1;
			isDHTML=1;
		}
	}
}
//****************************************************************************************//
function findDOM(objectId, withStyle) 
{
	if(withStyle==1) 
	{
		if(isId) 
			return (document.getElementById(objectId).style);
		else if(isAll)
			return (document.all[objectId].style);
		else if(isLayers)
			return (document.layers[objectId]);
	}
	else 
	{
		if(isId) 
			return (document.getElementById(objectId));
		else if(isAll)
			return (document.all[objectId]);
		else if(isLayers)
			return (document.layers[objectId]); 
	}	  
}
//****************************************************************************************//
function findFrameDOM(objectId, withStyle, frame, is_top)
{
	if(is_top==-1) 
	{
		if(withStyle==1) 
		{
			if(isId) 
				return (window.top.frames[frame].document.getElementById(objectId).style);
			else if(isAll)
				return (window.top.frames[frame].document.all[objectId].style);
			else if(isLayers)
				return (window.top.frames[frame].document.layers[objectId]);
		}
		else 
		{
			if(isId) 
				return (window.top.frames[frame].document.getElementById(objectId));
			else if(isAll)
				return (window.top.frames[frame].document.all[objectId]);
			else if(isLayers)
				return (window.top.frames[frame].document.layers[objectId]); 
		}	  
	}
	else 
	{
		if(withStyle==1) 
		{
			if(isId) 
				return (window.parent.frames[frame].document.getElementById(objectId).style);
			else if(isAll)
				return (window.parent.frames[frame].document.all[objectId].style);
			else if(isLayers)
				return (window.parent.frames[frame].document.layers[objectId]);
		}
		else 
		{
			if(isId) 
				return (window.parent.frames[frame].document.getElementById(objectId));
			else if(isAll)
				return (window.parent.frames[frame].document.all[objectId]);
			else if(isLayers)
				return (window.parent.frames[frame].document.layers[objectId]); 
		}	  
	}
}
//****************************************************************************************//
function findParentDOM(objectId, withStyle)
{
	if(withStyle==1) 
	{
		if(isId) 
			return (window.parent.document.getElementById(objectId).style);
		else if(isAll)
			return (window.parent.document.all[objectId].style);
		else if(isLayers)
			return (window.parent.document.layers[objectId]);
	}
	else 
	{
		if(isId) 
			return (window.parent.document.getElementById(objectId));
		else if(isAll)
			return (window.parent.document.all[objectId]);
		else if(isLayers)
			return (window.parent.document.layers[objectId]); 
	}	  
}
//****************************************************************************************//
function findOpenerDOM(objectId, withStyle)
{
	if(withStyle==1) 
	{
		if(isId) 
			return (window.opener.document.getElementById(objectId).style);
		else if(isAll)
			return (window.opener.document.all[objectId].style);
		else if(isLayers)
			return (window.opener.document.layers[objectId]);
	}
	else 
	{
		if(isId) 
			return (window.opener.document.getElementById(objectId));
		else if(isAll)
			return (window.opener.document.all[objectId]);
		else if(isLayers)
			return (window.opener.document.layers[objectId]); 
	}	  
}
//****************************************************************************************//


function applyStyle(obj, prop, value, type) {
	var temp = findDOM(obj, type);
	eval("temp." + prop + " = '" + value + "'");
}

function getStyle(obj, prop, type) {
	var temp = findDOM(obj, type);
	eval("var ret = temp." + prop);
	return ret;
}

function applyStyleParent(obj, prop, value, type) {
	var temp = findParentDOM(obj, type);
	eval("temp." + prop + " = '" + value + "'");
}

function getStyleParent(obj, prop, type) {
	var temp = findParentDOM(obj, type);
	eval("var ret = temp." + prop);
	return ret;
}