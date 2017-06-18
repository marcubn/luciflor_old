<?php 
/**
 * Abstract database table layer
 * Provides 
 *   Bind From POST, GET
 *   Store ( insert / update )
 *   Get Row Object
 * 
 * 
 * 2.0 Introduces _lang parameter when PK composed as id , lang
 *     Ex translation tables
 * 2.1 Mysqli support
 *
 * @version: 2.1
 **/
 class STable{
    
    var $_key   = null; // ID key name
    var $_lang  = null; // Language composit of PK. Default null otherwise the language column name
    var $_table = null; // Table name
    var $_query = null; // To temporary store query
    
    function STable( $table , $key, $lang = null ){
        $this->_table = $table;
        $this->_key = $key;
        $this->_lang = $lang;
    }
    
    /**
     * Returns Instance of Table with DB actual fields as properties
     * 
     **/
     public static function &tableInit( $table , $key , $lang=null ){
        $tObj = new STable( $table , $key , $lang );
        $db = & SDatabase::getInstance();
        $fields = $db->getTableFields( $table );
        $colums = array_keys($fields[$table]);
        foreach( $colums as $field){
            $tObj->$field = null;
        }
        return $tObj;
     }
     
    
    function getProperties( $public = true )
    {
        $vars  = get_object_vars($this);

        if ($public){
        	$columns = array_keys($vars);
            foreach ($columns as $key)
            {
                if ('_' == substr($key, 0, 1)) {
                        unset($vars[$key]);
                }
            }
        }

        return $vars;
    }
    
    /**
     * 
     * Fills From $from array, object the database objects properties
     *  
     */
    function bind( $from, $ignore=array() ){
		
        $fromArray	= is_array( $from );
        $fromObject	= is_object( $from );

        if (!$fromArray && !$fromObject){
            die( get_class( $this ).'::bind failed. Invalid from argument' );
        }
        
        if (!is_array( $ignore )) {
                $ignore = explode( ' ', $ignore );
        }
        
        $keys = array_keys($this->getProperties());
        foreach ($keys as $k){
            // internal attributes of an object are ignored
            if (!in_array( $k, $ignore ))
            {
                if ($fromArray && isset( $from[$k] )) {
                        $this->$k = $from[$k];
                } else if ($fromObject && isset( $from->$k )) {
                        $this->$k = $from->$k;
                }
            }
        }
        return true;

    }
    
    /**
     * 
     * @todo debuging behaviour to be conditioned by DB_DEBUG
     * @param type $updateNulls
     * @param type $force_new_entry
     * @return boolean
     */
    function store( $updateNulls=false , $force_new_entry = null )
    {
        $db = &SDatabase::getInstance();
        $keyName = $k = $this->_key;
        
        if( $this->$k && !$force_new_entry ){
		  
            $fmtsql = 'UPDATE '.$this->_table.' SET %s WHERE %s';
            if( $this->_lang ){
                
                $lng_col = $this->_lang;
                $fmtsql .= " AND  `{$lng_col}` = ".$this->{$lng_col};
                
            }
                
            $tmp = array();
            foreach (get_object_vars( $this ) as $k => $v)
            {
                if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                    continue;
                }
                
                if( $k == $keyName ) { // PK not to be updated
                    
                    $where = $keyName . '=' . "'". $db->getEscaped($v) ."'";
                    
                    continue;
                }
                
                if ($v === null)
                {
                        if ($updateNulls) {
                                $val = 'NULL';
                        } else {
                                continue;
                        }
                } else {
                    
                        $val =  "'". $db->getEscaped($v) . "'" ;
                }
                $tmp[] = "`". $k . "`" . '=' . ($val);
            }
            $this->_query = $query = ( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
            $db->setQuery($query);
            $ret = $db->query();
            //$ret = _sqlQuery($query);
            
            if (!$ret) {
                return false;
            }
            
            return true;
            
        }else{
            
            $fmtsql = 'INSERT INTO '.$this->_table.' ( %s ) VALUES ( %s ) ';
            $fields = array();
            foreach (get_object_vars( $this ) as $k => $v) {
                if (is_array($v) or is_object($v) or $v === NULL) {
                        continue;
                }
                if ($k[0] == '_') { // internal field
                        continue;
                }
                $fields[] = "`". $k ."`";
                $values[] = "'" . $db->getEscaped($v) ."'";
                //$values[] = "'" . _sqlEscValue($v) ."'";
            }
            $this->_query = $query = (  sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
            $db->setQuery($query);
            $ret = $db->query();
            //$ret = _sqlQuery($query);
            if (!$ret) {
                return false;
            }
            
            $db = &SDatabase::getInstance();
            $id = $db->insertid();
            if ($keyName && $id) {
                    $this->$keyName = $id;
            }
            return true;
        }
        
        if ( !$ret ){
            /* Log errors here */
                $sql=$this->_db->getQuery();
                $dir=ROOT_DIR."tmp/sqllogs/";
                $file = $dir.date("M")."_".date("Y")."_log.txt";
                $mesaj="[".date("Y-m-d h:i:s")."][table_error][Q: ".$sql."]";
                $list = debug_backtrace();
                $t = serialize($list[1]);
                $mesaj.="[debug: ".$t."]";

                if(!is_dir($dir))
                    mkdir($dir, 0777);
                if(!file_exists($file)){
                    $myfile = fopen($file, "wb");
                    fwrite($myfile, $mesaj);
                    fclose($myfile);    
                }
                else{
                    $current = file_get_contents($file);
                    $current .= "\n".$mesaj;
                    file_put_contents($file, $current);
                }
            /* Log errors here */
            
            //die(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
            return false;
        } else {
            return true;
        }
    }

    /**
     * Loads a row from the database and binds the fields to the object properties
     *
     * @access	public
     * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
     * @return	boolean	True if successful
     */
    function load( $oid=null, $lang=null )
    {
        
        $k = $this->_key;

        if ($oid !== null) {
            $this->$k = $oid;
        }

        $oid = $this->$k;

        if ($oid === null) {
            return false;
        }
        
        $sqlWhere="";
        if( $lang )
            $sqlWhere=" AND `{$this->_lang}`='$lang'";
            
        $db = SDatabase::getInstance();
        $q="SELECT * FROM $this->_table WHERE $this->_key = '$oid' $sqlWhere LIMIT 0,1";
        $db->setQuery($q);
        $row=$db->loadAssoc();
        if ( $row ) {
                return $this->bind($row);
        }
        
        return null;
        
	}
    
    function delete($oid){
        //_sqlDel($this->_table, $this->_key, $oid);
        $db = &SDatabase::getInstance();
        $q="DELETE FROM {$this->_table} WHERE $this->_key = $oid";
        $db->setQuery($q);
        $db->query();

        $upl = new SUPLPhotoHelper();
        $upl->delete_item_pictures($this->_table, $this->_key);
    }
    
    /**
     * STable::check()
     * Abstract validation method
     * 
     * @return boolean
     */
    function check(){
        return true;
    }
    
    /**
     * STable::getAlias()
     * Abstract get unique seo_name
     * 
     * @return string
     */
    function getAlias( $field_name ){
        $db = &SDatabase::getInstance();
        if( 
            isset($field_name) && $field_name!="" &&
            isset($this->$field_name) && $this->$field_name!=""
        ){
            $seo_link = seo_link( $this->$field_name );
            $q="select count({$this->_key}) as res from $this->_table where `$field_name` = '{$seo_link}'";
            $db->setQuery($q);
            $intermed=$db->loadAssoc();
            
            if( $intermed["res"] > 0 ){
                
                $seo_link .= "-".time();
            
            }
            
            return $seo_link;
            
            
        }else{
            die(":alias invalid argument");
        }
        
    }
    
    
 }
 
?>