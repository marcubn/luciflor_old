<?php
/*
 * Class cursBnrXML v1.0
 * Author: Ciuca Valeriu
 * E-mail: vali.ciuca@gmail.com
 * This class parses BNR's XML and returns the current exchange rate
 *
 * Requirements: PHP5 
 *
 * Last update: October 2011, 27     
 * More info: www.curs-valutar-bnr.ro
 *
 */

 class cursBnrXML
 {
     /**
     * xml document
     * @var string
     */
     var $xmlDocument = "";
     
     
     /**
     * exchange date
     * BNR date format is Y-m-d
     * @var string
     */
     var $date = "";
     
     
     /**
     * currency
     * @var associative array
     */
     var $currency = array();
     
     
     /**
     * cursBnrXML class constructor
     *
     * @access        public
     * @param         $url        string
     * @return        void
     */
    function cursBnrXML($url)
    {
        $this->xmlDocument = file_get_contents($url);
        $this->parseXMLDocument();
    }
     
    /**
     * parseXMLDocument method
     *
     * @access        public
     * @return         void
     */
    function parseXMLDocument()
    {
         $xml = new SimpleXMLElement($this->xmlDocument);
         
         $this->date=$xml->Header->PublishingDate;
         
         foreach($xml->Body->Cube->Rate as $line)    
         {                      
             $this->currency[]=array("name"=>$line["currency"], "value"=>$line, "multiplier"=>$line["multiplier"]);
         }
    }
    
    /**
     * getCurs method
     * 
     * get current exchange rate: example getCurs("USD")
     * 
     * @access        public
     * @return         double
     */
    function getCurs($currency)
    {
        foreach($this->currency as $line)
        {
            if($line["name"]==$currency)
            {
                return $line["value"];
            }
        }
        
        return "Incorrect currency!";
    }
 }

 class cursBnrXMLArchive
 {
     /**
     * xml document
     * @var string
     */
     var $xmlDocument = "";
     

     /**
     * Archive Publishing Date
     * BNR date format is Y-m-d
     * @var string
     */
     var $date = "";
     
     /**
     * exchange dates
     * BNR date format is Y-m-d
     * @var string
     */
     var $dates = array();
     
     
     /**
     * currency
     * @var associative array
     */
     var $currencies = array();
     
     
     /**
     * cursBnrXML class constructor
     *
     * @access        public
     * @param         $url        string
     * @return        void
     */
    function cursBnrXMLArchive($url)
    {
        $this->xmlDocument = file_get_contents($url);
        $this->parseXMLDocument();
    }
     
    /**
     * parseXMLDocument method
     *
     * @access        public
     * @return         void
     */
    function parseXMLDocument()
    {
         $xml = new SimpleXMLElement($this->xmlDocument);
         
         $this->date = $xml->Header->PublishingDate;
         
         foreach($xml->Body->Cube as $Cube){
            
             $CubeDate = (string)$Cube->attributes()->date ;
             $this->dates[] = $CubeDate;
             
             $rates = array();
             foreach($Cube as $line)    
             {                      
                 $rates[] = array("name"=>$line["currency"], "value"=>$line, "multiplier"=>$line["multiplier"]);
             }
             $this->currencies[$CubeDate] = $rates;
         }
    }
    
    /**
     * getCurs method
     * 
     * get current exchange rate: example getCurs("USD")
     * 
     * @access        public
     * @return         double
     */
    function getCurs($day, $currency)
    {
        
        if(isset($this->currencies[$day])){
            foreach($this->currencies[$day] as $line)
            {
                if($line["name"]==$currency)
                {
                    return $line["value"];
                }
            }
        }else{
            return "Incorrect currency date!";
        }
        
        return "Incorrect currency!";
    }
 }
 
 
?>