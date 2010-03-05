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
        $rv = "60";
        return $rv;
    }

    /**
     * Summarize average speed in routes and return it
     * as a total
     *
     * @return long
     */
    public function getAverageSpeed()
    {
        $rv = "83";
        return $rv;
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
    
    /**
     * Returns time to travel between each point
     * Formula: Time = Distance / Ground Speed
     * ie. 329miles / 94knots = 3.5 hours (3hours 30min) 
     *
     * @return array
     */
    public function getTimes()
    {
        $rv = array();
        
        return $rv;
    }
    
    /**
     * Returns Total Fuel Required
     * Formula: ( Total Time / 60 ) * Gallons Burn per hour = Gallons Required
     * ie. 2 hours (120min / 60) * 14gallons per hour burn = 28 gallons required
     * additional 20 min should be added to total time for reserve, + 10 min for start up.
     *
     * This will probably JS function since it requires input from a text field on Burn Per Hour Rate.
     *
     * @return array
     */
    public function getFuel()
    {
        $rv = array();
        
        return $rv;
    }

}
