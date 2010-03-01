<?php
# include your extended db class implementation
include('./class-sqlitedb.php');
#create a database object
$db = &new sqlitedb('./template.db');
# set some slice rendering attributes (see the method documentation for more info)
$db->set_slice_attrs(array( 'pages'=>"<a href=%lnk style=color:blue;text-decoration:none;>%page</a>",
                            'curpage'=>"<a href=%lnk style=color:red;text-decoration:none;>%page</a>",
                            'linkStr'=>"?page=%page&num=".(isset($_GET['num'])?$_GET['num']:10),
                            'linkSep'=>" / "
                          )
                    );
# get results as array (as always) + pages links + nb results all at once
list($res,$links,$nbres) = $db->select_array_slice('yourtable','*',null,
                                                  isset($_GET['page'])?$_GET['page']:10,
                                                  isset($_GET['num'])?$_GET['num']:10);

# now print the results
$cols = count($rows[0]);
if($res){
  foreach($res as $row){
    $rows[] = "<tr><td>".implode("</td><td",$row).'</td></tr>';
  }
}else{
  $rows[] = "<tr><td colspan=$cols>Aucun Résultats</td></tr>";
}
echo "<table>
  ".implode("\n  ",$rows)."
  <tr><td colspan=$cols>$links</td></tr>
</table>";
?>
