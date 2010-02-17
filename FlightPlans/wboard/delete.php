<?php


$dbname='../data/flights.sqlite';
$mytable ="schedule";


$base=new SQLiteDatabase($dbname, 0666, $err);
if ($err)  exit($err);


$myid = $_GET['id'];

$query = "DELETE FROM $mytable WHERE (id=$myid)";
$results = $base->queryexec($query);


echo "Deleting the post with ID $myid... <br><br>\n";


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
