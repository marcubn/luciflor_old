<?php
/**
 * UPLPhoto helpers
 *
 */
class SUPLPhotoHelper{
    
    /**
     * Get all uplphotos for an item
     *
     * @access: public
     * @return: null
    */
    static function get_pictures($table, $id, $no=0)
    {
            $db = &SDatabase::getInstance();
            if ($no>0) $sqlLimit="LIMIT 0, ".$no;
            else $sqlLimit="";

            $q="SELECT
                            *
                    FROM
                            uplphoto
                    WHERE
                            owner='{$table}' AND
                            owner_id='{$id}'
                    ORDER BY
                            def DESC, priority ASC
                    $sqlLimit
            ";

            $db->setQuery($q);
            $list = $db->loadAssocList();

            if ($no==1 && isset($list[0]))
                return $list[0];
            elseif (count($list)==0)
                    return false;
            else
                    return $list;
    }
    
    /**
     * Get all uplphotos for an item grouped by gallery
     *
     * @autor: Bmarcu (marcu.bogdannicolae@gmail.com)
     * @access: public
     * @return: null
    */
    function get_media($table, $id)
    {
        $db = &SDatabase::getInstance();
        $q="SELECT
                *
            FROM
                uplphoto
            WHERE
                owner='{$table}' AND
                owner_id='{$id}'
            ORDER BY
                gallery_id ASC, priority ASC
        ";
        
        $db->setQuery($q);
        $lista = $db->loadObjectList();

        $list = array(); 
        foreach($lista as $key=>$value) {
            $list[$value->gallery_id][] = $value;
        }

        if (count($list)==0)
            return false;
        else
            return $list;
    }


    /**
    * Deletes phisically and from database owned images for an item
    *  from uplphtotos  
    *
    * 
    * */
    function delete_item_pictures($table, $id){

        $db = &SDatabase::getInstance();
        $db->setQuery("select * from uplphoto where `owner` = '{$table}' and `owner_id` = '{$id}' ");
        $list = $db->loadObjectList();
        if($list){
            foreach($list as $k => $item){
                if(file_exists( PHOTOS_UPLOAD_DIR.$item->file ))
                    unlink(PHOTOS_UPLOAD_DIR.$item->file);
            }
        }
        $db->setQuery("delete from uplphoto where `owner` = '{$table}' and `owner_id` = '{$id}' ");
        $db->query();
    }
}


/**
 * Get all uplphotos for an item
 *
 * @deprecated since version 3.0
 * @access: public
 * @return: null
*/
function get_pictures($table, $id, $no=0)
{
    $upl = new SUPLPhotoHelper();
    return $upl->get_pictures($table, $id);
}

?>