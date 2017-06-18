<?php 
/**
 * 
 * Database
 * 
 * General Database method and available methods
 *  for each database type
 * 
 * History:
 *  2.0 added support for multiple conections
 * 
 * 
 * @version 2.0RC
 *
 */
class SDatabase{
	
	var $_query 	= null;
	var $_counter 	= null;
	var $_errorNum	= null;
	var $_errorMsg	= null;
	var $_log		= null;
	var $_resource	= null;
	var $_cursor	= null;
        
	
	
	/**
	 *
	 * @return SDatabase
	*/
	function __construct($options=array()){

            $this->connect($options) or die ("Baza de date inexistenta!");
		
	}
	
	/**
	 *
	 * @return SDatabase
	*/
	public static function &getInstance($options=array()){
            static $instances;

            // Sanitize the database connector options.
            $options['driver'] = (isset($options['driver'])) ? preg_replace('/[^A-Z0-9_\.-]/i', '', $options['driver']) : DB_TYPE;
            $options['host'] = (isset($options['host'])) ? $options['host'] : DB_HOST;
            $options['user'] = (isset($options['user'])) ? $options['user'] : DB_USER;
            $options['pw'] = (isset($options['pw'])) ? $options['pw'] : DB_PASS;
            $options['database'] = (isset($options['database'])) ? $options['database'] : DB_NAME;

            $signature = md5(serialize($options));
            if(!isset($instances)){
                $instances = array();
            }
            if (empty($instances["DBO"])){
                
                
                if(empty($instances["DBO"][$signature]))
                {
                    $adapters = array("db_mysql.php", "db_mysqli.php"); /** List of database adapters */
                    $adapter_name = "db_".$options['driver'].".php";

                    if (count($adapters)>0 && in_array($adapter_name, $adapters) ) {

                        $adapter_class = "SDatabase".ucfirst($options['driver']);

                        require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."adapter".DIRECTORY_SEPARATOR.$adapter_name);
                        $instances["DBO"][$signature] = new $adapter_class($options);

                    }else{
                        die("Database adapter not installed!");
                    }
                }
                

            }
            
            return $instances["DBO"][$signature];
	}
        
        /**
         * Destructor
         * 
         * @todo Close connection
         */
        public function __destruct(){
            
        }

	/**
	 * Get the active query
	 *
	 * @access public
	 * @return string The current value of the internal SQL vairable
	 */
	function getQuery()
	{
		return $this->_query;
	}

	function setQuery($query)
	{
		$this->_query=$query;
	}
	
	public function getAffectedRows(){}
	public function getNumRows( $cur=null ){}
	public function loadResult(){}
	public function loadRow(){}
	public function loadAssoc(){}
	public function loadAssocList( $key='' ){}
	public function loadObject($className = 'stdClass'){}
	public function loadObjectList($key='', $className = 'stdClass'){}
	public function query($query){}
	public function insertid(){}
	public function getCollation(){}
	public function getEscaped($text, $extra = false){}
	
	/**
	 * Get the version of the database connector
	 */
	function getVersion()
	{
		return 'Not available for this connector';
	}

	public function getTableList(){}
	public function getTableCreate($tables){}
	public function getTableFields($tables, $typeonly = true){}

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
    public function getMin($table, $column, $filters=array()){}
        
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
    public function getMax($table, $column, $filters=array()){}
        
    /**
     * Toggles a 1 or 0 field value in the table
     * 
     * @param string $tableName
     * @param string $fieldSwitch
     * @param string $idName
     * @param string $idValue
     */
    public function switchField($tableName, $fieldSwitch, $idName, $idValue)
    {
        $q = "UPDATE $tableName SET $fieldSwitch=($fieldSwitch+1)%2 WHERE $idName='$idValue'";
        $this->query($q);
    }
    
    /**
     * Searches for a row / column in a table with matching criteria in $where
     *
     * @param string $tableName
     * @param string $type one from row, row_assoc, column name, count
     * @param array $where
     */
    function search($tableName, $type="row" ,$where){
    	 
    	if(count($where)==1)
    		$where = array($where);
    	 
    	$col = "*";
    	$col_name = "";
    	 
    	switch ($type) {
    		case "count":
    			$col = "count(*) as nr";
    			$col_name = "nr";
    			break;
    		case "row_assoc":
    		case "row":
    			$col = "*";
    			break;
    		default:
    			$col_name = $col = $type;
    			break;
    
    	}
    	if(count($where))
    		$where_sql = "where ".implode(" AND ", $where);
    	else 
    		$where_sql = "";
    	
    	$this->setQuery("select {$col} from {$tableName} ".$where_sql);
    	if($type=="row_assoc")
    	{
    		$row = $this->loadAssoc();
    		return $row;
    	}else{
    		$row = $this->loadObject();
    		if($type=="row")
    			return $row;
    		else
    			return (isset($row->$col_name))?$row->$col_name:false;
    	}
    	
    }
    
	/**
	 * Get a quoted database escaped string
	 *
	 * @param	string	A string
	 * @param	boolean	Default true to escape string, false to leave the string unchanged
	 * @return	string
	 */
	function quote($text, $escaped = true)
	{
		return '\''.($escaped ? $this->getEscaped($text) : $text).'\'';
	}
	
	/**
	 * Returns date format for the database driver.
         * Default: Y-m-d H:i:s
	 * 
	 * @return  string  The format string.
	 *
	 */
	public function getDateFormat()
	{
		return 'Y-m-d H:i:s';
	}

	
}
