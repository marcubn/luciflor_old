//****************************************************************************************//
/**
* Delete item from a select
*
* @parametter: obj = name of element object (select)
*
* @author: FlorinC - Sitex
* @date creation: 05.05.04
*/
function deleteItems(obj)
{
	for (i=0; i < parseInt(obj.options.length); i++) 
		obj.options[i]=null;
	if(parseInt(obj.options.length) > 0)
		deleteItems(obj);
}

/**
* Populate object select
*
* @parametter: obj = name of object (select for populate)
* @parametter: array_populate = array with elements for populate
* @parametter: item_selected = the element that is to be selected
* @parametter: text_head_option = first element of select object  
*
* @author: FlorinC - Sitex
* @date creation: 05.05.04
*/
function populate(obj, array_populate, item_selected, text_head_option) 
{   
	//===>delete old select obj
	deleteItems(obj);
	//<===
	var i=0;	
	//===>init first options
	if(text_head_option != "")
	{
		var head_option = new Option(text_head_option, "");
		obj.options[0]=head_option;
		i++;
	}
	//<===

	//===> populate obj
	var key;	
	for (key in array_populate) 
	{
		var option = new Option(array_populate[key], key);
		obj.options[i]=option;		
		if(key == parseInt(item_selected))
			obj.options[i].selected=true;
		i++;
	}
	//<===		
}

/**
* Populate multiple
*
* @parametter: father_select = name of object (select) father
* @parametter: son_select = name of obkect (select) son
* @parametter: my_array = bidimensional array father/son
* @parametter: text_head_option = first element of select object 
* @parametter: vect_items_selected
*
* @author: FlorinC - Sitex
* @date creation: 05.05.04
*/
function populate_multiple(father_select, son_select, my_array, text_head_option, vect_items_selected) 
{   
	//alert()
	var son_select_length = son_select.options.length;
	var father_select_length = father_select.options.length;
	
	deleteItems(son_select);
	
	//===> empty son select
	for (var i=0; i < son_select_length; i++) 
		son_select.options[i]=null;
	//<===  
	
	var i=0;
	//===>init first options
	if(text_head_option != "")
	{
		var head_option = new Option(text_head_option, "");
		son_select.options[0]=head_option;
		i++;
	}
	//<===

	var key;
	for (var k=0; k < father_select_length; k++)
	{
		if(father_select.options[k].selected) 
		{
			id_show=father_select.options[k].value;
			for (key in my_array[id_show]) 
			{				
				var opt = new Option(my_array[id_show][key], key);
				son_select.options[i] = opt;
				
				if(vect_items_selected != -1 && check_in_array(key, vect_items_selected))
					son_select.options[i].selected=true;
				i++;
			}
		}
	}
	son_select.options.length=i;
}

function copyContentBetweenSelectElem(from, to, mode)
{
	for(i=0; i < parseInt(from.length); i++)
		if(from.options[i].selected==true || mode) 
		{
			var opt=new Option(from.options[i].text, from.options[i].value);
			to.options[to.length]=opt;
			from.options[i]=null;
			i--;
		}
}

function copyConcatContentFromSelectElemToTextElem(from, to, separator) 
{	
	to.value='';
	for(i=0; i < parseInt(from.length); i++)
	{
		if(to.value!='')
			to.value+=' ';
		to.value+=from.options[i].value;
	}
}

function selectItems(obj) 
{   		
	var length_obj = obj.options.length;
	for(i=0;i<length_obj;i++)
		obj.options[i].selected=true;
}

function unselectItems(obj) 
{   		
	var length_obj = obj.options.length;
	for(i=0;i<length_obj;i++)
		obj.options[i].selected=false;
}

/**
* Get no. elements from a multple select
*
* @parameter:  obj = select object
* @parameter:  flag = 1 - count only items with value !="" ; 0 - count all items
* @author: FlorinC - Sitex
* @date creation: 05.05.04
*/
function get_no_elem_selected(obj,flag) 
{   		
	var length_obj = obj.options.length;
	var count=0;
	if(length_obj > 0)
	{
		if(flag==1)
		{
			for(i=0;i<length_obj;i++)
			{			
				if(obj.options[i].value!="" && obj.options[i].selected==true)
					count++;			
			}
		}
		else
		{
			for(i=0;i<length_obj;i++)
			{			
				if(obj.options[i].selected==true)
					count++;			
			}			
		}
	}
	
	return count;
}