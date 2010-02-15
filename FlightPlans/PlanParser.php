<?php 

class PlanParser
{

    protected $_endPoint = null;
    protected $_data = array();

    public function __construct($endPoint)
    {
        if ( empty($endPoint) ) {
            throw new Exception("Empty endpoint given");
        }
        
        $this->_endPoint = $endPoint;
        $this->_data = $this->_parseData();
    }
    
    protected function _parseData()
    {
        $filename = dirname(__FILE__) . "/data/MRPV-" . $this->_endPoint . ".txt";
        
        if ( !file_exists($filename) ) {
            throw new Exception("Wrong given endpoint");
        }
        
        $file = file_get_contents($filename);        
        $data = json_decode(stripslashes($file), true);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit();
    
    }
    
    
    public function getData()
    {
        return $this->_data;
    }


    

}
