<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
		<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAQ2Bjps73vB8iCOm6sHY0exRXVcmsZpYJoXnJ-v26hVmq22y-QhT5PPnE1PQfJ8gxxNJollpRIpN1wQ" type="text/javascript"></script>
	</head>
	<body>
		<div id="map" style="width: 100%; height: 640px"></div>
		<script type="text/javascript">
		
		    function setWaypoint(latitude, logitud, legend, waypointIcon) {
		        var waypoint = new GLatLng(latitude, logitud);
			    var marker = new GMarker(waypoint, waypointIcon);
				
				map.addOverlay(marker);
				GEvent.addListener(marker, "click", function() {
				    marker1.openInfoWindowHtml(legend);
				});
		    }
		
			//<![CDATA[
			
			// Disable use of SVG because lines disappear in Firefox at high zoom.
			// TODO Review and reenable.
			_mSvgEnabled=false;
			
			var waypointIcon = new GIcon();
			waypointIcon.image = "../images/maps/gmap_point_blue.png";
			waypointIcon.shadow = "../images/maps/gmap_point_blue.png";
			waypointIcon.iconSize = new GSize(11, 11);
			waypointIcon.shadowSize = new GSize(11, 11);
			waypointIcon.iconAnchor = new GPoint(5, 5);
			waypointIcon.infoWindowAnchor = new GPoint(5, 1);
			
			var startPointIcon = new GIcon(waypointIcon);
			startPointIcon.image = "../images/maps/gmap_point_green.png";

			var endPointIcon = new GIcon(waypointIcon);
			endPointIcon.image = "../images/maps/gmap_point_red.png";
			
			var infoIcon = new GIcon();
			infoIcon.image = "../images/maps/gmap_info.png";
			infoIcon.shadow = "../images/maps/gmap_info.png";
			infoIcon.iconSize = new GSize(9, 9);
			infoIcon.shadowSize = new GSize(9, 9);
			infoIcon.iconAnchor = new GPoint(4, 4);
			infoIcon.infoWindowAnchor = new GPoint(5, 1);

			var map = new GMap2(document.getElementById("map"));
			//map.addControl(new GLargeMapControl3D(), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(10,70)));
			//map.addControl(new GMapTypeControl(), new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10)));
			map.setCenter(new GLatLng(9.95803, -84.141541), 12);
			map.setUIToDefault();
			map.removeMapType(G_SATELLITE_MAP);
	
			var waypoints = new Array(5);
			waypoints.push(setWaypoint(9.957819, -84.142399, "<strong>MRPV</strong><br/>9.957819 -84.142399W"));

			var waypoint0 = new GLatLng(9.957819, -84.142399);
			waypoints[0] = waypoint0;
			var marker0 = new GMarker(waypoint0, startPointIcon);
			map.addOverlay(marker0);
			GEvent.addListener(marker0, "click", function() {
				marker0.openInfoWindowHtml(
					"<strong>MRPV</strong><br/>9.957819 -84.142399W"
				);
			});
			
				var waypoint1 = new GLatLng(9.930131, -84.360924);
				waypoints[1] = waypoint1;
 				
						var marker1 = new GMarker(waypoint1, waypointIcon);
						
					map.addOverlay(marker1);
					GEvent.addListener(marker1, "click", function() {
						marker1.openInfoWindowHtml(
							"<strong>BALSA</strong><br/>9.930131N -84.360924W"
						);
					});
			
				var waypoint2 = new GLatLng(9.912546, -84.519281);
				waypoints[2] = waypoint2;
 				
						var marker2 = new GMarker(waypoint2, waypointIcon);
						
					map.addOverlay(marker2);
					GEvent.addListener(marker2, "click", function() {
						marker2.openInfoWindowHtml(
							"<strong>OROTINA</strong><br/>9.912546N -84.519281W"
						);
					});
					
				var waypoint3 = new GLatLng(9.796693, -84.604340);
				waypoints[3] = waypoint3;
 				
						var marker3 = new GMarker(waypoint3, waypointIcon);
						
					map.addOverlay(marker3);
					GEvent.addListener(marker3, "click", function() {
						marker3.openInfoWindowHtml(
							"<strong>RIO TARCOLES</strong><br/>9.796693N -84.604340W"
						);
					});
			
				var waypoint4 = new GLatLng(9.443431, -84.13034);
				waypoints[4] = waypoint4;
 				
						var marker4 = new GMarker(waypoint4, endPointIcon);
						
					map.addOverlay(marker4);
					GEvent.addListener(marker4, "click", function() {
						marker4.openInfoWindowHtml(
							"<strong>MRQP</strong><br/>9.443431N -84.13034W"
						);
					});
				


				
			var bounds = new GLatLngBounds(new GLatLng(8.341953, -85.665897), new GLatLng(10.914224, -83.040161));
			var center = bounds.getCenter();
			var zoomLevel = map.getBoundsZoomLevel(bounds);
			map.setCenter(center, zoomLevel, GMapType.G_NORMAL_MAP);

			var trackLine = new GPolyline(waypoints, "#E90E96", 5, 0.7);
			map.addOverlay(trackLine);
			
			//]]>
		</script>
		
	</body>
</html>
