<?php

$domain = $_SERVER['HTTP_HOST'];
$subFolder = $_SERVER['REDIRECT_URL'];
$destination = "/FlightPlans/";

$destUrl = "http://$domain" . $subFolder . $destination;

header("Location: $destUrl");

?>
