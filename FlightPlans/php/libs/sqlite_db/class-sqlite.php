<?php
/**
* @author Jonathan Gotti <jgotti at jgotti dot org>
* @copyleft (l) 2003-2008  Jonathan Gotti
* @package DB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @subpackage SQLITE
* @changelog - 2008-04-06 - no more $mode parameter to construct the database (not managed by the extension at all so drop it)
*                         - drop php4 support, and buffered query are no longer supported (was useless as db has it's own buffer)
*                         - autoconnect is now a static property
*                         - add check_conn method
*            - 2006-05-12 - clean the escape_string() method
*            - 2006-04-17 - rewrite the class to use abstarction class db
*                         - Conditions params support on methods select_*, update, delete totally rewrite to handle smat question mark
*                           @see db::process_conds()
*                         - get_field and list_fields are now deprecated but still supported (listfield will be indexed by name whatever is $indexed_by_name)
*            - 2005-02-25 - now the associative_array_from_q2a_res method won't automaticly ksort the results anymore
*                         - re-enable the possibility to choose between SQLITE_ASSOC or SQLITE_NUM
*            - 2005-02-28 - new method optimize and vacuum
*            - 2005-04-05 - get_fields will now try to get fields from sqlite_master if no data found in the table
* @todo add transactions support (you can use it on your own with query())
*/

class sqlitedb extends db{
	public $autocreate= TRUE;

	public $db_file = '';
	public $_protect_fldname = "'";
	/**
	* create a sqlitedb object for managing locale data
	* if DATA_PATH is define will force access in this directory
	* @param string $Db_file
	* @return sqlitedb object
	*/
	function __construct($db_file){
		$this->host = 'localhost';
		$this->db_file = $db_file;
		$this->conn = &$this->db; # only for better compatibility with other db implementation
		if(db::$autoconnect)
			$this->open();
	}
	###*** REQUIRED METHODS FOR EXTENDED CLASS ***###
	/** open connection to database */
	function open(){
		//prevent multiple db open
		if($this->db)
			return $this->db;
		if(! $this->db_file )
			return FALSE;
		if(! (is_file($this->db_file) || $this->autocreate) )
			return FALSE;
		if( $this->db = sqlite_open($this->db_file, 0666, $error)){
			return $this->db;
		}else{
			$this->verbose($error,__FUNCTION__,1);
			return FALSE;
		}
	}

	/** close connection to previously opened database */
	function close(){
		if( !is_null($this->db) )
			sqlite_close($this->db);
		$this->db = null;
	}

	/**
	* check and activate db connection
	* @param string $action (active, kill, check) active by default
	* @return bool
	*/
	function check_conn($action = ''){
		if(! $this->db){
			if($action !== 'active')
				return $action==='kill'?true:false;
			return $this->open()===false?false:true;
		}else{
			if($action==='kill'){
				$this->close();
				$this->db = null;
			}
			return true;
		}
	}

	/**
	* take a resource result set and return an array of type 'ASSOC','NUM','BOTH'
	* @param resource $result_set
	* @param string $result_type in 'ASSOC','NUM','BOTH'
	*/
	function fetch_res($result_set,$result_type='ASSOC'){
		$result_type = strtoupper($result_type);
		if(! in_array($result_type,array('NUM','ASSOC','BOTH')) )
			$result_type = 'ASSOC';
		eval('$result_type = SQLITE_'.$result_type.';');

		while($res[]=sqlite_fetch_array($result_set,$result_type));
		unset($res[count($res)-1]);//unset last empty row

		#- ~ $this->num_rows = sqlite_num_rows($this->last_qres);
		$this->num_rows = count($res);

		return $this->last_q2a_res = count($res)?$res:FALSE;
	}

	function last_insert_id(){
		return $this->db?sqlite_last_insert_rowid($this->db):FALSE;
	}

	/**
	* perform a query on the database
	* @param string $Q_str
	* @return result id | FALSE
	*/
	function query($Q_str){
		if(! $this->db ){
			if(! (db::$autoconnect && $this->open()) )
				return FALSE;
		}
		$this->verbose($Q_str,__FUNCTION__,2);
		$this->last_qres = sqlite_unbuffered_query($this->db,$Q_str);
		if(! $this->last_qres)
			$this->set_error(__FUNCTION__);
		return $this->last_qres;
	}

	/**
	* perform a query on the database like query but return the affected_rows instead of result
	* give a most suitable answer on query such as INSERT OR DELETE
	* Be aware that delete without where clause can return 0 even if several rows were deleted that's a sqlite bug!
	*    i will add a workaround when i'll get some time! (use get_count before and after such query)
	* @param string $Q_str
	* @return int affected_rows
	*/
	function query_affected_rows($Q_str){
		if(! $this->query($Q_str) )
			return FALSE;
		return sqlite_changes($this->db);
	}

	/**
	* return the list of field in $table
	* @param string $table name of the sql table to work on
	* @param bool $extended_info if true will return the result of a show field query in a query_to_array fashion
	*                           (indexed by fieldname instead of int if false)
	* @return array
	*/
	function list_table_fields($table,$extended_info=FALSE){
		# Try the simple method
		if( (! $extended_info) && $res = $this->query_to_array("SELECT * FROM $table LIMIT 0,1")){
			return array_keys($res[0]);
		}else{ # There 's no row in this table so we try an alternate method or we want extended infos
			if(! $fields = $this->query_to_array("SELECT sql FROM sqlite_master WHERE type='table' AND name ='$table'") )
				return FALSE;
			# get fields from the create query
			$flds_str = $fields[0]['sql'];
			$flds_str = substr($flds_str,strpos($flds_str,'('));
			$type = "((?:[a-z]+)\s*(?:\(\s*\d+\s*(?:,\s*\d+\s*)?\))?)?\s*";
			$default = '(?:DEFAULT\s+((["\']).*?(?<!\\\\)\\4|[^\s,]+))?\s*';
			if( preg_match_all('/(\w+)\s+'.$type.$default.'[^,]*(,|\))/i',$flds_str,$m,PREG_SET_ORDER) ){
				$key  = "PRIMARY|UNIQUE|CHECK";
				$Extra = 'AUTOINCREMENT';
				$default = 'DEFAULT\s+((["\'])(.*?)(?<!\\\\)\\2|\S+)';
				foreach($m as $v){
					list($field,$name,$type,$default) = $v;
					# print_r($field);
					if(!$extended_info){
						$res[] = $name;
						continue;
					}
					$res[$name] = array('Field'=>$name,'Type'=>$type,'Null'=>'YES','Key'=>'','Default'=>$default,'Extra'=>'');
					if( preg_match("!($key)!i",$field,$n))
						$res[$name]['Key'] = $n[1];
					if( preg_match("!($Extra)!i",$field,$n))
						$res[$name]['Extra'] = $n[1];
					if( preg_match('!(NO)T\s+NULL!i',$field,$n))
						$res[$name]['Null'] = $n[1];
				}
				return $res;
			}
			return FALSE;
		}
	}
	/**
	* get the table list
	* @return array
	*/
	function list_tables(){
		if(! $tables = $this->query_to_array('SELECT name FROM sqlite_master WHERE type=\'table\'') )
			return FALSE;
		foreach($tables as $v){
			$ret[] = $v['name'];
		}
		return $ret;
	}

	/** Verifier si cette methode peut s'appliquer a SQLite * /
	function show_table_keys($table){}

	/**
	* optimize table statement query
	* @param string $table name of the table to optimize
	* @return bool
	*/
	function optimize($table){
		return $this->vacuum($table);
	}
	/**
	* sqlitedb specific method to use the vacuum statement (used as replacement for mysql optimize statements)
	* you should use db::optimize() method instead for better portability
	* @param string $table_or_index name of table or index to vacuum
	* @return bool
	*/
	function vacuum($table_or_index){
		return $this->query("VACUUM $table_or_index;");
	}

	function error_no(){
		return $this->db?sqlite_last_error($this->db):FALSE;
	}

	function error_str($errno=null){
		return sqlite_error_string($errno);
	}

	/**
	* base method you should replace this one in the extended class, to use the appropriate escape func regarding the database implementation
	* @param string $quotestyle (both/single/double) which type of quote to escape
	* @return str
	*/
	function escape_string($string,$quotestyle='both'){
		$string = sqlite_escape_string($string);
		switch(strtolower($quotestyle)){
			case 'double':
			case 'd':
			case '"':
				$string = str_replace("''","'",$string);
				$string = str_replace('"','\"',$string);
				break;
			case 'single':
			case 's':
			case "'":
				break;
			case 'both':
			case 'b':
			case '"\'':
			case '\'"':
				$string = str_replace('"','\"',$string);
				break;
		}
		return $string;
	}
}
