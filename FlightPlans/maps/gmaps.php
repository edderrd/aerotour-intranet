<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"> 
	<head> 
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
		<!--<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAQ2Bjps73vB8iCOm6sHY0exRXVcmsZpYJoXnJ-v26hVmq22y-QhT5PPnE1PQfJ8gxxNJollpRIpN1wQ" type="text/javascript"></script> -->
                <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAFW8NgZfoxFc3Ht4HxIygBRTCv78iwvdsoKGinnS7fKaSmV9tzhT0xVaw1b-i4zI5DRJgo--17f5TBw" type="text/javascript"></script>
                <script src="../js/maps.js" type="text/javascript"></script>
		<script type="text/javascript">
			
                        function initialize() {
                            var map = document.getElementById("map");

                            if (GBrowserIsCompatible()) {
                                GDownloadUrl("../data/MRPV-<?= $_GET['point']?>.txt", function(response, responseCode) {
                                    
                                    Maps.init(map, response);
                                });
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

