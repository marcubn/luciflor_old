// Culorile default ale input-urilor
var elemFormDefaultBackColor = "#FFFFFF";
var elemFormBackColor = "#C6FFFF";

//===> BEGIN SET VARIABLES GLOBALS 
var reWhiteSpace = /^\s+$/;
var reDigit = /^\d$/;
var reInteger = /^\d+$/;
var reSignedInteger = /^(\+|\-)?\d+$/;
var reFloat = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
var reSignedFloat = /^(((\+|\-)?\d+(\.\d*)?)|((\+|\-)?(\d*\.)?\d+))$/;
var reLetter = /^[a-zA-Z]$/;
var reAlphabetic = /^[a-zA-Z]+$/;
var reLetterOrDigit = /^([a-zA-Z]|\d)$/;
var reAlphanumeric = /^[a-zA-Z0-9]+$/;
//var reEmail = /^([\w-]+\.)*[\w-]+\@([\w-]+\.)+[a-zA-Z]{2,3}$/;
var reEmail = /^([a-z\d]+([\.\-_]?[a-z\d]+)*)@([a-z\d]+[\.\-]?[a-z\d]+|[\.]?[a-z\d]+)+\.([a-z]{2}|com|net|org|edu|biz}info|gov)$/i;
var reZipCode = /^\d{5}$/;
var reDep = /^((\d\d)|(2A)|(2B)|(97[1-6]))$/;
//var reDate = /^(\d{2}\/){2}\d{4}$/;
var reDate = /^\d{4}(\-\d{2}){2}$/;
var reUrl = /^http\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}$/;
//<=== END SET VARIABLES GLOBALS 

//**************************************************************************************************//

function isEmpty(sIn){return ((sIn == null) || (sIn.length == 0));}

//**************************************************************************************************//

function isWhiteSpace(sIn){return (isEmpty(sIn) || reWhiteSpace.test(sIn));}

//**************************************************************************************************//

function isDigit(sIn){return reDigit.test(sIn);}

//**************************************************************************************************//

function isInteger(sIn){return reInteger.test(sIn);}

//**************************************************************************************************//

function isSignedInteger(sIn){return reSignedInteger.test(sIn);}

//**************************************************************************************************//

function isIntegerInRange(sIn, i_Min, i_Max)
{
    var flag_min, flag_max;
	flag_min=0;
	flag_max=0;
	var iNum, iMin, iMax;
    var bOkMin, bOkMax;	
    bOkMin = true;
    bOkMax = true;
	
    if(isSignedInteger(sIn))
	{
        iNum = parseInt(sIn, 10); 
		if(i_Min!="")
		{
            iMin = parseInt(i_Min, 10);
            bOkMin = (iNum>=iMin);
        }
        if(i_Max!="")
		{
            iMax = parseInt(i_Max, 10);
            bOkMax = (iNum<=iMax)
        }
		if(i_Max < i_Min)
			return false;
		return (bOkMin && bOkMax);
    }
    else 
		return false;
}

//**************************************************************************************************//

function isFloat(sIn){return reFloat.test(sIn);}

//**************************************************************************************************//

function isSignedFloat(sIn){return reSignedFloat.test(sIn);}

//**************************************************************************************************//

function isFloatInRange(sIn, f_Min, f_Max)
{
    var fNum, fMin, fMax;
    var bOkMin, bOkMax;

    bOkMin = true;
    bOkMax = true;
    if(isSignedFloat(sIn))
	{
        fNum = parseFloat(sIn);
        if(f_Min!="")
		{
            fMin = parseFloat(f_Min);
            bOkMin = (fNum>=fMin);
        }
        if(f_Max!="")
		{
            fMax = parseFloat(f_Max);
            bOkMax = (fNum<=fMax)
        }      
		return (bOkMin && bOkMax);
    }
    else 
		return false;
}

//**************************************************************************************************//

function isFloatFormatted(sIn, iTotal, iFrac)
{
    if(isSignedFloat(sIn))
		return false;
    else 
		return false;
}

//**************************************************************************************************//

function isLetter(sIn){return reLetter.test(sIn);}

//**************************************************************************************************//

function isAlpha(sIn){return reAlphabetic.test(sIn);}

//**************************************************************************************************//

function isLetterOrDigit(sIn){return reLetterOrDigit.test(sIn);}

//**************************************************************************************************//

function isAlphaNum(sIn){return reAlphanumeric.test(sIn);}

//**************************************************************************************************//

function isZipCode(sIn){return ((reZipCode.test(sIn)) && (sIn.substring(0,2)!="00"));}

//**************************************************************************************************//

function isEmail(sIn){return reEmail.test(sIn);}
function isNotEmail(sIn){return !reEmail.test(sIn);}

//**************************************************************************************************//

function isPhoneNumber(sIn, sDelim)
{
    var rePhoneNumber;

    rePhoneNumber = new RegExp("^(\\d\\d"+ sDelim + "){4}(\\d\\d)$");
    return rePhoneNumber.test(sIn);
}

//**************************************************************************************************//

function isDay(sIn){return isIntegerInRange(sIn, 1, 31);}

//**************************************************************************************************//

function isMonth(sIn){return isIntegerInRange(sIn, 1, 12);}

//**************************************************************************************************//

function isYear(sIn){return(isInteger(sIn) && ((sIn.length==2) || (sIn.length==4)));}

//**************************************************************************************************//

function isDate(sIn)
{
    var bOK;
    var i, iDay, iMonth, iYear;
    if(reDate.test(sIn))
	{
        bOK = true;
		
        iYear = sIn.substring(0,4);
		iMonth = sIn.substring(5,7);
		iDay = sIn.substring(8,10);
        if(!isDay(iDay)) bOK = false;
		if(!isMonth(iMonth)) bOK = false;
        if(!isYear(iYear)) bOK = false;
        // Les mois 30 jours
        if(iMonth==4 || iMonth==6 || iMonth==9 || iMonth==11)
			if(iDay==31) 
				bOK=false;
        // Le mois de fevrier et son 29eme jour !
        if (iMonth==2)
		{
            if(iDay>29) bOK=false;
            if(iDay==29)
			{
                if( (iYear%4)==0 && ((iYear%100)!=0 || (iYear%400)==0) ) 
					bOK = true;
                else 
					bOK = false;
            }
        }
        return bOK;
    }
    else return false;
}

//**************************************************************************************************//

function isDateInRange(sIn, d_Min, d_Max)
{
    var iIn, iMin, iMax;
    var bOkMin, bOkMax;

    bOkMin = true;
    bOkMax = true;
	
    if(isDate(sIn))
	{
        iIn = Date.parse(sIn);        
		if(d_Min!="")
		{
            iMin = Date.parse(d_Min);
            bOkMin = (iIn>=iMin);
        }
        if(d_Max!="")
		{
            iMax = Date.parse(d_Max);
            bOkMax = (iIn<=iMax)
        }
        return (bOkMin && bOkMax);
    }
    else 
		return false;
}

//**************************************************************************************************//

function isDateSup(date1,date2)
{
	date1=date1.replace("-","/");
	date2=date2.replace("-","/");
	timeDate1 = Date.parse(date1);
	timeDate2 = Date.parse(date2);
	
	if (timeDate1 > timeDate2)
		return true;
	else
		return false;
}

//**************************************************************************************************//

function DateCompare(date1,date2)
{
	timeDate1 = Date.parse(date1);
	timeDate2 = Date.parse(date2);
	
	if (timeDate1 == timeDate2)
		return 0;
	else if (timeDate1 > timeDate2)
		return 1;
	else if (timeDate1 < timeDate2)
		return (-1);		
}

//**************************************************************************************************//

function isHourMinute(sIn, sDelim)
{
    var reHourMinute;
    var iHour, iMinute;

    reHourMinute = new RegExp("^\\d\\d"+ sDelim + "\\d\\d$");
    if(reHourMinute.test(sIn))
	{
        iHour = sIn.substring(0,2);
        iMinute = sIn.substring(3,5);
        return(isIntegerInRange(iHour,0,23) && isIntegerInRange(iMinute,0,59));
    }
    else
		return false;
}

//**************************************************************************************************//

function isHourMinuteSecond(sIn, sDelim)
{
    var reHourMinuteSecond;
    var iHour, iMinute, iSecond;

    reHourMinuteSecond = new RegExp("^\\d\\d"+ sDelim + "\\d\\d" + sDelim + "\\d\\d$");
    if(reHourMinuteSecond.test(sIn))
	{
        iHour = sIn.substring(0,2);
        iMinute = sIn.substring(3,5);
        iSecond = sIn.substring(6,8);
        return(isIntegerInRange(iHour,0,24) && isIntegerInRange(iMinute,0,59)&& isIntegerInRange(iSecond,0,59));
    }
    else
		return false;
}

//**************************************************************************************************//

function isUrl(sIn){return reUrl.test(sIn);}

//**************************************************************************************************//

function isImage(image)
{
	var ret=true;
	var poz = image.lastIndexOf('.');
	var l = image.length;
	var ext = image.substr(poz, l-poz);
	ext=ext.toLowerCase();
	
	if(ext=='.gif' || ext=='.jpg' || ext=='.jpeg' || ext=='.tiff' || ext=='.bmp' || ext=='.png')
		return true;
	else 
		return false;
}

//**************************************************************************************************//

function checkExt(file, extension)
{
	var ret=true;
	var poz = file.lastIndexOf('.');
	var l = file.length;
	var ext = file.substr(poz+1, l-poz);
	ext=ext.toLowerCase();
	
	if(ext == extension.toLowerCase())
		return true;
	else 
		return false;
}

function formReset(form_name)
{
	form = eval("document."+form_name);
	for(var j = 0; j <= form.elements.length-1; j++)
	{
		if(form.elements[j].type!="button" && form.elements[j].type!="submit" && form.elements[j].type!="reset" && form.elements[j].type!="hidden")
			form.elements[j].value='';
		
		if(form.elements[j].type=="radio")
			form.elements[j].checked=false;
	}
}

//********************************** BEGIN VERIFY EACH ELEMENTS OF FORM *******************************//

function formValidate(form_name, falg_confirm)
{
	form = eval("document."+form_name);

	valid_form=1;
	
	//msg_invalid_form="Unul sau mai multe campuri n-au fost completate, sau formatul unui camp n-a fost respectat!";
	//msg_invalid_form="Un ou plusieurs champs obligatoires n a pas ete renseignes, ou un format de champs n a pas ete respecte!";	
	//msg_invalid_form="One or more fields have not been filled or the format of a field has not been respected!";
	
	if(typeof msg_invalid_form == 'undefined')
		msg_invalid_form="One or more fields have not been filled or the format of a field has not been respected!";
	
	msg_first_invalid_elem="";
		
	for(var j = form.elements.length-1; j >= 0; j--)
	{
		if (form.elements[j].set_val && form.elements[j].value!=form.elements[j].set_val)
       	{
   		   	// test valoare element diferita de o valoare setata
			valid_form=0;
			if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
			set_error_form(form,j);
   		}
		else 
		if (form.elements[j].pass_length && form.elements[j].value.length < form.elements[j].pass_length)
       	{
   		   	// test lungime element mai mica decat o valoare setata
			valid_form=0;
			if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
			set_error_form(form,j);
   		}
       	else if (form.elements[j].value=="" && form.elements[j].type!="hidden" && form.elements[j].oblig=='true')
       	{
   		   	// test valaore element diferit de hidden obligatorie
			valid_form=0;
			if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
			set_error_form(form,j);
   		}
		else if (form.elements[j].value=="" && form.elements[j].type=="hidden" && form.elements[j].oblig=='true')
       	{
   		   	// test valoare element hidden obligatorie
			valid_form=0; 
			if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
   		}
		else if (form.elements[j].value=="-1")
       	{
   		   	// test valoare element hidden obligatorie
			valid_form=0; 
			if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
   		}   		
		else if (form.elements[j].value && form.elements[j].type=="select-multiple" && form.elements[j].oblig=='true')
       	{
   		   	// test valoare element select-multiplu obligatorie			
			if(form.elements[j].flag==1)
				no = get_no_elem_selected(form.elements[j],1);
			else
				no = get_no_elem_selected(form.elements[j],0);
			if(no == 0)
			{
				valid_form=0; 
				if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
				set_error_form(form,j);
			}
   		}
       	else if ((form.elements[j].type=="radio") && (form.elements[j].oblig=='true'))
       	{
       		// test valoare elemente tip radio daca este macar unul setat			
			isValid = false
       		groupeRadio = eval(form.elements[j].name)
			for( k=0; k<groupeRadio.length; ++k)
			{
				if( groupeRadio[k].checked)
					isValid = true;
			}			
			if (!isValid)
			{
				valid_form=0;
				if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
				for( k=0; k<groupeRadio.length; ++k)
					groupeRadio[k].style.backgroundColor=elemFormBackColor;
   			}
   		}
   		else 
		{
			if (form.elements[j].equiv && form.elements[j].value!=form.elements[form.elements[j].equiv].value)
			{
				// test valoare element diferita de valoarea altui element (ex.: confirmare parola)
				valid_form=0;
				if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
				set_error_form(form,j); 
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="integer")
			{
				// test valoare element diferit de null daca este integer
				if (!isInteger(form.elements[j].value))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else if(form.elements[j].f_Min && form.elements[j].f_Max)
				{ 
					if(!isFloatInRange(form.elements[j].value, form.elements[j].f_Min, form.elements[j].f_Max))
					{
						valid_form=0;
						if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
						set_error_form(form,j);
					}
					else
						form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;				
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="SignedFloat")
			{
				// test valoare element diferit de null daca este integer
				if (!isSignedFloat(form.elements[j].value))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}			
			else if (form.elements[j].value!="" && form.elements[j].format=="float")
			{ 
				// test valoare element diferit de null daca este integer
				if (!isFloat(form.elements[j].value))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else if(form.elements[j].f_Min && form.elements[j].f_Max)
				{ 
					if(!isFloatInRange(form.elements[j].value, form.elements[j].f_Min, form.elements[j].f_Max))
					{
						valid_form=0;
						if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
						set_error_form(form,j);
					}
					else
						form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="image")
			{
				// test valoare element diferit de null daca este de tip imagine
				if (!isImage(form.elements[j].value))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].hasExt=="true")
			{
				// test extensie fisiere daca sunt egale
				if (!checkExt(form.elements[j].value, form.elements[j].ext))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="email")
			{				
				// test valoare element diferit de null daca este de tip e-mail
				if (!isEmail(form.elements[j].value))
				{
					valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="notemail")
			{				
				// test valoare element diferit de null daca este de tip e-mail
				if (isEmail(form.elements[j].value))
				{
					valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="not_spam")
			{
				var verify = form.elements[j].value.indexOf('@') + form.elements[j].value.indexOf('http:/') + form.elements[j].value.indexOf('www.');
				if (verify!=-3)
				{
					valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="";
					set_error_form(form,j);
				}
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}
			else if (form.elements[j].value!="" && form.elements[j].format=="date")
			{
				// test valoare element daca este de tip data (format an-luna-zi)
				if (!isDate(form.elements[j].value))
				{
		   		   	valid_form=0;
					if(msg_first_invalid_elem == "") msg_first_invalid_elem="Le format de date est inadmissible!";
					set_error_form(form,j);
				}
				else if (form.elements[j].date_sup && form.elements[j].date_sup!="")
				{										
					if (!isDate(form.elements[form.elements[j].date_sup].value))
					{
						valid_form=0;
						if(msg_first_invalid_elem == "") msg_first_invalid_elem="Le format de date est inadmissible!";
						set_error_form(form,j);
					}
					if (!isDateSup(form.elements[j].value, form.elements[form.elements[j].date_sup].value))
					{
						valid_form=0;
						if(msg_first_invalid_elem == "") msg_first_invalid_elem="Date 2 >Date 1!";
						set_error_form(form,j);
					}							
					else
						form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
				}	
				else
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
			}			
			else
			{
				if (form.elements[j].type!='radio' && form.elements[j].type!='checkbox' && form.elements[j].type!='button' && form.elements[j].type!='submit' && form.elements[j].type!='hidden')
				{
					form.elements[j].style.backgroundColor=elemFormDefaultBackColor;
				}
			}
	   	}//end else if 				
	}//end for
	
	if (valid_form == 1)
	{   
		if(falg_confirm==1)
		{
			if (confirm('Confirm ?')) 
				return true; 
			else 
				return false;
		}
		else 
			return true; 
	}
    else
	{
		alert(msg_invalid_form+'\n'+msg_first_invalid_elem);
		return false;
	}
}
//********************************** END VERIFY EACH ELEMENTS OF FORM *******************************//

function set_error_form(form, id_element)
{
	if (form.elements[id_element].type!='radio' && form.elements[id_element].type!='checkbox')
	{
		form.elements[id_element].style.backgroundColor=elemFormBackColor;
	}
	
	if (form.elements[id_element].disabled != true && form.elements[id_element].type!="hidden" )
	{
		form.elements[id_element].focus()
	}
}

//**************************************************************************************************//

function focus_form(i)
{  
	if (document.forms[i]!=undefined)
	{
		for(var j = 0 ; j<document.forms[i].elements.length; j++)
		{	
	        if (document.forms[i].elements[j].type!="hidden")
	        {
	        	document.forms[i].elements[j].focus();			
	        	break;
	        }
		}
	}
}

//**************************************************************************************************//

function focus_element(i,j)
{  
   	document.forms[i].elements[j].focus();			
}

//**************************************************************************************************//

function date_iso_to_fr(date_us)
{
	tab_date = date_us.split("/");
	iYear    = tab_date[2];
	iMonth  = tab_date[1];
	iDay   = tab_date[0];
	
	return (iMonth+"/"+iDay+"/"+iYear);
}

//**************************************************************************************************//

function disable(element)
{
	element.disabled = true
}

//**************************************************************************************************//

function enable(element)
{
	element.disabled = false
}

//**************************************************************************************************//
function confirmare()
{
	if (confirm('Confirm ?')) 
		return true; 
	else 
		return false;
}