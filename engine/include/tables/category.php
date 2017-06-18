<?php 

require_once ( LIB_DIR."db/db_table.php" );
require_once ( LIB_DIR . DS . "nested" . DS . "model.nestedset.php" );

class categoryTable extends SNestedSet{
    
    function __construct( ){
        parent::__construct( "product_category", "category_id" , "category_name"  );
    }
        
}

?>