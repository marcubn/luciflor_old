// JavaScript Document

/**
 * Open popup 
 *
 * @parameter: url = URL
 * @parameter: w = width
 * @parameter: h = height
 * @parameter: popupName = ame of popup
 * @parameter: scrollbars (default is 0 - without scroll)
 * @access: public
 * @return: null
 * @author: CFlorin (colotin_f@yahoo.com)
 * @date: 02.02.2005 (dd.mm.YYYY)
*/ 
function popup(url, w, h, popupName, scrollbars)
{
	if(popupName == '') popupName='popup';
	if(scrollbars == '') scrollbars=0;
	if(w==0) w=(screen.width);
	if(h==0) h=(screen.height);
	options='menubar=no, scrollbars='+scrollbars+', statusbar=no, resizable=no, toolbar=no, location=no, status=yes';
	var top=(screen.height-h)/2-18;
	var left=(screen.width-w)/2-8;
	var win_open = window.open(url, popupName, 'top='+top+', left='+left+', width='+w+', height='+h+', '+options);
}

function objShowHide(objName, keepPosition)
{
	var elem=findDOM(objName, 1);
	
	if(elem.visibility=='hidden') 
	{ 
		elem.visibility='visible';
		if(keepPosition!=1)
			elem.position='relative'; 
	} 
	else 
	{ 
		elem.visibility='hidden'; 
		if(keepPosition!=1)
			elem.position='absolute'; 
	} 
}

// inner write
function innerWrite(objName, text)
{
	var elem = findDOM(objName, 0);
	elem.innerHTML = text;
}

// inner write
function imgSrcChange(objName, src)
{
	var elem = findDOM(objName, 0);
	elem.src = src;
}

function old_switchMultipleCheckBox(objForm, textMatch)
{	
	var items = objForm.elements;	
	for(i=0; i < items.length; i++)
	{
		if(items[i].name.match(textMatch))
		{
			if(items[i].checked==true)
				items[i].checked = false;
			else if(items[i].disabled==false)
				items[i].checked = true;
		}
	}
}

function switchMultipleCheckBox(objForm, textMatch)
{	
    $('#selecctall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
}

/**
* Count chars from a textarea
*
* @author Colotin Florin
*/
function countInputChars(objText, objCounter, limit)
{
	if (objText.value.length > limit)
		objText.value = objText.value.substr(0,limit);
	objCounter.value=objText.value.length;
}

function onoffToggle(objLayer, objImg, srcObjImgOn, srcObjImgOff)
{
	var _objLayer=findDOM(objLayer, 1);
	var _objImg=findDOM(objImg, 0);

	if(_objLayer.visibility=='hidden') 
	{ 
		_objImg.src=srcObjImgOn;
		_objLayer.visibility='visible'; 
		_objLayer.position='relative'; 
		_objLayer.display='block'; 		
		_objImg=srcObjImgOn;
	} 
	else 
	{ 		
		_objImg.src=srcObjImgOff;
		_objLayer.visibility='hidden'; 
		_objLayer.position='absolute';
		_objLayer.display='none'; 
	} 
}


var openedWin = 0;
var offsetHeight = 0;
function openWin(obj, url) {
	if (!openedWin) {
		applyStyle(obj, 'src', url, 0);
		applyStyle(obj, 'visibility', 'visible', 1);
		openedWin = 1;
		offsetHeight = 83;
	}
}

$(document).ready(function() {
    $('#selecctall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.jsx_checkbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1" 
                verifyRowChecked(document.form_list, 'ids');
            });
        }else{
            $('.jsx_checkbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"   
                verifyRowChecked(document.form_list, 'ids');
            });         
        }
    });
});
