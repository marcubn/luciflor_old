<?php
/**
 * 
 * System Cache Cleaning functions
 * Required PHP Version: PHP5.2+
 *
 * 
 */
class SCache
{
	/**
         * Delete's cache folder contents
         * 
         * @return int number of files
         */
	function purgePhotosCache()
	{
            $no_files = 0;
            $dir = UPLOAD_DIR."cache/";
            $it = new RecursiveDirectoryIterator($dir);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            
            foreach($files as $file) {
                if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                    continue;
                }
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
                $no_files++;
            }

            //_sqlQuery("UPDATE product SET product_thumbnail=''");

            return $no_files;
	}
        
        /**
         * 
         * Deletes Smarty Cache manually ( no smarty delete cache method called )
         * 
         * @return int number of cache files deleted
         */
	function purgeSmartyCache()
        {
            $no_files = 0;
            $dir = COMPILE_DIR;
            $it = new RecursiveDirectoryIterator($dir);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            
            foreach($files as $file) {
                if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                    continue;
                }
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
                $no_files++;
            }

            return $no_files; 	
	}
}
?>