Maps = {

    gmap: null,
    data: null,
    markers: new Array(),
    cordinates: new Array(),
    bounds: null,

    /**
     * Main entry program it will create gmap object and parse data from
     * a especific destination point
     *
     * @param document element
     * @param string jsonString
     */
    init: function(element, jsonString) {
        // initialize gmap
        this.gmap = new GMap2(element);
        this.parseJsonResponse(jsonString);

        // add markers
        this.addMarkers();
        this.bounds = this.getBounds();
        this.addTrackLine();
        // add distance information
        this.addDistanceMarkers();

        // center map using bounds
        this.gmap.setCenter(this.bounds.getCenter());
        this.gmap.setZoom(this.gmap.getBoundsZoomLevel(this.bounds));

        // default options
        this.gmap.setUIToDefault();
        this.gmap.removeMapType(G_SATELLITE_MAP);
        this.gmap.disableScrollWheelZoom();
            
    },

    /**
     * Converts a string into a javascript object
     *
     * @param string response
     */
    parseJsonResponse: function(response) {
        this.data = eval("(" + response + ")");
    },

    /**
     * Create markers using routes object
     */
    addMarkers: function() {
        for (i = 0; i < this.data.route.length; i++) {
            this.data.route[i].cordinates = eval(this.data.route[i].cordinates);

            this.markers[i] = this.createMarker(this.data.route[i]);

            this.cordinates[i] = this.data.route[i].cordinates;
            this.gmap.addOverlay(this.markers[i]);
        }
    },

    /**
     * Create a distance marker between two points
     */
    addDistances: function() {
        for (i = 0; i < this.data.route.length; i++) {
            if ( (i+1) < jsonData.route.length ) {
                var pointA = jsonData.route[i].cordinates;
                var pointB = eval(jsonData.route[i+1].cordinates);
                console.debug(getDistance(pointA, pointB));
                //TODO: Add distance marker
            }

        }
    },


    /**
     * Defines plot icon
     *
     * @param string image
     * @return GIcon
     */
    makeIcon: function (image) {
        var icon = new GIcon();
        icon.image = image;
        icon.shadow = "../images/maps/aa-shadow.png";
        icon.iconSize = new GSize(32, 37);
        icon.shadowSize = new GSize(51, 37);
        icon.iconAnchor = new GPoint(16, 35);
        icon.infoShadowAnchor = new GPoint(0, 0);
        icon.infoWindowAnchor = new GPoint(16, 37);

        return icon;
    },

    /**
     * Gmaps bubble tabs
     *
     * @param string input text inside in a tab
     * @return string html
     */
    formatTabOne: function (input) {
	var html 	 = "<div class=\"bubble\">";
	html 		+= "<h4>" + input.point + "</h4>";		
	html		+= "<p>"
	html		+= "GPS <b>" + input.cordinates + "</b><br />"
	if(input.altitude != null) {
	html 		+= "Safe altitude <b>" + input.altitude + "ft.</b><br />";
	}
	html 		+= "Communicate: <b>" + input.frequency + "</b>";
	html		+= "</p></div>";					
	return html;			
    },

    /**
     * Gmail bubble second tab
     *
     * @param string input text inside in a tab
     * @return string html
     */
    formatTabTwo: function (input) {
        var html 	 = "<div class=\"bubble\">";
            html		+= "<p>"
        if(input.course != null) {
            html 	+= "Track to next point: <b>" + input.course + "&#186;</b><br />";
        }
        if(input.distance != null) {
            html 	+= "Distance to next point: <b>" + input.distance + "nm.</b><br />";
        }
        if(input.runway != null) {
            html 	+= "Runway: <b>" + input.runway + "</b><br />";
        }
        if(input.length != null) {
            html 	+= "Length: <b>" + input.length + "m.</b><br />";
        }
        if(input.ground != null) {
            html 	+= "Type: <b>" + input.ground + "</b><br />";
        }
        if(input.approach != null) {
            html 	+= "Approach: <b>" + input.approach + "</b><br />";
        }
        if(input.tower != null) {
            html 	+= "Tower: <b>" + input.tower + "</b><br />";
        }
        if(input.twrground != null) {
            html 	+= "Ground: <b>" + input.twrground + "</b>";
        }
        html 		+= "</p></div>";

        return html;
    },
	
    /**
     * Actual point in the map
     *
     * @param string input
     * @return Gmarker
     */
    createMarker: function (input) {

        var marker = new GMarker(input.cordinates, this.makeIcon(input.markerImage) );

        var tabs_array	= [ new GInfoWindowTab("Checkpoint", this.formatTabOne(input) ),
                            new GInfoWindowTab("Information", this.formatTabTwo(input) ) ];

        GEvent.addListener(marker, "click", function() {
                marker.openInfoWindowTabsHtml(tabs_array);
        });

        return marker;
    },

    midPoint: function (pointA, pointB) {
        var dLon = pointB.lng() - pointA.lng();

        var Bx = Math.cos(pointB.lat()) * Math.cos(dLon);
        var By = Math.cos(pointB.lat()) * Math.sin(dLon);

        lat3 = Math.atan2(Math.sin(pointA.lat())+Math.sin(pointB.lat()),

        Math.sqrt((Math.cos(pointA.lat())+Bx)*(Math.cos(pointA.lat())+Bx) + By*By ) );
        lon3 = pointA.lng() + Math.atan2(By, Math.cos(pointA.lat()) + Bx);

        if (isNaN(lat3) || isNaN(lon3)) return null;
            return new GLatLng(lat3*180/Math.PI, lon3*180/Math.PI);
    },

    /**
     * return gmaps bounds
     *
     * @return GLatLngBounds
     */
    getBounds: function () {
        var bounds = new GLatLngBounds();

        for(i = 0; i < this.markers.length; i++) {
            bounds.extend(this.markers[i].getLatLng());
        }
        return bounds;
    },

    /**
     * Calcualte disntance from two points
     *
     * @param GLatLng pointA
     * @param GLatLng pointB
     * @return double on meters format
     */
    getDistance: function (pointA, pointB){
        var distance = pointA.distanceFrom(pointB);

        return distance;
    },

    
    addDistanceMarkers: function() {
        for (var i = 0; this.markers.length; i++) {

            // validate if isn't the end of the array, to avoid a overflow
            if ((i+1) <= this.markers.length) {
                var pointA = this.markers[i].getLatLng();
                var pointB = this.markers[i+1].getLatLng();

                var distance = this.getDistance(pointA, pointB);

            }
        }
    },

    /**
     * Create plot lines between markers
     */
    addTrackLine: function () {
        var trackLine = new GPolyline(this.cordinates, "#E90E96", 5, 0.7);
        this.gmap.addOverlay(trackLine);
    }
}