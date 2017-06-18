<?php
/**
 * 
 * @version 1.2
 * 
 */
class SDatabaseMysql extends SDatabase
{
	
	function __construct(){
		parent::__construct();
	}
	
	function connect($options=array()){
            
            $options['host'] = (isset($options['host'])) ? $options['host'] : DB_HOST;
            $options['user'] = (isset($options['user'])) ? $options['user'] : DB_USER;
            $options['pw'] = (isset($options['pw'])) ? $options['pw'] : DB_PASS;
            $options['database'] = (isset($options['database'])) ? $options['database'] : DB_NAME;
            
            $this->_resource = mysql_connect($options['host'], $options['user'], $options['pw']);

            if($this->_resource)
            {
                    if(!mysql_select_db($options['database'])) return false;
                    mysql_query($this->_resource, "SET NAMES 'utf8'");
                    return true;
            }else return false;
		
	}
	
	/**
	 * Description
	 *
	 * @return	int	The number of rows returned from the most recent query.
	 */
	function getNumRows( $cur=null ){
		
		return mysql_num_rows($cur ? $cur : $this->_cursor);
		
	}
	
	/**
	 * @return	int		The number of affected rows in the previous operation
	 */
	function getAffectedRows()
	{
		return mysql_affected_rows($this->_resource);
	}
	
	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult()
	{
		$this->query();
		$item = mysql_fetch_row($this->_cursor);
        mysql_free_result($this->_cursor);
        return $item;
	}
	
	/**
	 * Description
	 *
	 * @return The first row of the query.
	 */
	function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysql_fetch_row($cur)) {
			$ret = $row;
		}
		mysql_free_result($cur);
		return $ret;
	}
	
	/**
	 * Fetch a result row as an associative array
	 */
	function loadAssoc()
	{
            if (!($cur = $this->query())) {
                    return null;
            }
            $ret = null;
            if ($array = mysql_fetch_assoc( $cur )) {
                    $ret = $array;
            }
            mysql_free_result( $cur );
            return $ret;
	}


	/**
	* Load a assoc list of database rows
	*
	* @access	public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	
	function loadAssocList( $key='' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
	  	
		$s = & SProfiler::getInstance();
	    $s->log("Query:{$this->_query}: ");
	    
		$array = array();
		if (!$key) {
			
			while ($row = mysql_fetch_assoc( $cur )) {
				$array[] = $row;
			}
		    $s->log("loadAssocList after: {$this->_query}: ");
		    
			mysql_free_result( $cur );
			return $array;
			
		} else {
			
			while ($row = mysql_fetch_assoc( $cur )) {
				$array[$row[$key]] = $row;
			}
			
		    $s->log("Query:{$this->_query}: ");
		    
			mysql_free_result( $cur );
			return $array;
			
		}
		
	}
	
	/**
	 * This global function loads the first row of a query into an object.
	 *
	 * @param	string	The name of the class to return (stdClass by default).
	 *
	 * @return	object
	 */
	function loadObject($className = 'stdClass')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = mysql_fetch_object($cur, $className)) {
			$ret = $object;
		}
		mysql_free_result($cur);
		return $ret;
	}

	/**
	 * Load a list of database objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string	The field name of a primary key
	 * @param	string	The name of the class to return (stdClass by default).
	 *
	 * @return	array	If <var>key</var> is empty as sequential list of returned records.
	 */
	function loadObjectList($key='', $className = 'stdClass')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_object($cur, $className)) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result($cur);
		return $array;
	}
	
	/**
	 * Execute the query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function query($query=null)
	{
    	global $profiler;

		if (!is_resource($this->_resource)) {
			return false;
		}

                if($query)
                    $this->setQuery($query);
                
		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->_query;
        // speed on some hostings or a very idiot technical support
        $sql = $sql.";";
        if(1==DEBUG){
            $profiler->enterSection($sql);
    	}
		$this->_errorNum = 0;
		$this->_errorMsg = '';

		if (!($this->_cursor = mysql_query( $sql, $this->_resource )))
		{
			$this->_errorNum = mysql_errno( $this->_resource );
			$this->_errorMsg = mysql_error( $this->_resource )." SQL=$sql";
            
            /* Log errors here */
                $dir=ROOT_DIR."tmp/sqllogs/";
                $file = $dir.date("M")."_".date("Y")."_log.txt";
                $mesaj="[".date("Y-m-d h:i:s")."][error][Q: ".$sql."][E: ".$this->_errorNum.", ".$this->_errorMsg."]";
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
            
            return false;
		}
        if(1==DEBUG){
            // iF debug and succesfull
            $profiler->leaveSection($sql);
            if(1==DEBUG || 1==DB_DEBUG){
                $this->_log[] = ( $sql );
        	}
    	}
        
		return $this->_cursor;
	}
	
	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 */
	function getEscaped($text, $extra = false)
	{
		$result = mysql_real_escape_string($text, $this->_resource);
		if ($extra) {
			$result = addcslashes($result, '%_');
		}
		return $result;
	}
	
	/**
	 * Description
	 */
	function insertid()
	{
		return mysql_insert_id($this->_resource);
	}

	/**
	 * Description
	 */
	function getVersion()
	{
		return mysql_get_server_info($this->_resource);
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @return	string	Collation in use
	 */
	function getCollation ()
	{
		if ($this->hasUTF()) {
			$this->setQuery('SHOW FULL COLUMNS FROM #__content');
			$array = $this->loadAssocList();
			return $array['4']['Collation'];
		} else {
			return "N/A (mySQL < 4.1.2)";
		}
	}

	/**
	 * Description
	 *
	 * @return	array	A list of all the tables in the database
	 */
	function getTableList()
	{
		$this->setQuery('SHOW TABLES');
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @param	array|string	A table name or a list of table names
	 * @return	array	A list the create SQL for the tables
	 */
	function getTableCreate($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery('SHOW CREATE table ' . $this->getEscaped($tblval));
			$rows = $this->loadRowList();
			foreach ($rows as $row) {
				$result[$tblval] = $row[1];
			}
		}

		return $result;
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @param	array|string	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array	An array of fields by table
	 */
	function getTableFields($tables, $typeonly = true)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery('SHOW FIELDS FROM ' . $tblval);
			$fields = $this->loadObjectList();

			if ($typeonly) {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
				}
			} else {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = $field;
				}
			}
		}

		return $result;
	}
	
    function __destruct() {
        mysql_close($this->_resource);
    }
    
    /**
         * Get's the minimum value of column from the table
         * 
         * @example $db->getMin('sample', 'sample_price', array( " sample_status='1' ", " sample_category='1' " ));
         * 
         * @todo
         * @param string $table the name of the table
         * @param string $column the name of the table column
         * @param array $filters array of aditional sql conditions
    */
    function getMin($table, $column, $filters=array()){
        $db = & SDatabase::getInstance();
        $sqlWhere="";
        if(count($filters)>0)
        {
            foreach($filters as $key=>$item) {
                $sqlWhere.=" AND $key='$item' ";
            }
        }
            

        $q="SELECT min($column) min FROM $table WHERE 1 $sqlWhere LIMIT 0,1";
        $db->setQuery($q);
        $result=$db->loadAssoc();

        if(isset($result['min']) && $result['min']!="")
            return $result['min'];
        else
            return false;
    }
    
    /**
         * Get's the maximum value of column from the table
         * 
         * @example $db->getMax('sample', 'sample_price', array( " sample_status='1' ", " sample_category='1' " ));
         * 
         * @todo
         * @param string $table the name of the table
         * @param string $column the name of the table column
         * @param array $filters array of aditional sql conditions
    */
    function getMax($table, $column, $filters=array()){
        $db = & SDatabase::getInstance();
        $sqlWhere="";
        if(count($filters)>0)
        {
            foreach($filters as $key=>$item) {
                $sqlWhere.=" AND $key='$item' ";
            }
        }
            

        $q="SELECT max($column) max FROM $table WHERE 1 $sqlWhere LIMIT 0,1";
        $db->setQuery($q);
        $result=$db->loadAssoc();

        if(isset($result['max']) && $result['max']!="")
            return $result['max'];
        else
            return false;
    }
    
}