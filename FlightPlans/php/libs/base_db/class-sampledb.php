<?php
/**
* Ssqeleton class to construct an extended db class.
* @author Jonathan Gotti <nathan at the-ring dot homelinux dot net>
* @copyleft (l) 2004-2005  Jonathan Gotti
* @package DB
* @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
*/

class sampledb extends db{
	/** open connection to database */
	function open(){}
	/** close connection to previously opened database */
  function close(){}
	/**
  * Select the database to work on (it's the same as the use db command or mysql_select_db function)
  * @param string $dbname
  * @return bool
	* check if applicable to your database implementation (exemple unusable for sqlite)
  */
  function select_db($dbname=null){} 
	/** 
	* take a resource result set and return an array of type 'ASSOC','NUM','BOTH' 
	* @see sqlitedb or mysqldb implementation for exemple
	* @return array
	*/
	function fetch_res($result_set,$result_type){}
	function last_insert_id(){}
	/**
	* there's a base method you should replace in the extended class, to use the appropriate escape func regarding the database implementation
	* @param string $quotestyle (both/single/double) which type of quote to escape
	* @return str
	*/
	function escape_string($string,$quotestyle='both'){}
	/**
  * perform a query on the database
  * @param string $Q_str
  * @return= result id | FALSE
  **/
  function query($Q_str){}
	/**
  * perform a query on the database like query but return the affected_rows instead of result
  * give a most suitable answer on query such as INSERT OR DELETE
  * @param string $Q_str
  * @return int affected_rows or FALSE on error!
	* @can work without this method but less smart
	*/
	function query_affected_rows($Q_str){}
	/**
  * get the table list from $this->dbname
  * @return array
  */
  function list_tables(){}
	/** Verifier si cette methode peut s'appliquer a SQLite */
  function show_table_keys($table){}
  /**
  * optimize table statement query
  * @param string $table name of the table to optimize
  * @return bool
  */
  function optimize($table){}
  function error_no(){}
  function error_str($errno=null){}
  function __destruct(){
    parent::__destruct();
  }
}
?>