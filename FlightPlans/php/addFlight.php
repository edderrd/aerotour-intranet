<?php

$dbname='../data/flights.sqlite';
$mytable ="schedule";

$base=new SQLiteDatabase($dbname, 0666, $err);
if ($err)  exit($err);
		
$fstatus = $_POST['fstatus'];
$fdate = $_POST['fdate'];
$route = $_POST['route'];
$pax = $_POST['pax'];
$tail = $_POST['tail'];
$pilot = $_POST['pilot'];
$client = $_POST['client'];
$leaves = $_POST['leaves'];
$returns = $_POST['returns'];
$fuel = $_POST['fuel'];
$notes = $_POST['notes'];

$query = "INSERT INTO schedule(status, date, route, pax, tail, pilot, client, leaves, returns, fuel, notes) 
                VALUES ('$fstatus', '$fdate', '$route', '$pax', '$tail', '$pilot', '$client', '$leaves', '$returns', '$fuel', '$notes')";
//echo "$query<br>";
$results = $base->queryexec($query);
if(!$results)
{
  echo "<i>$mytable</i> not updated <br>\n";
  exit(0);
}
echo "Data entered into <i>$mytable</i> successfully<br>\n";


?>