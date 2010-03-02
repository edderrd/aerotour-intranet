<?
include "../php/class.flights.php";
include "../php/configuration.php";

$flight = new Flight();
$flight->fstatus = $_POST['fstatus'];
$flight->fdate = $_POST['fdate'];
$flight->route = $_POST['route'];
$flight->pax = $_POST['pax'];
$flight->tail = $_POST['tail'];
$flight->pilot = $_POST['pilot'];
$flight->client = $_POST['client'];
$flight->leaves = $_POST['leaves'];
$flight->returns = $_POST['returns'];
$flight->fuel = $_POST['fuel'];
$flight->notes = $_POST['notes'];
if ($flight->Save())
{
	echo "Flight saved successfully!";
}
else
{
	echo "Flight not saved";
}

?>