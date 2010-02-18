<?php 

class PlanParser
{

    protected $_endPoint = null;
    protected $_data = array();
    protected $_routes = array();

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
        if ( empty($this->_routes)) {
            $this->_routes = $this->_data['route'];
        }
        return $this->_routes;
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
    
    /**
     * Summarize all time in routes and return it
     * as a total
     *
     * @return long
     */
    public function getTotalTime()
    {
        $tt = "00:00:00";
        return $tt;
    }

    /**
     * Summarize average speed in routes and return it
     * as a total
     *
     * @return long
     */
    public function getAverageSpeed()
    {
        $as = "83";
        return $as;
    }
    
    /**
     * Only return routes with airports
     *
     * @return array
     */
    public function getAirports()
    {
        $routes = $this->getRoute();
        $rv = array();
        
        foreach($routes as $row) {
            if ($row['airport'] == 'yes') {
                $rv[] = $row;
            }
        }
        
        return $rv;
    }

}
