<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"> 
	<head> 
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
		<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAQ2Bjps73vB8iCOm6sHY0exRXVcmsZpYJoXnJ-v26hVmq22y-QhT5PPnE1PQfJ8gxxNJollpRIpN1wQ" type="text/javascript"></script> 
		<script src="../js/gmaps.js" type="text/javascript"></script>
		<script type="text/javascript">
			var map = document.getElementById("map");
			function initialize() {
				if (GBrowserIsCompatible()) {
				
					var gmap = new GMap2(document.getElementById("map"));
					
					GDownloadUrl("../data/MRPV-<?= $_GET['point']?>.txt", function(data, responseCode) { 
						parseJson(gmap, data);	
					});

					gmap.setUIToDefault();
					gmap.removeMapType(G_SATELLITE_MAP);
					gmap.disableScrollWheelZoom();
					gmap.setCenter(new GLatLng(9.884981311823843, -84.078369140625), 9);
			
				} else {
					map.innerHtml = "<h1>Sorry, your browser cannot handle the true power of Google Maps</h1>";
				}
			}
		</script>
	</head> 
	<body onload="initialize()" onunload="GUnload()"> 
		<div id="map" style="width: 100%; height: 800px;"></div>
	</body> 
</html> 