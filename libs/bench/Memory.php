<?php
class MemoryProfiler{
    
    /**
     * Get information about current memory usage.
     *
     * @return  integer  The memory usage
     *
     * @link    PHP_MANUAL#memory_get_usage
     * @since   11.1
     */
    public function getMemory()
    {
            if (function_exists('memory_get_usage'))
            {
                    return memory_get_usage();
            }
            else
            {
                    // Initialise variables.
                    $output = array();
                    $pid = getmypid();

                    if ($this->_iswin)
                    {
                            // Windows workaround
                            @exec('tasklist /FI "PID eq ' . $pid . '" /FO LIST', $output);
                            if (!isset($output[5]))
                            {
                                    $output[5] = null;
                            }
                            return substr($output[5], strpos($output[5], ':') + 1);
                    }
                    else
                    {
                            @exec("ps -o rss -p $pid", $output);
                            return $output[1] * 1024;
                    }
            }
    }
    
    function convert($size)
    {
       $unit=array('b','kb','mb','gb','tb','pb');
       return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
    function get_server_cpu_usage(){
        
        if(function_exists("sys_getloadavg")){
            $sys_load = sys_getloadavg();
            return "Last minute: ".$sys_load[0] ." % /"."Last 5 minutes: ".$sys_load[1]." % /"."Last 15 minutes: ".$sys_load[2];
        }else{
            return "NaN";
        }

    }    
}
