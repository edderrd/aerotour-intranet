<?php

class MapsData
{
    protected $_dataPath = "";
    protected $_endPoint = "";
    protected $_startPoint = "MRPV";
    protected $_cordinates = array();

    public function __construct($point)
    {
        if ( empty($point) )
            throw new Exception(__METHOD__ . ": Point not passed");

        $this->_dataPath = dirname(__FILE__) . "/../data/";
        $this->_endPoint = $point;
        $this->_data = $this->_getData();
        $this->_cordinates = $this->_getCordinates();
    }

    /**
     * Create path using start and end points
     *
     * @param string $startPoint
     * @param string $endPoint
     * @return string
     */
    protected function _getPath($startPoint, $endPoint)
    {
        return $this->_dataPath . $this->_startPoint . "-" . $this->_endPoint . ".txt";

    }

    /**
     * Reads file contents parse json and return it as a array
     *
     * @return array
     */
    protected function _getData()
    {
        $path = $this->_getPath($this->_startPoint, $this->_endPoint);

        if ( !file_exists($path) )
            throw new Exception(__METHOD__ . ": path $path doesn't exist");

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Parse cordiantes from the routes
     *
     * @return array
     */
    protected function _getCordinates()
    {
        $rv = array();

        if ( !empty($this->_data['route']) ) {

            foreach($this->_data['route'] as $route) {
                $latLong = $route['cordinates'];
                $latLong = str_ireplace("new GLatLng(", "", $latLong);
                $latLong = str_replace(")", "", $latLong);
                $latLong = explode(",", $latLong);

                $lat = trim($latLong[0]);
                $long = trim($latLong[1]);

                $rv[$route['point']]['lat'] = $lat;
                $rv[$route['point']]['lng'] = $long;
            }
        }

        return $rv;
    }

    /**
     * Calculate bearing between two points
     *
     * @param array $pointA array('lat', 'lng');
     * @param array $pointB array('lat', 'lng');
     * @return long
     */
    protected function _getBearing($pointA, $pointB)
    {
        if (empty($pointA) || empty($pointB))
            throw new Exception(__METHOD__ . ": One of the points is empty");

        $lat1 = $pointA['lat'];
        $lon1 = $pointA['lng'];
        $lat2 = $pointB['lat'];
        $lon2 = $pointB['lng'];

        return round((rad2deg(atan2(sin(deg2rad($lon2) - deg2rad($lon1)) * cos(deg2rad($lat2)), cos(deg2rad($lat1)) * sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2) - deg2rad($lon1)))) + 360) % 360);
    }

    /**
     * Calculate distance from two points
     *
     * @param array $pointA array('lat', 'lng');
     * @param array $pointB array('lat', 'lng');
     * @return int
     */
    protected function _getDistance($pointA, $pointB)
    {
        if (empty($pointA) || empty($pointB))
            throw new Exception(__METHOD__ . ": One of the points is empty");

        $lat1 = $pointA['lat'];
        $lon1 = $pointA['lng'];
        $lat2 = $pointB['lat'];
        $lon2 = $pointB['lng'];

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $dist = $dist * 60 * 1.1515;

        $dist *= 1.609344;
        $dist = $dist / 1.852;

        return round($dist, 0); //0 represents decimal value. 1 for one value after point (.)
    }

    /**
     * Calculate Bearing and distance for all routes
     *
     * @return array
     */
    public function getBearingsDistances()
    {
        $rv = array();

        if ( !empty($this->_cordinates) ) {
            $lastRoute = end(array_keys($this->_cordinates));
            $index = 0;

            foreach($this->_cordinates as $route => $latLong) {
                if ( $lastRoute != $route ) {
                    $values = array_values($this->_cordinates);

                    $pointA = $latLong;
                    $pointB = $values[$index+1];

                    $rv[$route]['bearing'] = $this->_getBearing($pointA, $pointB);
                    $rv[$route]['distance'] = $this->_getDistance($pointA, $pointB);
                }
                $index++;
            }
        }

        return $rv;
    }
}