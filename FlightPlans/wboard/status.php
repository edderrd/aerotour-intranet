<?php


$dbname='../data/flights.sqlite';
$mytable ="schedule";


$base=new SQLiteDatabase($dbname, 0666, $err);
if ($err)  exit($err);


$myid = $_GET['id'];
$changed= $_GET['status'];


$query = "UPDATE $mytable SET status = '$changed' WHERE (id=$myid)";
$results = $base->queryexec($query);


echo "Updating the post with ID $myid... <br><br>\n";
echo "New content: <hr>$changed<hr><br>\n";


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
