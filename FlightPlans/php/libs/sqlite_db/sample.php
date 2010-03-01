<?php
# simple example script to show how to use sqlitedb objects
include_once('class-sqlite.php');
# create sqlitedb object
$db = &new sqlitedb('mydbfile.db');
# get an array of selected datas
$result_array = $db->select_to_array('tablename','*','ORDER BY ID');
# get a single row as an array
$row_array  = $db->select_single_to_array('tablename','*',"WHERE id =1 ");
# access a single value in the database
$single_val =  $db->select_single_value('tablename','colname',"WHERE id = 1");
# get results as associative arrays indexed on the id field (or any other field of your choice)
$indexed_array = $db->select2associative_array('tablename','*',$conds=null,$index_field='id');
# use the new 'smart question mark' feature
$datas = $db->select_to_array('tablename','*',array('WHERE id = ? AND othercol = ?',$idval,$authercolval));
# same as above but precising which data to use in which place
$datas = $db->select_to_array('tablename','*',array('WHERE id = 2? AND othercol = 1?',$authercolval,$idval)); 
# perform insert update or delete
$db->insert('tablename',array('colname'=>'newvalue'));
$db->update('tablename',array('colname'=>'newvalue'),"WHERE id=1");
$db->delete('tablename',"WHERE id=1");
# and close connection
$db->close();
# note: usage of where clause as an array is not ready to use in my opinion,
# if u think this feature can be cool, drop me a line about how you intend to use it.
?>