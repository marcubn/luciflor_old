<?php

/**
 * Abstract class for admin modules operations
 * 
 * multilanguage edit bug fixed
 * 
 * @version 2.2
 * @author: psorin
 * */
class adminModule{

    public $pagingAction = "page_list";
    public $idName 		= null;
    public $orderBy     = "";
    public $langName 	= null;
    public $methodsMap  = null;
    public $canEdit 	= true;
    public $canAdd 		= true;
    
    /**
     * Constructor
     * 
     * @param string $action
     * @return boolean
     */
    function adminModule($action=""){
        
        $this->loadTable();
        
        $ok = false;
        if ( isset($this->methodsMap) && is_array($this->methodsMap) && count($this->methodsMap)>0  && 
             ( isset($this->methodsMap[$action]) ||  in_array($action,$this->methodsMap) ) 
        ){
            if(isset($this->methodsMap[$action]))
                $method_action = $this->methodsMap[$action];
            else
                $method_action = $action;
            
            if ( method_exists($this, $method_action) ){
                
                $this->$method_action();
                return true;
            }
        }
        
        switch($action){
            
            default:
            case "page_list":
                $this->initFields();
                $this->page_list();
                $ok = true;
            break;
      		
            case "page_act":
                $this->initFields();
                $this->page_act();
                $ok = true;
            break;
      		 	
            case "add_upd":
                $this->add_upd();
                $ok = true;
            break;
      		
            case "page_export":		
                $this->page_export();
                $ok = true;
            break;
			
            case "ajax_table":
                $this->ajax_table();
                exit;
            break;
			
            case "update_order":
                $this->update_order();
                $ok = true;
            break;
			
            case "view_table_log":
                $this->view_table_log();
                $ok = true;
            break;
			
            case "delete_items":
                $this->delete_items();
                $ok = true;
            break;
			
            case "switch":
                
                if(isset($_GET[$this->idName]) && $_GET[$this->idName]!='' && isset($_GET['fieldName']) && $_GET['fieldName']!=''){

                    systemMessage::addMessage("Status Switched!");
                    $db = SDatabase::getInstance();
                    $fieldname = filter_var($_GET['fieldName'], FILTER_SANITIZE_STRING);
                    if((int)$_GET[$this->idName]!=0)
                    {
                        $id = filter_var($_GET[$this->idName], FILTER_SANITIZE_NUMBER_INT);
                        $q="UPDATE $this->tableName SET $fieldname=($fieldname+1)%2 WHERE $this->idName='$id'";
                        $db->setQuery($q);
                        $db->query();
                    }
                }
                redirect("index.php?obj={$_GET['obj']}&action=page_list");
            break;
        }
        
        return $ok;
    
    }
    
    /**
     * Abstract method to load table Object
     * Default inits table object
     * Overide with table custom
     * 
     * @since:1.0
     * */
    function loadTable(){

        require_once ( LIB_DIR."db/db_table.php" );
        $this->table = STable::tableInit($this->tableName,$this->idName,$this->langName);

    }

    /**
     * Add/Update operation
     * 
     * @access: public
     * @return: void
     * @author: PSorin (sorin@frankgroup.ro)
    */
    function add_upd()
    {
        $db = SDatabase::getInstance();
        
        /**
         * Introduced because logic fails me most of the times I'm tired.
         * Edit: most of the times
         */
        $edit = (getFromRequest($_POST,"act")=="upd")?true:false;
        $lang = null;
        
        $fields = null;
        if( isset($_POST[$this->idName]) && $_POST[$this->idName]!="" ){
        	
        	$dummy = clone $this->table;
        	if(isset($dummy->modified)) unset($dummy->modified);
        	if(isset($dummy->modified_by)) unset($dummy->modified_by);
        	$fields = array_keys($this->table->getProperties());
            /**
             * This is for:
             * -> to load hidden fields and so not to loose them on save
             * -> fot the other reason I don't remember and didn't commented
             */
            $this->table->load($_POST[$this->idName], getFromRequest($_POST, "lang", null));
        }
        
        $old_data = $this->table->getProperties();
        
        $upload_types = $this->_getFieldsOfType("upload");
        if(count($upload_types)){
        	foreach($upload_types as $fU){
        		$field_name = $fU->name;
        		$upload_dir = $fU->params["dir"];
        		$extensions = $fU->params["extensions"];
		        // Begin Upload File thumbnail
		        $new_upload = new FUpload($field_name, $upload_dir);
		        $new_upload->fileTypeAccepted = $extensions;
		        $new_upload->cms_do_upload($this);
		        if( !$new_upload->success ){
		            systemMessage::addMessage(implode("<br />",$new_upload->error_msg_queue) );
		            unset($this->table->{$field_name});
		        }
        		
        	}
        }
        
        $switch_types = $this->_getFieldsOfType("switch");
        if(count($switch_types)>0)
            foreach($switch_types as $field)
                if(!isset($_POST[$field->name]))
                    $_POST[$field->name] = 0;
        /**
         * because flagName still exists
         */
        if(!isset($_POST[$this->flagName]))
            $_POST[$this->flagName] = 0;
        
        $this->table->bind($_POST);
        
        $ok = $this->table->check();
        if( $ok ){
            
            if($edit){
                
                if(isset($this->table->modified)){
                    $this->table->modified = date($db->getDateFormat());
                }
                
                if(isset($this->table->modified_by)){
                    $this->table->modified_by = $_SESSION[SESS_IDX]["UL"]["user_id"];
                }

            }
            /**
             * Global available params field
             */
            if(isset($this->table->params) && is_array($this->table->params)){
                $this->table->params = json_encode($this->table->params);
            }
          
            /**
             * Global available seo field
             */
            if(isset($this->table->seo) && is_array($this->table->seo)){
                $this->table->seo = json_encode($this->table->seo);
            }
          
            
            $ok = $this->table->store(true, !$edit);
            $id = $this->table->{$this->idName};
            if(isset($this->langName))
            	$lang = $this->table->{$this->langName};
        }
        
        $table_data = $this->table->getProperties();
        
        $edited_fields = array();
        foreach($fields as $k){
            
            $from = $old_data[$k];
            $to   = $table_data[$k];
            if( $from != $to ){
                if( trim($from) =="" && trim($to)!="" ){
                    $edited_fields[] = " Value for <strong>{$k}</strong> was added ('{$to}'); ";
                }elseif( trim($to) =="" && trim($from) !="" ){
                    $edited_fields[] = " Value for <strong>{$k}</strong> was removed; ";
                }else{
                    $edited_fields[] = " Value for <strong>{$k}</strong> was edited from '{$from}' into '{$to}' ";
                }
                
            }
        }
        

        if( $ok===true ){
	        /**
	         * Save updates log
	         */
	        if(count($edited_fields)){
	        	$userid = $_SESSION[SESS_IDX]["UL"]["user_id"];
	            $edited_fields_comment = implode("<br />", $edited_fields);
	            $edited_fields_comment = "Following fields changed:<br /> ".$edited_fields_comment;
	            $edited_fields_comment = $db->getEscaped($edited_fields_comment);
	            $q="insert into db_table_logs set otable='{$this->tableName}', oid = '{$id}', data=NOW(), uid = '{$userid}', descr='{$edited_fields_comment}'";
	            $db->query($q);
	            
	        }
	        	
            systemMessage::addMessage("Saved");
        }
        else
            systemMessage::addMessage(implode("; ",$ok));

        /**
         * Redirectin' time
         */
        if(isset($_POST['tip_pagina']) && $_POST['tip_pagina']=='modal')
        {            
            $link_to = $_SERVER['HTTP_REFERER']."&modal=close";
        }
        else{
            if( isset($id) && isset($_POST['backToEditForm']) ){
                $link_to="index.php?obj={$this->moduleName}&action=page_act&{$this->idName}={$id}&act=upd";
                $link_to .= ( isset($this->langName) && isset($lang) )?"&{$this->langName}={$lang}":"";
                $link_to .= ( isset($_POST['parent_id']) )?"&parent_id={$_POST['parent_id']}":"";
            }else {
                $link_to = "index.php?obj={$this->moduleName}&action=page_list";
                $link_to .= ( isset($_POST['parent_id']) )?"&parent_id={$_POST['parent_id']}":"";
            }
        }
            
        redirect($link_to);
    }
    
    /**
     * Page act (add/edit form)
     *
     * @access: public
     * @return: null
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function page_act()
    {
        global $smarty;
        $db = SDatabase::getInstance();

        $form_act = array();
        $lng = 0;
        if(isset($this->langName))
            $lng = (int)getFromRequest($_GET, $this->langName,1);
        
        if(isset($this->uplphoto) && $this->uplphoto!="")
            $smarty->assign("uplphoto", $this->uplphoto);
        if(isset($this->modal) && $this->modal!="")
            $smarty->assign("modal", $this->modal);
        if(isset($_GET[$this->idName]) && $_GET[$this->idName]!=''){

            $id = getFromRequest($_GET, $this->idName);
            $q="SELECT * FROM $this->tableName WHERE $this->idName = $id";
            if(isset($this->langName) && $lng>0)
               $q .= " AND `{$this->langName}` = $lng";
            $db->setQuery($q);
            $form_act = $db->loadAssoc();
            if(isset($form_act[$this->idName])){
            	$form_act["act"] = "upd";
            }else{
            	$form_act[$this->idName] = $id;
            	$form_act["act"] = "add";
            }
            
            
            if(isset($form_act['params']))
            {
                $params = json_decode($form_act['params'], true);    
                $smarty->assign("params", $params);
            }
            
            if(isset($form_act['seo']))
            {
                $seo = json_decode($form_act['seo'], true);    
                $smarty->assign("seo", $seo);
            }

        }else{
            $form_act = $this->table->getProperties();
            $form_act["act"] = "add";
            if(isset($_POST) && !empty($_POST))
                $form_act=$_POST;		

        }
        
        $columns = $this->_renderFormFields($form_act);
        $smarty->assign("form_fields", $columns);
        
        if(!isset($this->tplName) || !file_exists(TEMPLATES_DIR."admin/{$this->tplName}_act.tpl")){
            objInitVar($this, "admin/standard/standard_act.tpl", $this->moduleName, "page_act", "", "", "");
            
        }else{
            
            objInitVar($this, "admin/{$this->tplName}_act.tpl", $this->moduleName, "page_act", "", "", "");
        }
        

        if($this->priorityName!=""){
            $maxOrder = $minOrder = null;
            
            $minOrder = $db->getMin($this->tableName, $this->priorityName);
            $maxOrder = $db->getMax($this->tableName, $this->priorityName);

            if ($maxOrder!=0 || $minOrder!=0) {
                $maxOrder++;
                $minOrder--;
            }
            if(!($maxOrder))
                $maxOrder=1;

            $smarty->assign("minOrder", $minOrder);
            $smarty->assign("maxOrder", $maxOrder);
        }

        //assign variables					
        $smarty->assign("form_act", htmlArrayFilter($form_act));
        $smarty->assign("idName", $this->idName);

        //display smarty template
        
        $smarty->assign("moduleTitle", $this->moduleTitle);
        $smarty->display($this->tplName);		
    }

    /**
     * Page list/edit
     *
     * @access: public
     * @return: null
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function page_list()
    {
        global $smarty;

        if(!isset($this->tplName) || !file_exists(TEMPLATES_DIR."admin/{$this->tplName}_list.tpl")){
            
            objInitVar($this, "admin/standard/standard_list.tpl", $this->moduleName, "page_list", "", "", "");

            //delete multiple items
            if(isset($_POST["act"]) && $_POST["act"]=="delete")
                $this->deleteMItems();

            if(isset($this->modal) && $this->modal!="")
                $smarty->assign("modal", $this->modal);
            
            $columns = $this->_orderFields("list");
            $smarty->assign("list_fields", $columns);

			$smarty->assign("canExport", (isset($this->exportParams) && count($this->exportParams)>0)?true:false);
			$smarty->assign("canEdit", (isset($this->canEdit))?$this->canEdit:true);
			$smarty->assign("canAdd", (isset($this->canAdd))?$this->canAdd:true);
            $smarty->assign("moduleTitle", $this->moduleTitle);
            $smarty->display($this->tplName);
            
        }else{
            
            objInitVar($this, "admin/{$this->tplName}_list.tpl", $this->moduleName, "page_list", "", "", "");

            //delete multiple items
            if(isset($_POST["act"]) && $_POST["act"]=="delete")
                    $this->deleteMItems();

            $smarty->assign("recList", $this->getList());
            $smarty->assign("moduleSession", $_SESSION[SESS_IDX][$this->moduleName]);

			$smarty->assign("canExport", (isset($this->exportParams) && count($this->exportParams)>0)?true:false);
			$smarty->assign("canEdit", (isset($this->canEdit))?$this->canEdit:true);
			$smarty->assign("canAdd", (isset($this->canAdd))?$this->canAdd:true);
			if(isset($this->moduleTitle))
                $smarty->assign("moduleTitle", $this->moduleTitle);
            $smarty->display($this->tplName);
            
        }
    }
    
    /**
     * adminModule::page_export()
     * Export INTO XLS
     * $exportParams must be defined
     * 
     * @return void
     */
    function page_export(){
        error_reporting(0);
        if( isset($this->exportParams) && count($this->exportParams) > 0 ){
            
            require_once( LIB_DIR ."/xls/xls_ext.php");
            
            $file_name = $this->moduleName."_".time().".xls";
            $sql_names = array_keys($this->exportParams);
            XLSHelper::xls_download($this->getItems(), $sql_names, $this->exportParams, "", $file_name);
        
        }
        
    }


/**
 * Method to display table history log
 * 
 */
	function view_table_log(){
		global $smarty;
		$db = SDatabase::getInstance();
		$id = (int)getFromRequest($_GET, "oid");
		$db->setQuery("select * from db_table_logs where otable='{$this->tableName}' and oid='{$id}' order by data desc");
		$list = $db->loadObjectList();
		$smarty->assign("data", $list);
		$smarty->display("admin/standard/table_log.tpl");
	}
    /**
     * Get Items List
     * Not to be confused with getList (soon to be deprecated)
     *
     * @access: public
     * @return: array
    */
    function getItems($parent = null)
    {
        $db = &SDatabase::getInstance();
        
        $q=$this->getQuery($parent);
        
        $order = '';

        if ( isset($_GET['order']) && count($_GET['order']) ) {
            $orderBy = array();
            $dtColumns = array_filter($this->columnsList, function($var){ return isset($var->list_order);});

            for ( $i=0, $ien=count($_GET['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($_GET['order'][$i]['column']);
                $requestColumn = $_GET['columns'][$columnIdx];

                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['orderable'] == 'true' ) {
                        $dir = $request['order'][$i]['dir'] === 'asc' ?
                                'ASC' :
                                'DESC';

                        $orderBy[] = '`'.$column['db'].'` '.$dir;
                }
            }

            $order = 'ORDER BY '.implode(', ', $orderBy);
        }
        
        $db->setQuery($q);
        $result = $db->loadAssocList();
        
        $list = array(); $i=-1;
        if(count($result)>0)
        {
            foreach($result as $key=>$record)
            {
                $i++;
                $list[$i]=$record;
                $list[$i]["del_op"] = $this->verifyDel($record[$this->idName]);
                if(isset($record["langkeys"]))
                    $list[$i]["lkeys"]  = @explode(",", $record["langkeys"]);
            }   
        }
		
        return $list;
    }
    
    /**
     * Update order
     *
     * @access: public
     * @return: null
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function update_order()
    {		
        $db=SDatabase::getInstance();
        if (isset($_GET[$this->idName]) && isset($_GET['act']))
        {
            $q="SELECT * FROM $this->tableName WHERE $this->idName = '{$_GET[$this->idName]}' LIMIT 0,1";
            $db->setQuery($q);
            $item=$db->loadAssoc();

            $sqlWhere="";
            if($this->priorityFilter!="")
                $sqlWhere=" AND {$this->priorityFilter}='{$item[$this->priorityFilter]}' ";

            if( $item[$this->priorityName]==0 ){
                $q="select count($this->idName) as nr from $this->tableName where $this->priorityName=0 $sqlWhere";
                $db->setQuery($q);
                $intermed=$db->loadAssoc();
                $nr_zero=$intermed['nr'];
                
                $q="select count($this->idName) as nr from $this->tableName where 1 $sqlWhere";
                $db->setQuery($q);
                $intermed=$db->loadAssoc();
                $nr_all=$intermed['nr'];
                
                if( $nr_zero==$nr_all ){
                    $q="update {$this->tableName} set $this->priorityName = $this->idName";
                    $db->setQuery($q);
                    $db->query();
                }

            }

            if ($_GET['act']=="up")
            {
                $q="SELECT {$this->idName}, {$this->priorityName} FROM {$this->tableName} WHERE {$this->priorityName}<{$item[$this->priorityName]} {$sqlWhere} ORDER BY {$this->priorityName} DESC LIMIT 0,1";
                $db->setQuery($q);
                $exists=$db->loadAssoc();

                if ($exists)
                {
                    $intermed=$exists;
                    $orderAux = $intermed[$this->priorityName];
                    $orderAuxId = $intermed[$this->idName];

                    $q="UPDATE {$this->tableName} SET {$this->priorityName}={$item[$this->priorityName]} WHERE {$this->idName}={$orderAuxId}";
                    $db->setQuery($q);
                    $db->query();

                    $q="UPDATE {$this->tableName} SET {$this->priorityName}={$orderAux} WHERE {$this->idName}={$item[$this->idName]}";
                    $db->setQuery($q);
                    $db->query();
                }
            }
            elseif ($_GET['act']=="down")
            {
                $q="SELECT {$this->idName}, {$this->priorityName} FROM {$this->tableName} WHERE {$this->priorityName}>{$item[$this->priorityName]} {$sqlWhere} ORDER BY {$this->priorityName} ASC LIMIT 0,1";
                $db->setQuery($q);
                $exists=$db->loadAssoc();
                if ($exists)
                {
                    $intermed=$exists;
                    $orderAux = $intermed[$this->priorityName];
                    $orderAuxId = $intermed[$this->idName];

                    $q="UPDATE {$this->tableName} SET {$this->priorityName}={$item[$this->priorityName]} WHERE {$this->idName}={$orderAuxId}";
                    $db->setQuery($q);
                    $db->query();

                    $q="UPDATE {$this->tableName} SET {$this->priorityName}={$orderAux} WHERE {$this->idName}={$item[$this->idName]}";
                    $db->setQuery($q);
                    $db->query();
                }
            }
        }
        redirect("index.php?obj={$_GET['obj']}&action=page_list");
    }

    

    /**
     * Verify item for delete operation
     *
     * @access: public
     * @return: bool
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function verifyDel($id)
    {
        return true;
    }
	
    /**
     * Delete multiple items
     *
     * @access: public
     * @return: null
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function deleteMItems()
    {
        if(isset($_POST["act"]) && $_POST["act"]=="delete")
            if(isset($_POST["ids"]))
                foreach($_POST["ids"] as $k=>$v)
                    $this->deleteItem($v);
    }
    
    function delete_items() {
        if(isset($_GET['ids']) && $_GET['ids']!="")
        {
            $ids = explode(",", $_GET['ids']);
        }
        foreach($ids as $k=>$v)
            $this->deleteItem($v);
        redirect("index.php?obj={$_GET["obj"]}&action=page_list");
    }
	
    /**
     * Delete item
     *
     * @access: public
     * @return: null
     * @author: PSorin (sorinporumboiu@gmail.com)
    */
    function deleteItem($id)
    {	
        $db = &SDatabase::getInstance();
        if( $this->verifyDel($id) ){
            //delete item
            //_sqlDel($this->tableName, $this->idName, $id);
            $q="DELETE FROM {$this->tableName} WHERE $this->idName = $id";
            $db->setQuery($q);
            $db->query();
            
            $upl = new SUPLPhotoHelper();
            $upl->delete_item_pictures($this->tableName, $this->idName);
        }
    }
    
    /**
     * Abstract method
     * To be defined in child class
     */
    function initFields(){
        
    }
    
    /**
     * Outputs a json for datatables
     * 
     * @return json
     */
    function ajax_table() {
        /**
         * Because it's ajax
         */
        error_reporting(0);
        $this->initFields($_POST['parent']);
        $columns = $this->_orderFields("list");
        
        $list = $this->getItems($_POST['parent']);

        $json_result = array();
        $json_result["draw"]=1;
        $db = SDatabase::getInstance();
        $db->query("SELECT * FROM $this->tableName");
        $json_result["recordsTotal"]    = $db->getNumRows();
        $json_result["recordsFiltered"] = $db->getNumRows();
        $editModal = false;
        
        foreach($list as $key => $item) {
            
            foreach($columns as $col){
                
                if(isset($col->params['EditType']) && $col->params['EditType']=='modal')
                {
                    $editModal = true;
                }
                $json_result['data'][$key][] = $col->list_html($item, $this, $col->type);
            }
            // Standard Edit Button
            if($this->canEdit)
            {
                if($editModal)
                {
                    //$json_result['data'][$key][] = '<a href="index.php?obj='.$this->moduleName.'&action=page_act&'.$this->editURL.$this->idName.'='.$item[$this->idName].'&act=upd&tip_pagina=modal" data-toggle="modal" data-target="#myModal" class="btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button></a><!-- Modal --><div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel">Modal title</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
                    $json_result['data'][$key][] = '<a onclick=open_dialog("index.php?obj='.$this->moduleName.'&action=page_act&'.$this->editURL.$this->idName.'='.$item[$this->idName].'&act=upd&tip_pagina=modal") class="btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button></a>';  
                }
                else{
                    if(isset($item["parent_id"])) {
                        $json_result['data'][$key][] = '<a href="index.php?obj='.$this->moduleName.'&action=page_act&'.$this->idName.'='.$item[$this->idName].'&act=upd&parent_id='.$item["parent_id"].'" class="btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button></a>';
                    } else {
                        $json_result['data'][$key][] = '<a href="index.php?obj='.$this->moduleName.'&action=page_act&'.$this->idName.'='.$item[$this->idName].'&act=upd" class="btn"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button></a>';
                    }
                }
            }
            // Standard Delete button
            $json_result['data'][$key][] = '<input class="offset3 jsx_checkbox" type="checkbox" name="ids[]" value="'.$item[$this->idName].'" onClick="verifyRowChecked(document.form_list, \'ids\')" class="bd0">';
            
        }
        echo json_encode($json_result);
        
    }
    
    function _ajax_table() {

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = $this->columns;
        
        // SQL server connection information
        $sql_details = array(
            'user' => DB_USER,
            'pass' => DB_PASS,
            'db'   => DB_NAME,
            'host' => DB_HOST
        );
        

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */
        require_once(LIB_DIR . "/admin/ssp.class.php" );
        
        $rezultat = SSP::simple( $_GET, $sql_details, $this->tableName, $this->idName, $columns );
        
        foreach($rezultat['data'] as $key=>$item) {
            $rezultat['data'][$key][0]='<input class="offset3" type="checkbox" name="ids[]" value="'.$item[3].'" onClick="verifyRowChecked(document.form_list, \'ids\')" class="bd0">';
            $status = '<a href="index.php?obj='.$_GET['obj'].'&action=switch&fieldName='.$this->flagName.'&'.$this->idName.'='.$item[3].'" title="Title Switch" class="btn">';
            if($item[1]==1)
            {
                $status .= '<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>';   
            }
            else
            {
                $status .= '<button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>';   
            }
            $status.='</a>';
            $rezultat['data'][$key][1]=$status;
            $rezultat['data'][$key][2]='<a href="index.php?obj='.$_GET['obj'].'&action=page_act&'.$this->idName.'='.$item[3].'&act=upd" class="btn">
                                            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
                                        </a>';
        }
            
        echo "<Pre>";
        var_dump($rezultat);
        exit;
        echo json_encode(
            $rezultat
            //SSP::simple( $_GET, $sql_details, $this->tableName, $this->idName, $columns )
        );
        exit;
    }
 
    function getQuery()
    {
        $sqLimit = "";
        if( isset($_GET['start']) && $_GET['length'] != -1 ) {
            $sqLimit = " LIMIT ".intval($_GET['start']).", ".intval($_GET['length']);
        }
        
        $Qlang="";
        if(array_key_exists("lang", get_object_vars($this->table)))
        {
            $Qlang = ",GROUP_CONCAT(lang) as langkeys";
        }
        if($this->orderBy!="")
            $this->orderBy = "ORDER BY ".$this->orderBy;
        $q="SELECT
                {$this->tableName}.*
                {$Qlang}
            FROM 
                {$this->tableName} 
            GROUP BY 
                {$this->idName}
            {$this->orderBy}
            {$sqLimit}";
               
        return $q;
    }
    
    /**
     * Prepares fields for edit form
     * 
     * @return array
     */
    private function _renderFormFields($form_act=array()){
        
        $columns = $this->_orderFields("act");
        if(count($columns)){
            foreach($columns as &$col){
                switch ($col->type) {
                    case "list":
                        if(isset($col->params) && isset($col->params["query"])){
                            $db = SDatabase::getInstance();
                            $db->setQuery($col->params["query"]);
                            $col->options = $db->loadObjectList();
                        }
                        
                        break;
                    
                    case "modified_by":
                        if(isset($form_act['modified_by'])){
                            $q="SELECT user_userid FROM user WHERE user_id = ".$form_act['modified_by'];
                            $q=$db->setQuery($q);
                            $modified_by = $db->loadAssoc();
                            $col->value = $modified_by['user_userid'];
                        }
                        break;

                    default:
                        break;
                }
            }
        }
        return $columns;
    }
    /**
     * Orders field list for list or act action
     *  
     * @param string $action
     * @return array
     */
    private function _orderFields($action="list"){
        
        if($action=="list"){
            /**
             * Get columns that have a list_order.
             * If list_order is null then column will not be in list!
             */
             $columns = array_filter($this->columnsList, function($var){ return isset($var->list_order);});
             /**
              * Orders the columns order based on list_order
              */
             usort($columns, function($a, $b) { return $a->list_order - $b->list_order;});
        }else{
            /**
             * Get columns that have a list_order.
             * If list_order is null then column will not be in list!
             */
             $columns = array_filter($this->columnsList, function($var){ return isset($var->act_order);});
             /**
              * Orders the columns order based on list_order
              */
             usort($columns, function($a, $b) { return $a->act_order - $b->act_order;});
        }
        
        
        return $columns;
        
    }
    
    /**
     * Returns fields of parameter type
     */
    public function _getFieldsOfType($type){
        if(!isset($this->columnsList))
            $this->initFields();
        return array_filter($this->columnsList, function($var) use ($type) { return ($var->type==$type);});
    }
}

/**
 * Admin Field object
 */
class adminField{
    
    /**
     * 
     * @param string $name The db name of the column
     * @param string $label The label of the column
     * @param string $type The input type of the column (text, textarea, editor, int, checkbox, radio, etc )
     * @param int $list_order
     * @param int $act_order
     * @param array $params
     */
    function adminField($name, $label, $type, $list_order, $act_order, $params=array()){
        $this->name         = $name;
        $this->label        = $label;
        $this->type         = $type;
        $this->list_order   = $list_order;
        $this->act_order    = $act_order;
        $this->params       = $params;
    }
    
    /**
     * Returns a field list html
     */
    function list_html($data, $module, $type=""){
        
        $SLang =  SLanguage::getInstance();
        $db =  SDatabase::getInstance();
        
        //var_dump($data);exit;
        //switch ($col->type) {
        switch ($type) {
            case "switch":

                $status = '<a href="index.php?obj='.$module->moduleName.'&action=switch&fieldName='.$this->name.'&'.$module->idName.'='.$data[$module->idName].'" title="Title Switch" class="btn">';
                if($data[$this->name]==1)
                {
                    $status .= '<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>';   
                }
                else
                {
                    $status .= '<button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-lock"></span></button>';   
                }
                $status.='</a>';
                $html = $status;

                break;

            case "order":

                $order = '<div class="btn-group">
                                    <button onclick="window.location=\'index.php?obj='.$module->moduleName.'&action=update_order&act=up&'.$module->idName.'='.$data[$module->idName].'\'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                    <button onclick="window.location=\'index.php?obj='.$module->moduleName.'&action=update_order&act=down&'.$module->idName.'='.$data[$module->idName].'\'" type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                </div>';
                $html = $order;

                break;
            
            case "lang":
                
                $languages = $SLang->loadLanguages("language_id");

                $flags_arr = array();
                foreach($languages as $lang){
                    if(in_array($lang->language_id, $data['lkeys']))
                        $flags_arr[] = '<a class="btn" href="index.php?obj='.$module->moduleName.'&action=page_act&'.$module->idName.'='.$data[$module->idName].'&act=upd&lang='.$lang->language_id.'"><img src="/img/admin/flags/'.$lang->language_flag.'" title="Edit '.$lang->language_name.'" style="vertical-align:middle;" /></a> ';
                    else
                        $flags_arr[] = '<a class="btn" href="index.php?obj='.$module->moduleName.'&action=page_act&'.$module->idName.'='.$data[$module->idName].'&act=add&lang='.$lang->language_id.'"><img src="/img/admin/flags/'.$lang->language_flag.'" title="Add '.$lang->language_name.'" style="opacity: 0.3;filter: alpha(opacity=30);" /></a> ';
                }
                $html = implode("", $flags_arr);

            break;
            
            case "modified_by":
                
                if((int)$data["modified_by"]>0){
                	$db->setQuery("select user_userid from user where user_id = '{$data["modified_by"]}'");
                	$user = $db->loadAssoc();
                	if(isset($user["user_userid"]))
                		$html = $user["user_userid"];
            		else
            			$html = "-"; 
                }else{
                	$html = "-";	
                }
                

            break;

            default:
                $html = $data[$this->name];
                break;
        }

        return $html;
    }
    
    /**
     * validates field input
     * @todo
     */
    function check(){
        
    }
    
    /**
     * fetches from $_POST or $_FILES the data content
     */
    function bind(){
        
    }
    
    
}