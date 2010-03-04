<?php
// PHP Proxy example for Yahoo! Web services.
// Responds to both HTTP GET and POST requests
//
// Author: Jason Levitt
// December 7th, 2005
//


// Get the REST call path from the AJAX application
$url = $_GET['url'];
// Open the Curl session
$session = curl_init($url);

// If it's a POST, put the POST data in the body
if ($_GET['url']) {
	$postvars = '';
	while ($element = current($_POST)) {
		$postvars .= key($_POST).'='.$element;
		next($_POST);
	}



	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($session,CURLOPT_REFERER,'https://www.notams.jcs.mil/dinsQueryWeb/');
        curl_setopt( $session, CURLOPT_FOLLOWLOCATION, false );
}

// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

// Make the call
$xml = curl_exec($session);
$response = curl_getinfo( $session );

// The web service returns XML. Set the Content-Type appropriately
//header("Content-Type: text/html");
preg_match_all('/<pre.*?>(.*?)<\/pre>/imsu', $xml, $filtered);

$filtered = implode("<br>", $filtered[0]);
preg_match_all('/<pre.*?>(.*?)<\/pre>/imsu', $xml, $filtered);

$filtered = implode("<br>", $filtered[0]);
$filtered = str_ireplace("<pre>", "<span>", $filtered);
$filtered = str_ireplace("</pre>", "</span>", $filtered);

if (empty($filtered))
	echo "<p style='text-align:center'>No data available</p>";
else
	echo $filtered;
curl_close($session);