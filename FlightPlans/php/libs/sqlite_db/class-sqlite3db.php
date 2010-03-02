<?php
/**
* @author Jonathan Gotti <jgotti at jgotti dot org>
* @copyleft (l) 2008  Jonathan Gotti
* @package class-db
* @file
* @since 2008-04
* @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
* @svnInfos:
*            - $LastChangedDate: 2009-04-30 01:00:12 +0200 (jeu. 30 avril 2009) $
*            - $LastChangedRevision: 127 $
*            - $LastChangedBy: malko $
*            - $HeadURL: http://trac.jgotti.net/svn/class-db/trunk/adapters/class-sqlite3db.php $
* @changelog
*            - 2008-07-29 - suppress a bug to avoid some error while trying to destroy twice the same last_qres.
* @todo add transactions support
*/

/**
* exented db class to use with sqlite3 databases.
* require php sqlite3 extension to work
* @class sqlite3db
*/
class sqlite3db extends db{
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
		if( $this->db = sqlite3_open($this->db_file)){
			return $this->db;
		}else{
			$this->set_error(__FUNCTION__);
			return FALSE;
		}
	}
	/** close connection to previously opened database */
	function close(){
		if( !is_null($this->db) ){
			if($this->last_qres){
				sqlite3_query_close($this->last_qres);
				$this->last_qres = null;
			}
			sqlite3_close($this->db);
		}
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
		if($result_type==='ASSOC'){
			while($res[]=sqlite3_fetch_array($result_set));
			unset($res[count($res)-1]);//unset last empty row
		}elseif($result_type==='NUM'){
			while($res[]=sqlite3_fetch($result_set));
			unset($res[count($res)-1]);//unset last empty row
		}else{
			while($row=sqlite3_fetch_array($result_set)){
				$res[] = array_merge($row,array_values($row));
			};
		}
		if( empty($res) )
			return $this->last_q2a_res = false;
		$this->num_rows = count($res);
		return $this->last_q2a_res = $res;
	}

	function last_insert_id(){
		return $this->db?sqlite3_last_insert_rowid($this->db):FALSE;
	}

	/**
	* perform a query on the database
	* @param string $Q_str
	* @return result id or bool depend on the query type| FALSE
	*/
	function query($Q_str){
		if(! $this->db ){
			if(! (db::$autoconnect && $this->open()) )
				return FALSE;
		}
		$this->verbose($Q_str,__FUNCTION__,2);
		if($this->last_qres){#- close unclosed previous qres
			sqlite3_query_close($this->last_qres);
			$this->last_qres = null;
		}

		if( preg_match('!^\s*select!i',$Q_str) ){
			$this->last_qres = sqlite3_query($this->db,$Q_str);
			$res = $this->last_qres;
		}else{
			$res = sqlite3_exec($this->db,$Q_str);
		}
		if(! $res)
			$this->set_error(__FUNCTION__);

		return $res;
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
		return sqlite3_changes($this->db);
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

	/**
	* base method you should replace this one in the extended class, to use the appropriate escape func regarding the database implementation
	* @param string $quotestyle (both/single/double) which type of quote to escape
	* @return str
	*/
	function escape_string($string,$quotestyle='both'){

		if( function_exists('sqlite_escape_string') ){
			$string = sqlite_escape_string($string);
			$string = str_replace("''","'",$string); #- no quote escaped so will work like with no sqlite_escape_string available
		}else{
			$escapes = array("\x00", "\x0a", "\x0d", "\x1a", "\x09","\\");
			$replace = array('\0',   '\n',    '\r',   '\Z' , '\t',  "\\\\");
		}
		switch(strtolower($quotestyle)){
			case 'double':
			case 'd':
			case '"':
				$escapes[] = '"';
				$replace[] = '\"';
				break;
			case 'single':
			case 's':
			case "'":
				$escapes[] = "'";
				$replace[] = "''";
				break;
			case 'both':
			case 'b':
			case '"\'':
			case '\'"':
				$escapes[] = '"';
				$replace[] = '\"';
				$escapes[] = "'";
				$replace[] = "''";
				break;
		}
		return str_replace($escapes,$replace,$string);
	}

	function error_no(){
		$this->verbose('sqlite3 driver doesn\'t support this method',__function__,1);
	}

	function error_str($errno=null){
		return sqlite3_error($this->db);
	}

	protected function set_error($callingfunc=null){
		static $i=0;
		if(! $this->db ){
			$this->error[$i] = '[ERROR] No Db Handler';
		}else{
			$this->error[$i] =  $this->error_str();
		}
		$this->last_error = $this->error[$i];
		$this->verbose($this->error[$i],$callingfunc,1);
		$i++;
	}
}
