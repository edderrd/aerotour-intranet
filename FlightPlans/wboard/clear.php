<?php


$dbname='../data/flights.sqlite';
$mytable ="schedule";


$base=new SQLiteDatabase($dbname, 0666, $err);
if ($err)  exit($err);


$date = date("m/d/Y");

$query = "DELETE FROM $mytable WHERE date < '$date'";
$results = $base->queryexec($query);


echo "Deleting the post with date lower than $date... <br><br>\n";


if($results)
{
  echo "<i>$mytable</i> updated.<br>\n";
  header ("Location: add.php");
}
else
{
  echo "Can't access $mytable table.<br>\n";
}
   echo "<hr>";

?>
