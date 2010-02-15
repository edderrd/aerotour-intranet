function load () {
	
	var map = document.getElementById("map");
	
	if (GBrowserIsCompatible()) {

		var gmap = new GMap2(map);
//		gmap.addControl( new GOverviewMapControl(new GSize(200,200)) );
		gmap.setUIToDefault();
		gmap.removeMapType(G_SATELLITE_MAP);
		gmap.setCenter(new GLatLng(9.95803, -84.141541), 12);

		
		function makeIcon (image) {
			var icon = new GIcon();
			icon.image = image;
			icon.shadow = "../images/maps/dd-shadow.png";
			icon.iconSize = new GSize(20, 34);
			icon.shadowSize = new GSize(37, 34);
			icon.iconAnchor = new GPoint(18, 30);
			icon.infoShadowAnchor = new GPoint(0, 0);
			icon.infoWindowAnchor = new GPoint(8, 1);	
			return icon;
		}
		
		function formatTabOne (input) {				
			var html 	 = "<div class=\"bubble\">";
			html 		+= "<h3>" + input.point + " : " + input.cordinates + "</h3>";			
			html 		+= "<h4>Frequency: " + input.frequency + "</h4>";
			html		+= "</div>";					
			return html;			
		}
		
		function formatTabTwo (input) {
			var html 	 = "<div class=\"bubble\">";
			html 		+= "<h3>Safe altitude " + input.altitude + "ft.</h3>";
			html		+= "<p>"
			if(input.course != null) {
				html 	+= "<strong>Track to next point: </strong> " + input.course + "<br />";
			}		
			if(input.distance != null) {
				html 	+= "<strong>Distance to next point: </strong> " + input.distance + "<br />";
			}
			html 		+= "</p></div>";					
			return html;			
		}
					
	    function createMarker(input) {
		
			var marker = new GMarker(input.cordinates, makeIcon(input.markerImage) );

			var tabs_array	= [ new GInfoWindowTab("Checkpoint", formatTabOne(input) ),
			 		    new GInfoWindowTab("Information", formatTabTwo(input) ) ];
						
			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowTabsHtml(tabs_array);
			});
			
			return marker;
		}

		function parseJson (doc) {
						
			var jsonData = eval("(" + doc + ")")
					
	        for (var i = 0; i < jsonData.route.length; i++) {
				var marker = createMarker(jsonData.route[i]);
				gmap.addOverlay(marker);
			}
		}
		
		function createTrack() {
			var trackLine = new GPolyline(cordinates, "#000077", 3, 0.7);
			gmap.addOverlay(trackLine);
		}
		
		
		GDownloadUrl("../data/MRPV-MRQP.txt", function(data, responseCode) { 
			parseJson(data);
		});
	
	} else {
		alert("Sorry, your browser cannot handle the true power of Google Maps");
	}
}
window.onload = load;
window.onunload = GUnload;