<?php
/**
 * Fork from Mark Stuppacher nested sets class
 * @version: the better one
 *
 */
class SNestedSet
{
	/*Properties*/

	/**
	 * Database object
	 * @var object
	 */
	var $db;

	/**
	 * Name of the database table
	 * @var string
	 */
	var $tableName = '';

	/**
	 * Primary key of the database table
	 * @var string
	 */
	var $primaryKey = '';

	/**
	 * Name Field in the database table
	 * @var string
	 */
	var $fieldName = '';

	/*Methods*/

	/**
	 * Stores a Mysqli object for further use
	 * @param object $mysqli Mysqli object
	 * @return boolean true
	 */
	public function __construct($tableName, $primaryKey, $fieldName)
	{
            $this->tableName    = $tableName;
            $this->primaryKey   = $primaryKey;
            $this->fieldName    = $fieldName;
            $this->db = &Sdatabase::getInstance();
	}


	/**
	 * Creates the root node
	 * @param object $root_node Properties of the new root node
	 * @return boolean true
	 */
	function createRootNode( $root_node ) {
		
            $this->db->setQuery("LOCK TABLES `" . $this->tableName . "` WRITE");
            $this->db->query();

            $sql = "SELECT `rgt` FROM `" . $this->tableName . "` ORDER BY `rgt` DESC LIMIT 1";
            $this->db->setQuery($sql);
            $result = $this->db->loadResult();

            if ($this->db->getAffectedRows() == 0)
            {
                    $lft = 0;
                    $rgt = 1;
            }
            else
            {
                    $lft = $result[0] + 1;
                    $rgt = $lft + 1;
            }
        
            $dbTable = STable::tableInit( $this->tableName, $this->primaryKey );
            if($root_node!=="ROOT"){
                $dbTable->bind( $root_node );
            }
            $dbTable->lft = $lft;
            $dbTable->rgt = $rgt;
            $dbTable->store();
            
            $this->db->setQuery("UNLOCK TABLES");
            $this->db->query();
	}

	function getRootNode()
	{
            $sql = "SELECT * FROM `" . $this->tableName . "` ORDER BY `lft` ASC LIMIT 1";
            $this->db->setQuery($sql);

            return $this->db->loadObject();
	}


	/**
	 * Creates a new node
	 * @param string $name name of the new node
	 * @param parent node
	 * @return boolean	true
	 */
	function insertNode( $nodeDBObj, $parent_node )
	{
            $rgt 	= $parent_node->rgt;
            $lft 	= $parent_node->lft;
            $parent_id 	= $parent_node->{$this->primaryKey};

            $sql = "UPDATE " . $this->tableName . " SET rgt = rgt + 2 WHERE rgt >= " . $rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET lft = lft + 2 WHERE lft > " . $rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $dbTable = STable::tableInit( $this->tableName, $this->primaryKey );
            $dbTable->bind( $nodeDBObj );
            $dbTable->lft = $rgt;
            $dbTable->rgt = $rgt + 1;
            $dbTable->parent = $parent_id;
            $this->getAlias($dbTable);
            $dbTable->store();

	}


	/**
	 * Gets an object with all data of a node
	 * @param integer $id id of the node
	 * @return object object with node-data (id, lft, rgt,...)
	 */
	function getNode($id)
	{
            $id = intval($id);

            $this->db->setQuery( "SELECT * FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = " . $this->db->getEscaped($id)  );
            $node = $this->db->loadObject();
            return $node;
	}

	/**
	 * Creates a new child node of the node with the given id
	 * @param array $name name of the new node
	 * @param integer $parent id of the parent node
	 * @return boolean true
	 */
	function insertChildNode( $nodeDBObj, $parent)
	{
            $p_node = $this->getNode($parent);

            if(!$p_node)
            {
                    $rootNode = $this->getRootNode();
                    if(!$rootNode)
                    {
                            $this->createRootNode('ROOT');
                    }
                    $p_node = $this->getRootNode();
            }

            $this->insertNode( $nodeDBObj, $p_node );
            $lastId = $this->db->insertid();
	}

	/**
	 * Creates a multi-dimensional array of the whole tree
	 * @return array multi-dimenssional array of the whole tree
	 */
	function getTree($parentId=0,$includeParent=true,$maxLevels=0)
	{
            $having = "";
            if($maxLevels>0)
                $having = " HAVING level <= {$maxLevels}";

            $parentCondition = '';
            if(intval($parentId))
            {
                $this->db->setQuery("SELECT * FROM `".$this->tableName."` WHERE `id`='".$parentId."'");
                $parent = $this->db->loadObject();

                if($parent->{$this->primaryKey}==$parentId)
                {
                        $parentCondition = ' AND p.lft>'.$parent->lft.' AND p.rgt<'.$parent->rgt.' ';
                }
            }

            $nr_subcats_query = " , (SELECT COUNT(*)-1 FROM $this->tableName AS kidz WHERE kidz.lft BETWEEN n.lft AND n.rgt ) as nr_cats";

            $sql = 
            "SELECT n.*, COUNT(*)-1 AS level \r\n $nr_subcats_query \r\n ".
            " FROM " .$this->tableName . " AS n, " . $this->tableName . " AS p \r\n ".
            " WHERE n.lft BETWEEN p.lft AND p.rgt $parentCondition \r\n GROUP BY n.lft {$having} ORDER BY n.lft\r\n ";
            $this->db->setQuery($sql);
            //var_dump($this->db->getQuery());exit;

            $rows = $this->db->loadObjectList();
            if(!$includeParent)
            {
                array_shift($rows);
            }
            return $rows;
	}
	
	function getParent($id,$fullDetails=false)
	{
            $path = $this->getPath($id);
            array_pop($path);

            if(!$fullDetails)
            {
                    return array_pop($path);
            }
            else
            {
                    $partParent = array_pop($path);
                    return $this->getNode($partParent->{$this->primaryKey});
            }
	}

	
	/**
         * BUG: doesn't work moving on a higher level
         * 
	 * Moves a node and its children to a new parent
	 * @param integer $id id of the node
	 * @param integer $parentId id of the new parent
	 * @return boolean true
	 */
	function moveNode($id,$parentId)
	{
            
            $id = $this->db->getEscaped($id);
            $parentId = $this->db->getEscaped($parentId);

            $node = $this->getNode($id);
            $newParent = $this->getNode($parentId);
            $iSize = $node->rgt - $node->lft + 1;
            
            $this->db->setQuery("LOCK tables " . $this->tableName . " WRITE;");
            $this->db->query();

            // step 1: temporary "remove" moving node
            $query = 'UPDATE `'.$this->tableName.'` SET `lft` = 0-(`lft`), `rgt` = 0-(`rgt`) WHERE `lft` >= "'.$node->lft.'" AND `rgt` <= "'.$node->rgt.'"';
            $this->db->setQuery($query);
            $this->db->query();

            // step 2: decrease left and/or right position values of currently 'lower' items (and parents)
            $query = 'UPDATE `'.$this->tableName.'` SET `lft` = `lft` - '.$iSize.' WHERE `lft` > "'.$node->rgt.'"';
            $this->db->setQuery($query);
            $this->db->query();
            
            $query = 'UPDATE `'.$this->tableName.'` SET `rgt` = `rgt` - '.$iSize.' WHERE `rgt` > "'.$node->rgt.'"';
            $this->db->setQuery($query);
            $this->db->query();

            // step 3: increase left and/or right position values of future 'lower' items (and parents)
            $query = 'UPDATE `'.$this->tableName.'` SET `lft` = `lft` + '.$iSize.' WHERE `lft` >= "'.($newParent->rgt > $node->rgt ? $newParent->rgt - $iSize : $newParent->rgt).'"';
            $this->db->setQuery($query);
            $this->db->query();
            
            $query = 'UPDATE `'.$this->tableName.'` SET `rgt` = `rgt` + '.$iSize.' WHERE `rgt` >= "'.($newParent->rgt > $node->rgt ? $newParent->rgt - $iSize : $newParent->rgt).'"';
            $this->db->setQuery($query);
            $this->db->query();

            // step 4: move node (ant it's subnodes) and update it's parent item id
            $query = 'UPDATE `'.$this->tableName.'` SET `lft` = 0-(`lft`)+'.($newParent->rgt > $node->rgt ? $newParent->rgt - $node->rgt - 1 : $newParent->rgt - $node->rgt - 1 + $iSize).', `rgt` = 0-(`rgt`)+'.($newParent->rgt > $node->rgt ? $newParent->rgt - $node->rgt - 1 : $newParent->rgt - $node->rgt - 1 + $iSize).' WHERE `lft` <= "'.(0-$node->lft).'" AND `rgt` >= "'.(0-$node->rgt).'"';
            $this->db->setQuery($query);
            $this->db->query();
            
            //$query = 'UPDATE `'.$this->tableName.'` SET `parent` = "'.$newParent->id.'" WHERE `id`="'.$node->id.'"';
            //$this->db->setQuery($query);
            //$this->db->query();
    	
            $this->db->setQuery("UNLOCK TABLES;");
            $this->db->query();
	}
	
	
	/**
	 * Deletes a node and all it's children
	 * @param integer $id id of the node to delete
	 * @return boolean true
	 */
	function deleteNode($id) {
            
            $this->db->setQuery("LOCK tables " . $this->tableName . " WRITE;");
            $this->db->query();
            
            $node = $this->getNode($id);
            $sql = "DELETE FROM " . $this->tableName . " WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();
            $sql = "UPDATE " . $this->tableName . " SET lft = lft - ROUND((" . $node->rgt . " - " . $node->lft . " + 1)) WHERE lft > " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();
            $sql = "UPDATE " . $this->tableName . " SET rgt = rgt - ROUND((" . $node->rgt . " - " . $node->lft . " + 1)) WHERE rgt > " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();
            
            $this->db->setQuery("UNLOCK TABLES;");
            $this->db->query();
            
            return true;
	}

	/**
	 * Deletes a node and increases the level of all children by one
	 * @param integer $id id of the node to delete
	 * @return boolean true
	 */
	function deleteSingleNode($id) {
            
            $this->db->setQuery("LOCK tables " . $this->tableName . " WRITE;");
            $this->db->query();

            $node = $this->getNode($id);

            $sql = "DELETE FROM " . $this->tableName . " WHERE lft = " . $node->lft . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET lft = lft - 1, rgt = rgt - 1 WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET lft = lft - 2 WHERE lft > " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET rgt = rgt - 2 WHERE rgt > " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $this->db->setQuery("UNLOCK TABLES;");
            $this->db->query();

            return true;
	}
	
	
	/**
	 * Gets a multidimensional array containing the path to defined node
	 * @param integer $id id of the node to which the path should point
	 * @return array multidimensional array with the data of the nodes in the tree
	 */
	function getPath($id)
	{
            $sql = "SELECT p." . $this->primaryKey . ", p." . $this->fieldName . " FROM " . $this->tableName . " n, " . $this->tableName . " p WHERE n.lft BETWEEN p.lft AND p.rgt AND n." . $this->primaryKey ." = " . $this->db->getEscaped($id) . " ORDER BY p.lft;";
            $this->db->setQuery($sql);

            return $this->db->loadObjectList();
	}

	
	/**
	 * Gets the id of a node depending on it's rgt value
	 * @param integer $rgt rgt value of the node
	 * @return integer id of the node
	 */
	function getIdRgt($rgt)
	{
            $this->db->setQuery( "SELECT " . $this->primaryKey . " FROM " . $this->tableName . " WHERE rgt = " . $this->db->getEscaped($rgt) );
            $p = $this->db->loadResult();
            if(isset($p[0]))
                return $p[0];
            else{
                systemMessage::addMessage("Node can not be moved!");
                redirect($_SERVER["HTTP_REFERER"]);
            }
	}
	
	
	/**
	 * Moves a node one position to the left staying in the same level
	 * @param $nodeId id of the node to move
	 * @return boolean true
	 */
	function moveLft($nodeId)
	{
            $node = $this->getNode($nodeId);
            $brotherId = $this->getIdRgt($node->lft-1);
            if (!$brotherId)
            {
                    //node can't be moved left
                    systemMessage::addMessage('Node can not be moved left!');
                    return;
            }
            $brother = $this->getNode($brotherId);

            $nodeSize = $node->rgt - $node->lft + 1;
            $brotherSize = $brother->rgt - $brother->lft + 1;

            $sql = "SELECT " . $this->primaryKey . " FROM " . $this->tableName . " WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $res = $this->db->loadObjectList();

            $idsNotToMove = array();
            foreach ($res as $n)
            {
                    $idsNotToMove[] = $n->{$this->primaryKey};
            }

            $sql = "UPDATE " . $this->tableName . " SET lft = lft - " . $brotherSize . ", rgt = rgt - " . $brotherSize . " WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET lft = lft + " . $nodeSize . ", rgt = rgt + " . $nodeSize . " WHERE lft BETWEEN " . $brother->lft . " AND " . $brother->rgt;
            for ($i = 0; $i < count($idsNotToMove); $i++)
            {
                    $sql .= " AND " . $this->primaryKey . " != " . $idsNotToMove[$i];
            }
            $this->db->setQuery($sql);
            $this->db->query();

            return true;
	}
	
	
	/**
	 * Gets the id of a node depending on it's lft value
	 * @param integer $lft lft value of the node
	 * @return integer id of the node
	 */
	function getIdLft($lft)
	{
            $this->db->setQuery("SELECT " . $this->primaryKey . " FROM " . $this->tableName . " WHERE lft = " . $this->db->getEscaped($lft) );
            $p = $this->db->loadResult();
            if(isset($p[0]))
                return $p[0];
            else{
                systemMessage::addMessage("Node can not be moved!");
                redirect($_SERVER["HTTP_REFERER"]);
            }
	}

	/**
	 * Moves a node one position to the right staying in the same level
	 * @param $nodeId id of the node to move
	 * @return boolean true
	 */
	function moveRgt($nodeId)
	{
            $node = $this->getNode($nodeId);
            $brotherId = $this->getIdLft($node->rgt+1);
            if ($brotherId == false)
            {
                //node can't be moved right
                systemMessage::addMessage('Node can not be moved right!');
                return;
            }
            $brother = $this->getNode($brotherId);

            $nodeSize = $node->rgt - $node->lft + 1;
            $brotherSize = $brother->rgt - $brother->lft + 1;


            $this->db->setQuery("SELECT " . $this->primaryKey . " FROM " . $this->tableName . " WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt);
            $result = $this->db->loadObjectList();

            $idsNotToMove = array();
            foreach($result as $r)
            {
                    $idsNotToMove[] = $r->{$this->primaryKey};
            }

            $sql = "UPDATE " . $this->tableName . " SET lft = lft + " . $brotherSize . ", rgt = rgt + " . $brotherSize . " WHERE lft BETWEEN " . $node->lft . " AND " . $node->rgt . ";";
            $this->db->setQuery($sql);
            $this->db->query();

            $sql = "UPDATE " . $this->tableName . " SET lft = lft - " . $nodeSize . ", rgt = rgt - " . $nodeSize . " WHERE lft BETWEEN " . $brother->lft . " AND " . $brother->rgt;
            for ($i = 0; $i < count($idsNotToMove); $i++) {
                    $sql .= " AND " . $this->primaryKey . " != " . $idsNotToMove[$i];
            }		
            $this->db->setQuery($sql);
            $this->db->query();

            return true;
	}
	
	
    /**
     *
     * Get Category Direct Subcategories 
     * 
     * @return object list
     **/
    function getSubcategories( $id ){
        
        $this->db->setQuery( "SELECT * FROM {$this->tableName} WHERE parent_id = {$id}" );
        return $this->db->loadObjectList();
        
    }

    /**
     * Get alias
     * 
     */
    function getAlias( &$node ){
        
        if( 
            isset($this->fieldName) && $this->fieldName!="" &&
            property_exists($node,"alias") && $node->alias == "" 
                
        ){
            $alias = seo_link( $node->{$this->fieldName} );
            
            if( _sqlFetchQuery("select count({$this->primaryKey}) as res from $this->tableName where alias = '{$alias}' and parent = '{$node->parent}' ","res") > 0 ){
                
                $alias .= "-".time();
            
            }
            
            $node->alias = $alias;
            
        }
        
    }
    

}
?>