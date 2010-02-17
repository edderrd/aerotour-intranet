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

    /**
     * Get file content and converted to a array
     *
     * @return array
     */
    protected function _parseData()
    {
        $filename = dirname(__FILE__) . "/data/MRPV-" . $this->_endPoint . ".txt";
        
        if ( !file_exists($filename) ) {
            throw new Exception("Wrong given endpoint");
        }
        
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        
        return $data;
    }
    

    /**
     * Return parsed data array
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Return routes array
     *
     * @return array
     */
    public function getRoute()
    {
        return $this->_data['route'];
    }


    /**
     * Summarize all distances in routes and return it
     * as a total
     *
     * @return long
     */
    public function getTotalDistance()
    {
        $rv = null;

        foreach ($this->getRoute() as $row) {
            $rv = $rv + $row['distance'];
        }

        return $rv;
    }
    

}
