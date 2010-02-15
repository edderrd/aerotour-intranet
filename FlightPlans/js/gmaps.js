/**
 * Defines plot icon
 * 
 * @param string image
 * @return GIcon
 */
function makeIcon (image) {
	var icon = new GIcon();
	icon.image = image;
	icon.shadow = "../images/maps/aa-shadow.png";
	icon.iconSize = new GSize(32, 37);
	icon.shadowSize = new GSize(51, 37);
	icon.iconAnchor = new GPoint(16, 35);
	icon.infoShadowAnchor = new GPoint(0, 0);
	icon.infoWindowAnchor = new GPoint(16, 37);	
	return icon;
}

/**
 * Gmaps bubble tabs
 * 
 * @param string input text inside in a tab
 * @return string html
 */
function formatTabOne (input) {				
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
}

/**
 * Gmail bubble second tab
 * 
 * @param string input text inside in a tab
 * @return string html
 */
function formatTabTwo (input) {
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
		html 	+= "Type: <b>" + input.ground + "</b>";
	}
	html 		+= "</p></div>";					
	return html;			
}
	
/**
 * Actual point in the map
 * 
 * @param string input
 * @return Gmarker
 */		
function createMarker(input) {

	var marker = new GMarker(input.cordinates, makeIcon(input.markerImage) );

	var tabs_array	= [ new GInfoWindowTab("Checkpoint", formatTabOne(input) ),
	 		    new GInfoWindowTab("Information", formatTabTwo(input) ) ];
				
	GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowTabsHtml(tabs_array);
	});
	
	return marker;
}

/**
 * Convert data into gmap overlay
 * 
 * @param json data
 * @return 
 */
function parseJson (gmap, data) {
				
	var jsonData = eval("(" + data + ")");
	var cordinates = new Array();
	
	for (i = 0; i < jsonData.route.length; i++) {
		var marker = createMarker(jsonData.route[i]);
		cordinates[i] = jsonData.route[i].cordinates;
		gmap.addOverlay(marker);
	}
	
	createTrack(cordinates, gmap);
	
	return gmap;
}

/**
 * Create plot lines between
 * 
 * @param Gmap gmap
 * @return Gmap gmap
 */
function createTrack(cordinates, gmap) {

	var trackLine = new GPolyline(cordinates, "#E90E96", 5, 0.7);
	gmap.addOverlay(trackLine);
	
	return gmap;
}

