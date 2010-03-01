<?php
/**
* Base class for databases object.
* @author Jonat<?php
/**
* @package class-db
* @file
* @author Jonathan Gotti <jgotti at jgotti dot org>
* @copyleft (l) 2003-2008  Jonathan Gotti
* @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
* @since 2006-04-16 first splitted version
*  - get_field and list_fields have changed -> list_table_fields (list_fields indexed by name)
*  - smart '?' on conditions strings
* @svnInfos:
*            - $LastChangedDate: 2009-04-30 11:12:52 +0200 (jeu. 30 avril 2009) $
*            - $LastChangedRevision: 134 $
*            - $LastChangedBy: malko $
*            - $HeadURL: http://trac.jgotti.net/svn/class-db/trunk/class-db.php $
* @changelog
*            - 2009-12-14 - add some cleanup to profiler reports (htmlentities)
*                         - new sliceAttrs (first|prev|next|last)Disabled + htmlentities replace default prev|next|first|last values
*                         - replace verbose msgs by DIV instead of B tags
*            - 2009-04-03 - add one level trace context information to dbProfiler reports
*            - 2009-02-06 - add css class dbMsg to verbose messages
*            - 2008-10-10 - new static property $_default_verbosity to set default beverbose value of any further new db instance
*            - 2008-07-30 - some minor changes in dbProfiler report representation and bug correction in colors
*            - 2008-04-14 - add location of queries and some colors to dbProfiler
*            - 2008-04-10 - new class dbprofiler
*            - 2008-04-06 - autoconnect is now a static property
*                         - now db::getInstance call require on missing class-xxxxdb.php
*            - 2008-02-19 - now get_count() method can receive optional clause as second parameter
*                         - add method _call() and static property db::$aliases to support methods aliases to be user defined (you can set your own aliases for any methods)
*                         - rename most of select_* methods with 'better' name (shorter and better meaning I hope) But old names will work just fine with new methods aliases support
*            - 2007-11-20 - now beverbose property is int instead of bool and can takes 4 values
*                           0 -> no output, 1-> only errors, 2-> only queries, 3-> queries + errors
*                           (don't forget to change true by 1 in your scripts or you won't have expected output anymore)
*                         - remove call to set_error() in query_to_array only query has to do it
*            - 2007-10-11 - change default curpage for an input in set_slice_attrs()
*                         - no more autoload of console_app when not in sapi cli
*            - 2007-10-08 - new methods getInstance, setDefaultConnectionStr, and __destruct
*                           to ease the way of getting uniques db instances for each db
*            - 2007-03-28 - protect_field_names() isn't called automaticly anymore to allow
*                           the use of function, wild char or alias in fields list.
*                           will perhaps permit this again with a more effective regex in the future.
*                           so at this time it's up to you to use this method as needed on any select_* method (still applyed for insert and update)
*                         - move last_q2a_res assignment from fetch_res() method to query_to_array() (seems more ligical to me)
*            - 2007-03-26 - better fields name handling (auto-protect fieldsname even if string is given)
*            - 2007-01-12 - better values type handling in update and insert methods
*            - 2007-01-10 - correct a bug about page counting in set_slice_attrs() and add %page replacement to fromatStr
*                         - better params type handling in method process_conds()  (int/string/array/null)
*            - 2006-12-05 - new method select_field_to_array()
*            - 2006-05-15 - new methods set_slice_attrs() and select_array_slice() to easily paginate your results
*/

/**
* this class is an encapsulator for any db extended class and is used to help profiling at developpment time.
* @class dbProfiler
* @see db
* @code
* // instanciate and encapsulate a db class
* $db = new dbProfiler(db::getInstance());
* // do your normal job as if you where working with any other class-db extended object
* $db->list_tables();
* //then when finished work print the report
* $db->printReport();
* @endcode
*/
class dbProfiler{
	static public $precision = 4;

	static public $stats     = array();
	protected $db            = null;
	protected $statFuncs     = null;

	function __construct($dbInstance){
		$this->db  = $dbInstance;
		$this->statFuncs = array_merge(array_keys(db::$aliases),array_values(db::$aliases),array('delete','update','insert','query','query_to_array','list_tables','list_table_fields','show_table_keys','optimize','get_count'));
	}

	function __get($k){
		return $this->db->$k;
	}

	function __set($k,$v){
		return $this->db->$k = $v;
	}

	function __call($m,$a){
		if(! in_array($m,$this->statFuncs))
			return call_user_func_array(array($this->db,$m),$a);
		# - get stat on queries
		$trace = debug_backtrace();
		if(! isset($trace[2])){
			$traceContext='';
		}else{
			if(empty($trace[2]['type']))
				$traceContextObject = '';
			else
				$traceContextObject = (($trace[2]['type']=='::'?$trace[2]['class']:get_class($trace[2]['object'])).$trace[2]['type']);
			$traceContext = $traceContextObject.$trace[2]['function'].'(...)'.(isset($trace[2]['file'])?' in '.basename($trace[2]['file']).' ('.$trace[2]['line'].')':'');
		}
		$trace = basename($trace[1]['file']).' ('.$trace[1]['line'].')';
		if(! empty($traceContext) )
			$trace = "<abbr title=\"$traceContext\">$trace</abbr>";
		$stat = array(
			$trace,
			"<b>$m</b>(".implode('<b>,</b> ',array_map(array($this,'_prepareArgStr'),$a)).')',
			$this->get_microtime()
		);
		$res  = call_user_func_array(array($this->db,$m),$a);
		$stat[] = $this->get_microtime();
		if( $res === false){
			$stat[0] = '<span style="color:red;">'.$stat[0].'</span>';
		}
		self::$stats[] = $stat;
		return $res;
	}

	function _prepareArgStr($a){
		static $charset;
		if(! $charset){
			$charset = ini_get('default_charset');
		}
		return stripslashes(htmlentities(var_export($a,1),ENT_NOQUOTES,$charset));
	}

	static function printReport(){
		if(! count(self::$stats) )
			return;
		$total = 0;
		foreach(self::$stats as $stat){
			list($locInfo,$query,$start,$end) = $stat;
			$time = round($end-$start,self::$precision);
			$rows[] = '<tr><td style="border-bottom:solid silver 1px;vertical-align:top;">'.$query.'</td><td style="border-bottom:solid silver 1px;vertical-align:top;"><i>'.$locInfo.'</i></td><td style="border-bottom:solid silver 1px;text-align:right;vertical-align:top;">'.$time.' sec</td></tr>';
			$total += $time;
		}
		echo '<table cellspacing="0" cellpadding="2" style="border:solid silver 1px;text-align:left;">
		<caption style="text-align:left;font-weight:bold;cursor:pointer;" title="show / hide report details" onclick="var tb=this.parentNode;var disp=(tb.tBodies[0].style.display==\'none\'?\'table-row-group\':\'none\');tb.tBodies[0].style.display=disp;tb.tHead.style.display=disp; document.getElementById(\'dbProfilerButton\').innerHTML=(disp==\'none\'?\'&dArr;\':\'&uArr;\');"> <span id="dbProfilerButton" style="float:right;">&dArr;</span>dbProfiler report</caption>
		<thead style="display:none;"><tr><th style="text-align:left;border-bottom:solid silver 1px;">Query</th><th style="border-bottom:solid silver 1px;">at</th><th style="text-align:right;border-bottom:solid silver 1px;">time</th></tr></thead>
		<tfoot><tr><td><b>Total: '.count(self::$stats).' queries</b></td><td>&nbsp;</td><td><b>Total time: '.$total.'sec</b></td></tr></tfoot>
		<tbody id="dbProfilerReport" style="display:none;">'.implode('',$rows)."</tbody>
		</table>";
	}

	function get_microtime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

}


/**
* Base class for databases object.
* @class db
*/
class db{
	/** array of instances already created, one for each connection strings */
	static protected $instances = array();
	/** the default connection to use */
	static public $defaultConnStr = null;
	/**
	* define methods aliases
	* define your own like this db::$aliases[alias] = 'realMethodName';
	*/
	static public $aliases = array(
		/** aliases required to keep backward compatibility */
		'select_to_array'          => 'select_rows',
		'select_single_to_array'   => 'select_row',
		'select2associative_array' => 'select_associative',
		'select_single_value'      => 'select_value',
		'select_array_slice'       => 'select_slice',
		'select_field_to_array'    => 'select_col',
		/** camelCase style aliases (for my own pleasure) */
		'selectRows'               => 'select_rows',
		'selectRow'                => 'select_row',
		'selectAssociative'        => 'select_associative',
		'selectCol'                => 'select_col',
		'selectSlice'              => 'select_slice',
		'selectValue'              => 'select_value',
	);

	/**Db hostname*/
	public $host = null;
	/**mysql username*/
	public $user = null;
	/**mysql password*/
	public $pass = null;

	/**resource connection (same as $conn if not applicable)*/
	public $conn = null;
	/**resource db selected*/
	public $db = null ;

	/**selected database*/
	public $dbname = '';

	/** resource result handler*/
	public $last_qres = null;
	/**array of last query to array results*/
	public $last_q2a_res = array();
	/**array of error number and msgs*/
	public $error = array();
	/**the last error array*/
	public $last_error = array();

	/**
	* set the level of verbosity.
	* It MUST be an integar not a string or nothing will output!!!!
	* 0 -> no output, 1-> only errors, 2-> only queries, 3-> queries + errors
	*/
	public $beverbose = 0;
	/**
	* set default beverbose value of any further new db instance
	*/
	static public $_default_verbosity = 0;

	static public $autoconnect = TRUE;
	/**
	*chr to protect fields names in queries
	*@private
	*/
	public $_protect_fldname = '`';

	/**
	* return a single instance of the database corresponding to the given connection String.
	* This method must be copyed in the exended class as php is not able to get the name of the calling class (one more poor aspect of this language).
	* @param string $connectionStr the connection string is a semi colon separated list
	*                              of connection parameter in the order they appear in the constructor
	*                              preceeded by classname://
	*                              for exemple a mysqldb connection string will look like:
	*                              "mysqldb://dbname;dbhost:port;dbuser;dbpass"
	*                              and a sqlitedb one will look like:
	*                              "sqlitedb://dbfile;mode"
	* @param bool $setDefault      if true then this database connection will be the default one
	*                              returned when no arguments are given.
	*                              For conveniance the first call to this method
	*                              will set the corresponding instance the default one
	*                              if none has been set before
	* @return db instance
	*/
	static public function getInstance($connectionStr=null,$setDefault=false){
		if( is_null(self::$defaultConnStr) ){
			if(is_null($connectionStr))
				throw new Exception(__class__." Can't return an instance without any valid connection string.");
			self::$defaultConnStr = $connectionStr;
		}
		if(is_null($connectionStr))
			$connectionStr = self::$defaultConnStr;
		if(isset(self::$instances[$connectionStr]))
			return self::$instances[$connectionStr];
		list($class,$params) = explode('://',$connectionStr);
		$params = explode(';',$params);
		$paramNb = count($params);
		for($i=0;$i<$paramNb;++$i){
			$pEval[] = "\$params[$i]";
		}
		if(! class_exists($class,false) )
			require (dirname(__file__)."/adapters/class-$class.php");
		eval( '$instance = new '.$class.'('.implode(',',$pEval).');');
		if( self::$_default_verbosity )
			$instance->beverbose = (int) self::$_default_verbosity;
		return self::$instances[$connectionStr] = $instance;
	}

	static public function setDefaultConnectionStr($connectionStr){
		self::$defaultConnStr = $connectionStr;
	}
	/**
	* This way of creating an instance is not encourage anymore!
	* @deprecated use @see getInstance instead
	* constructor stay public even if we have getInstance for 2 reason
	* 1- backward compatibility with existing scripts
	* 2- permit getInstance to create any derived class without redefining it in each subclass
	*/
	public function __construct(){
		if(self::$autoconnect)
			$this->open();
	}

	public function __destruct(){
		foreach( self::$instances as $k=>$db){
			self::$instances[$k]->close();
			unset($db,self::$instances[$k]);
		}
		$this->close(); #- close current object connection if not obtained by getInstance
	}

	function __call($m,$a){
		if( isset(self::$aliases[$m]) )
			return call_user_func_array(array($this,self::$aliases[$m]),$a);
	}
	###*** REQUIRED METHODS FOR EXTENDED CLASS ***###

	/** open connection to database */
	public function open(){}

	/** close connection to previously opened database */
	public function close(){}
	/**
	* Select the database to work on (it's the same as the use db command or mysql_select_db function)
	* @param string $dbname
	* @return bool
	* /
	function select_db($dbname=null){}*/
	/**
	* take a resource result set and return an array of type 'ASSOC','NUM','BOTH'
	* @see sqlitedb or mysqldb implementation for exemple
	*/
	public function fetch_res($result_set,$result_type='ASSOC'){}

	public function last_insert_id(){}

	/**
	* base method you should replace this one in the extended class, to use the appropriate escape func regarding the database implementation
	* @param string $quotestyle (both/single/double) which type of quote to escape
	* @return str
	*/
	public function escape_string($string,$quotestyle='both'){
		$escapes = array("\x00", "\x0a", "\x0d", "\x1a", "\x09","\\");
		$replace = array('\0',   '\n',    '\r',   '\Z' , '\t',  "\\\\");
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
				$replace[] = "\'";
				break;
			case 'both':
			case 'b':
			case '"\'':
			case '\'"':
				$escapes[] = '"';
				$replace[] = '\"';
				$escapes[] = "'";
				$replace[] = "\'";
				break;
		}
		return str_replace($escapes,$replace,$string);
	}

	/**
	* perform a query on the database
	* @param string $Q_str
	* @return= result id | FALSE
	**/
	public function query($Q_str){}

	/**
	* perform a query on the database like query but return the affected_rows instead of result
	* give a most suitable answer on query such as INSERT OR DELETE
	* @param string $Q_str
	* @return int affected_rows or FALSE on error!
	* @can work without this method but less smart
	function query_affected_rows($Q_str){}
	*/

	/**
	* get the table list from $this->dbname
	* @return array
	*/
	public function list_tables(){}
	/**
	* return the list of field in $table
	* @param string $table name of the sql table to work on
	* @param bool $extended_info if true will return the result of a show field query in a query_to_array fashion
	*                           (indexed by fieldname instead of int if false)
	* @return array
	*/
	public function list_table_fields($table,$extended_info=FALSE){}

	/** Verifier si cette methode peut s'appliquer a SQLite */
	public function show_table_keys($table){}

	/**
	* optimize table statement query
	* @param string $table name of the table to optimize
	* @return bool
	*/
	public function optimize($table){}

	public function error_no(){}
	public function error_str($errno=null){}

	###*** COMMON METHODS ***###

	/**
	* return the result of a query to an array
	* @param string $Q_str SQL query
	* @param string $result_type 'ASSOC', 'NUM' et 'BOTH'
	* @return array | false if no result
	*/
	public function query_to_array($Q_str,$result_type='ASSOC'){
		$this->last_q2a_res = array();
		if(! $this->query($Q_str)){
			return FALSE;
		}
		return $this->last_q2a_res = $this->fetch_res($this->last_qres,$result_type);
	}

	/**
	* send a select query to $table with arr $fields requested (all by default) and with arr $conditions
	* @param string|array $Table
	* @param string|array $fields
	* @param string|array $conditions
	* @param string $res_type 'ASSOC', 'NUM' et 'BOTH'
	* @Return  array | false
	**/
	public function select_rows($tables,$fields = '*', $conds = null,$result_type = 'ASSOC'){
		//we make the table list for the Q_str
		if(! $tb_str = $this->array_to_str($tables))
			return FALSE;
		//we make the fields list for the Q_str
    if(! $fld_str = $this->array_to_str($fields) )
			$fld_str = '*';
		//now the WHERE str
		$conds_str = $this->process_conds($conds);

		$Q_str = "SELECT $fld_str FROM $tb_str $conds_str";
		# echo "SQL : $Q_str\n;";
		return $this->query_to_array($Q_str,$result_type);
	}
	/**
	* Same as select_rows but return only the first row.
	* equal to $res = select_rows followed by $res = $res[0];
	* @see select_rows for details
	* @return array of fields
	*/
	public function select_row($tables,$fields = '*', $conds = null,$result_type = 'ASSOC'){
		if(! $res = $this->select_rows($tables,$fields,$conds,$result_type))
			return FALSE;
		return $res[0];
	}
	/**
	* just a quick way to do a select_rows followed by a associative_array_from_q2a_res
	* see both thoose method for more information about parameters or return values
	*/
	public function select_associative($tables,$fields='*',$conds=null,$index_field='id',$value_fields=null,$keep_index=FALSE){
		if(! $this->select_rows($tables,$fields,$conds))
			return FALSE;
		return $this->associative_array_from_q2a_res($index_field,$value_fields,null,$keep_index);
	}
	/**
	* select a single value in database
	* @param string $table
	* @param string $field the field name where to pick-up value
	* @param mixed conds
	* @return mixed or FALSE
	*/
	public function select_value($table,$field,$conds=null){
		if($res = $this->select_row($table,$field,$conds,'NUM'))
			return $res[0];
		else
			return FALSE;
	}
	/**
	* select a single table field and return all values
	* @param string $table
	* @param string $field name of the single field to retrieve
	* @param mixed  $conds
	* @return array or FALSE
	*/
	public function select_col($table,$field,$conds=null){
		$conds_str = $this->process_conds($conds);
		$Q_str = "SELECT $field FROM $table $conds_str";
		if(! $res = $this->query_to_array($Q_str,'NUM') )
			return FALSE;
		foreach($res as $row){
			$_res[] = $row[0];
		}
		return $_res;
	}

	/**
	* @return array  array((array) results,(str) navigationstring, (int) totalrows)
	*/
	public function select_slice($table,$fields='*',$conds=null,$pageId=1,$pageNbRows=10){
		$conds = $this->process_conds($conds);
		if(! ($tot = $this->select_value($table,'count(*)',$conds) ) )
			return FALSE;
		$limitStart = (int) $pageNbRows * ($pageId-1);
		$res = $this->select_rows($table,$fields,$conds." Limit $limitStart,$pageNbRows");
		# now prepare navigation links
		$attrs = $this->set_slice_attrs();
		extract($attrs);
		$nbpages = ceil($tot/max(1,$pageNbRows));

		# start/prev link
		if($nbpages > 1 && $pageId != 1){
			$first = str_replace('%lnk',str_replace('%page',1,$linkStr),$first);
			$prev = str_replace('%lnk',str_replace('%page',$pageId-1,$linkStr),$prev);
		}else{
			$first = str_replace('%lnk',str_replace('%page',1,$linkStr),$firstDisabled);
			$prev = str_replace('%lnk',str_replace('%page',$pageId-1,$linkStr),$prevDisabled);
		}
		# next/end link
		if( $pageId < $nbpages ){
			$last  = str_replace('%lnk',str_replace('%page',$nbpages,$linkStr),$last);
			$next = str_replace('%lnk',str_replace('%page',$pageId+1,$linkStr),$next);
		}else{
			$last  = str_replace('%lnk',str_replace('%page',$nbpages,$linkStr),$lastDisabled);
			$next = str_replace('%lnk',str_replace('%page',$pageId+1,$linkStr),$nextDisabled);
		}

		# pages links
		if(preg_match('!%(\d+)?links!',$formatStr,$m)){
			$nblinks = isset($m[1])?$m[1]:'';
			if(! $nblinks){ # all pages links
				$slideStart = 1;
				$slideEnd   = $nbpages;
			}else{ # range pages link
				$delta      = $nblinks%2?($nblinks-1)/2:$nblinks/2;
				$slideStart = max(1,$pageId - $delta - (($pageId+$delta)<=$nbpages?0: $pageId -($nbpages-$delta)) );
				$slideEnd   = min($nbpages,$pageId + $delta + ($pageId > $delta?0: $delta - $pageId + 1 ) );
			}
			for($i=$slideStart;$i<=$slideEnd;$i++){
				$pageLinks[] = str_replace(
					array('%lnk','%page'),
					array(str_replace('%page',$i,$linkStr),$i),
					($i==$pageId?$curpage:$pages)
				);
			}

			$links = implode($linkSep,$pageLinks);
		}

		$formatStr = str_replace(
			array('%first','%prev','%next','%last','%'.$nblinks.'links','%tot','%nbpages','%page'),
			array($first,$prev,$next,$last,$links,$tot,$nbpages,$pageId),
			$formatStr
		);
		return array($res,$formatStr,$tot);
	}

	/**
	* set attributes for slice rendering.
	* take an associative array of format strings to render slice links.
	* - firt:  first page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - prev:  previous page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - next:  next page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - last:  last page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - pages: pages link %lnk and %page will be replaced by the link to the page and the number of the page
	* - curpage: selected page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - linkStr: is used for rendering the url of pages %page will be replaced by the corresponding page number
	* - linkSep: separator between pages links
	* - formatStr: is used to render the full pagination string
	*              %start, %prev, %next, %last will be replaced respectively by corresponding links
	*              %Nlinks will be replaced by the pages links. N is the number of link to display
	*              including the selected page ex: %5links will show 5 pages links
	* you can pass only the keys you want to replace ex: db::set_slice_attrs(array('linkStr'=>"myslice.php?page=%page"))
	* all keys can also contain a %tot and %nbpages which will be replaced respectively by
	* the total amount of result and the total number of pages
	*@param array $attrs
	*@return array
	*/
	public function set_slice_attrs($attrs=null){
		static $sliceAttrs;
		if(! isset($sliceAttrs) ){
			$sliceAttrs = array(
				'firstDisabled' => '',
				'prevDisabled'  => '',
				'nextDisabled'  => '',
				'lastDisabled'  => '',
				'first' => '<a href="%lnk" class="pagelnk"><big>&laquo;</big></a>',
				'prev'  => '<a href="%lnk" class="pagelnk"><big>&lsaquo;</big></a>',
				'next'  => '<a href="%lnk" class="pagelnk"><big>&rsaquo;</big></a>',
				'last'  => '<a href="%lnk" class="pagelnk"><big>&raquo;</big></a>',
				'pages' => '<a href="%lnk" class="pagelnk">%page</a>',
				#- 'curpage'  => "<b><a href=\"%lnk\" class=\"pagelnk\">%page</a></b>",
				'curpage'  => '<input type="text" value="%page" onfocus="this.value=\'\';" onkeydown="if(event.keyCode==13){ var p=parseInt(this.value)||1;window.location=\'%lnk\'.replace(/page=%page/,\'page=\'+(p>%nbpages?%nbpages:(p<1?1:p)));return false;}" size="3" title="aller &agrave; la page" style="text-align:center;" />',
				'linkStr'  => '?page=%page',
				'linkSep'  => ' ',
				'formatStr'=> ' %first %prev %5links %next %last'
			);
		}
		if( is_array($attrs) ){
			$sliceAttrs = array_merge($sliceAttrs,array_intersect_key($attrs,$sliceAttrs));
		}
		return $sliceAttrs;
	}

	/**
	* Send an insert query to $table
	* @param string $table
	* @param array $values (arr(FLD=>VALUE,)
	* @param bool $return_id the function will return the inserted_id if $return_id is true (the default value), else it'll return only true or false.
	* @return insert id or FALSE
	**/
	public function insert($table,$values,$return_id=TRUE){
		if(!is_array($values))
			return FALSE;
		$fld = $this->protect_field_names(array_keys($values));
		$val = array_map(array($this,'prepare_smart_param'),$values);

		$Q_str = "INSERT INTO $table ($fld) VALUES (".$this->array_to_str($val).")";
		if(! $this->query($Q_str) )
			return FALSE;
		$this->last_id = $this->last_insert_id();
		return $return_id?$this->last_id:TRUE;
	}
	/**
	* Send a delete query to $table
	* @param string $table
	* @param mixed $conds
	* @return int affected_rows
	**/
	public function delete($table,$conds=null){
		$conds_str = $this->process_conds($conds);
		$Q_str = "DELETE FROM $table $conds_str";
		if(method_exists($this,'query_affected_rows')){
			$res = $this->query_affected_rows($Q_str);
			return ($res===FALSE || $res === -1)?FALSE:$res;
		}else{
			$count = (int) $this->get_count($table);
			if(! $this->query($Q_str) )
				return FALSE;
			$count2 = (int) $this->get_count($table);
			return (int) ($count - $count2);
		}
	}
	/**
	* Send an update query to $table
	* @param string $table
	* @param string|array $values ( 'fld=value, fld2=value2' arr(FLD=>VALUE,))
	* @return int affected_rows or bool (depends on the database implementation (have we a query_affected_rows or not?))
	**/
	public function update($table,$values,$conds = null){
		if(is_array($values)){
			$str = array();
			foreach( $values as $k=>$v)
				$str[] = $this->protect_field_names($k)." = ".$this->prepare_smart_param($v).' ';
		}elseif(! is_string($values)){
			return FALSE;
		}
		# now the WHERE str
		$conds_str = $this->process_conds($conds);
		$Q_str = "UPDATE $table SET ".(is_array($values)?$this->array_to_str($str):$values)." $conds_str";
		if(method_exists($this,'query_affected_rows')){
			$res = $this->query_affected_rows($Q_str);
			return ($res===FALSE || $res === -1)?FALSE:$res;
		}else{
			return (bool) $this->query($Q_str);
		}
	}

	/**
	* get the number of row in $table
	* @param string $table table name
	* @param mixed  $conds
	* @return int
	*/
	public function get_count($table,$conds=null){
		return $this->select_value($table,'count(*) as c',$conds);
	}

	/**
	*return an associative array indexed by $index_field with values $value_fields from
	*a mysqldb->select_rows result
	*@param string $index_field default value is id
	*@param mixed $value_fields (string field name or array of fields name default is null so keep all fields
	*@param array $res the mysqldb->select_rows result
	*@param bool $keep_index if set to true then the index field will be keep in the values associated (unused if $value_fields is string)
	*@param bool $sort_keys will automaticly sort the array by key if set to true @deprecated argument
	*@return array
	*/
	public function associative_array_from_q2a_res($index_field='id',$value_fields=null,$res = null,$keep_index=FALSE,$sort_keys=FALSE){
		if($res===null)
			$res = $this->last_q2a_res;
		if(! is_array($res)){
			$this->verbose("associative_array_from_q2a_res with invalid result",__FUNCTION__,1);
			return FALSE;
		}
		# then verify index exists
		if(!isset($res[0][$index_field])){
			$this->verbose("associative_array_from_q2a_res with invalid index field '$index_field'",__FUNCTION__,1);
			return FALSE;
		}
		# then we do the trick
		if(is_string($value_fields)){
			foreach($res as $row)
				$associatives_res[$row[$index_field]] = $row[$value_fields];
		}elseif(is_array($value_fields)||$value_fields===null){
			foreach($res as $row){
				$associatives_res[$row[$index_field]] = $row;
				if(!$keep_index)
					unset($associatives_res[$row[$index_field]][$index_field]);
			}
		}
		if(! count($associatives_res))
			return FALSE;
		if($sort_keys)
			ksort($associatives_res);
		return $this->last_q2a_res = $associatives_res;
	}
	/*########## INTERNAL METHOD ##########*/

	/**
	* used by other methods to parse the conditions param of a QUERY.
	* If $conds is string then nothing more is done.
	* If it's an array, the first value (index 0) will be consider as the full condition string and all '?' will be replaced by other values in the array (sort of sprintf).
	* You can add a number before a ? to replace it by a given index in the array like 2?
	* @param string|array $conds
	* @return string
	*/
	public function process_conds($conds=null){
		if(is_string($conds) )
			return $conds;
		elseif(! is_array($conds) )
			return '';
		$conds_str = array_shift($conds);
		array_unshift($conds,'');
		$i=0;
		return preg_replace('!(\d*)\?!e',"\$this->prepare_smart_param('\\1'!==''?\$conds['\\1']:(isset(\$conds[++\$i])?\$conds[\$i]:null),'single')",$conds_str);
	}

	/**
	* used internally for smart params processing
	* @private
	*/
	protected function prepare_smart_param($val){
		if(is_null($val)){
			return 'NULL';
		}elseif (is_int($val) || is_float($val)) {
			return $val;
		} elseif(is_string($val)) {
			return "'".$this->escape_string($val,'single')."'";
		} elseif(is_array($val)) {
			return implode(',', array_map(array(&$this,'prepare_smart_param'),$val));
		}else{
			return "''";
		}
	}
	/**
	* used internally to prepare fields for queries
	* @param string|array $fields list of fields. it's up to you to protect fieldsname if you put in fields as string
	*/
	public function protect_field_names($fields){
		if(is_array($fields)){
			foreach($fields as $k=>$f)
				$fields[$k] = $this->_protect_fldname.$f.$this->_protect_fldname;
			$fields = implode(',',$fields);
		}elseif($fields){
			if( $this->_protect_fldname && ! substr_count($fields,$this->_protect_fldname) ){ # if already protected we do nothing
				$fields = preg_replace('!\s*,\s*!',$this->_protect_fldname.','.$this->_protect_fldname,$fields);
				$fields = $this->_protect_fldname . trim($fields) . $this->_protect_fldname;
			}
		}
		return $fields?$fields:false;
	}

	protected function array_to_str($var,$sep=','){
		return (is_string($var)?$var:(is_array($var)?implode($sep,$var):''));
	}

	protected function set_error($callingfunc=null){
		static $i=0;
		if(! $this->db ){
			$this->error[$i]['nb']  = null;
			$this->error[$i]['str'] = '[ERROR] No Db Handler';
		}else{
			$this->error[$i]['nb']  = $this->error_no();
			$this->error[$i]['str'] = $this->error_str($this->error[$i]['nb']);
		}
		$this->last_error = $this->error[$i];
		$this->verbose($this->error[$i]['str'],$callingfunc,1);
		$i++;
	}

	/**
	* print a msg on STDOUT if $this->beverbose is set to true
	* @param string $msg         message output string
	* @param string $callingFunc name of the calling function
	* @param int $msgLvl         the level corresponding to the message
	*                            1-> error message
	*                            2-> query or similar informative message
	*                            msgLvl must be an int not a string as it will be test by type
	* @private
	*/
	protected function verbose($msg,$callingFunc=null,$msgLvl=1){
		if(! $this->beverbose)
			return;
		if( ($msgLvl===2 && $this->beverbose >=2) || ($msgLvl === 1 && $this->beverbose !== 2) ){
			$msg = get_class($this).($callingFunc?"::$callingFunc":'').' => '.$msg;
			$useConsoleApp = ( php_sapi_name()=='cli' && class_exists('console_app',false))?true:false;
			$isError = $msgLvl===1?true:false;
			if($isError){
				if($useConsoleApp)
					return console_app::msg_error($msg);
				echo "<div style=\"color:red;font-weight:bold;\" class=\"dbMsg\">[ERROR] $msg</div>\n";
			}else{
				if($useConsoleApp)
					return console_app::msg_info($msg);
				echo "<div style=\"color:blue;font-weight:bold;\" class=\"dbMsg\">$msg</div>\n";
			}
		}
	}

	###*** DEPRECATED METHODS ***###

	/**
	* return the list of field in $table
	* @deprecated still here for compatibility with old version
	* @use and @see db::list_table_fields() instead
	* @param string $table name of the sql table to work on
	* @param bool $extended_info will return the result of a show field query in a query_to_array fashion
	*/
	public function get_fields($table,$extended_info=FALSE){
		return $this->list_table_fields($table,$extended_info);
	}

	/**
	* get the fields list of table
	* @deprecated now the $indexed_by_name args won't exists anymore but will considered as TRUE in all case
	* @see db::list_table_fields as a replacement method
	* @param string $table
	* @param bool $indexed_by_name the return array will be indexed by the fields name if set to true (default is FALSE)
	* @return array
	*/
	public function list_fields($table,$indexed_by_name=FALSE){
		return $this->list_table_fields($table,TRUE);
	}

}
han Gotti <jgotti at jgotti dot org>
* @copyleft (l) 2003-2008  Jonathan Gotti
* @package DB
* @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
* @since 2006-04-16 first splitted version
* get_field and list_fields have changed -> list_table_fields (list_fields indexed by name)
* smart '?' on conditions strings
* @changelog - 2008-04-10 - new class dbprofiler
*            - 2008-04-06 - autoconnect is now a static property
*                         - now db::getInstance call require on missing class-xxxxdb.php
*            - 2008-02-19 - now get_count() method can receive optional clause as second parameter
*                         - add method _call() and static property db::$aliases to support methods aliases to be user defined (you can set your own aliases for any methods)
*                         - rename most of select_* methods with 'better' name (shorter and better meaning I hope) But old names will work just fine with new methods aliases support
*            - 2007-11-20 - now beverbose property is int instead of bool and can takes 4 values
*                           0 -> no output, 1-> only errors, 2-> only queries, 3-> queries + errors
*                           (don't forget to change true by 1 in your scripts or you won't have expected output anymore)
*                         - remove call to set_error() in query_to_array only query has to do it
*            - 2007-10-11 - change default curpage for an input in set_slice_attrs()
*                         - no more autoload of console_app when not in sapi cli
*            - 2007-10-08 - new methods getInstance, setDefaultConnectionStr, and __destruct
*                           to ease the way of getting uniques db instances for each db
*            - 2007-03-28 - protect_field_names() isn't called automaticly anymore to allow
*                           the use of function, wild char or alias in fields list.
*                           will perhaps permit this again with a more effective regex in the future.
*                           so at this time it's up to you to use this method as needed on any select_* method (still applyed for insert and update)
*                         - move last_q2a_res assignment from fetch_res() method to query_to_array() (seems more ligical to me)
*            - 2007-03-26 - better fields name handling (auto-protect fieldsname even if string is given)
*            - 2007-01-12 - better values type handling in update and insert methods
*            - 2007-01-10 - correct a bug about page counting in set_slice_attrs() and add %page replacement to fromatStr
*                         - better params type handling in method process_conds()  (int/string/array/null)
*            - 2006-12-05 - new method select_field_to_array()
*            - 2006-05-15 - new methods set_slice_attrs() and select_array_slice() to easily paginate your results
*/

class dbProfiler{
	static public $precision     = 4;

	static public $stats    = array();
	protected $db           = null;
	protected $statFuncs    = null;

	function __construct($dbInstance){
		$this->db  = $dbInstance;
		$this->statFuncs = array_merge(array_keys(db::$aliases),array_values(db::$aliases),array('delete','update','query','query_to_array'));
	}

	function __get($k){
		return $this->db->$k;
	}

	function __set($k,$v){
		return $this->db->$k = $v;
	}

	function __call($m,$a){
		if(! in_array($m,$this->statFuncs))
			return call_user_func_array(array($this->db,$m),$a);
		# - get stat on queries
		$stat = array("<b>$m</b>(".implode('<b>,</b> ',array_map(array($this,'_prepareArgStr'),$a)).')',$this->get_microtime());
		$res  = call_user_func_array(array($this->db,$m),$a);
		$stat[] = $this->get_microtime();
		self::$stats[] = $stat;
		return $res;
	}

	function _prepareArgStr($a){
		return var_export($a,1);
	}

	static function printReport(){
		if(! count(self::$stats) )
			return;
		$total = 0;
		foreach(self::$stats as $stat){
			list($query,$start,$end) = $stat;
			$time = round($end-$start,self::$precision);
			$rows[] = '<tr><td style="border-bottom:solid silver 1px;">'.$query.'</td><td style="border-bottom:solid silver 1px;text-align:right;">'.$time.' sec</td></tr>';
			$total += $time;
		}
		echo '<table cellspacing="0" cellpadding="2" style="border:solid silver 1px;">
		<caption style="text-align:left;font-weight:bold;" onclick="var body = document.getElementById(\'dbProfilerReport\');body.style.display=(body.style.display==\'none\'?\'table-row-group\':\'none\')">dbProfiler report</caption>
		<thead><tr><th style="text-align:left;border-bottom:solid silver 1px;">Query</th><th style="text-align:right;border-bottom:solid silver 1px;">time</th></tr></thead>
		<tfoot><tr><td><b>Total: '.count(self::$stats).' queries</b></td><td><b>Total time: '.$total.'sec</b></td></tr></tfoot>
		<tbody id="dbProfilerReport" style="display:none;">'.implode('',$rows)."</tbody>
		</table>";
	}

	function get_microtime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

}


class db{
	/** array of instances already created, one for each connection strings */
	static protected $instances = array();
	/** the default connection to use */
	static public $defaultConnStr = null;
	/**
	* define methods aliases
	* define your own like this db::$aliases[alias] = 'realMethodName';
	*/
	static public $aliases = array(
		/** aliases required to keep backward compatibility */
		'select_to_array'          => 'select_rows',
		'select_single_to_array'   => 'select_row',
		'select2associative_array' => 'select_associative',
		'select_single_value'      => 'select_value',
		'select_array_slice'       => 'select_slice',
		'select_field_to_array'    => 'select_col',
		/** camelCase style aliases (for my own pleasure) */
		'selectRows'               => 'select_rows',
		'selectRow'                => 'select_row',
		'selectAssociative'        => 'select_associative',
		'selectCol'                => 'select_col',
		'selectSlice'              => 'select_slice',
		'selectValue'              => 'select_value',
	);

	/**Db hostname*/
	public $host = null;
	/**mysql username*/
	public $user = null;
	/**mysql password*/
	public $pass = null;

	/**resource connection (same as $conn if not applicable)*/
	public $conn = null;
	/**resource db selected*/
	public $db = null ;

	/**selected database*/
	public $dbname = '';

	/** resource result handler*/
	public $last_qres = null;
	/**array of last query to array results*/
	public $last_q2a_res = array();
	/**array of error number and msgs*/
	public $error = array();
	/**the last error array*/
	public $last_error = array();

	/**
	* set the level of verbosity.
	* It MUST be an integar not a string or nothing will output!!!!
	* 0 -> no output, 1-> only errors, 2-> only queries, 3-> queries + errors
	*/
	public $beverbose = 0;

	static public $autoconnect = TRUE;
	/**
	*chr to protect fields names in queries
	*@private
	*/
	public $_protect_fldname = '`';

	/**
	* return a single instance of the database corresponding to the given connection String.
	* This method must be copyed in the exended class as php is not able to get the name of the calling class (one more poor aspect of this language).
	* @param string $connectionStr the connection string is a semi colon separated list
	*                              of connection parameter in the order they appear in the constructor
	*                              preceeded by classname://
	*                              for exemple a mysqldb connection string will look like:
	*                              "mysqldb://dbname;dbhost:port;dbuser;dbpass"
	*                              and a sqlitedb one will look like:
	*                              "sqlitedb://dbfile;mode"
	* @param bool $setDefault      if true then this database connection will be the default one
	*                              returned when no arguments are given.
	*                              For conveniance the first call to this method
	*                              will set the corresponding instance the default one
	*                              if none has been set before
	* @return db instance
	*/
	static public function getInstance($connectionStr=null,$setDefault=false){
		if( is_null(self::$defaultConnStr) ){
			if(is_null($connectionStr))
				throw new Exception(__class__." Can't return an instance without any valid connection string.");
			self::$defaultConnStr = $connectionStr;
		}
		if(is_null($connectionStr))
			$connectionStr = self::$defaultConnStr;
		if(isset(self::$instances[$connectionStr]))
			return self::$instances[$connectionStr];
		list($class,$params) = explode('://',$connectionStr);
		$params = explode(';',$params);
		$paramNb = count($params);
		for($i=0;$i<$paramNb;++$i){
			$pEval[] = "\$params[$i]";
		}
		if(! class_exists($class) )
			require (dirname(__file__)."/class-$class.php");
		eval( '$instance = new '.$class.'('.implode(',',$pEval).');');
		return self::$instances[$connectionStr] = $instance;
	}

	static public function setDefaultConnectionStr($connectionStr){
		self::$defaultConnStr = $connectionStr;
	}
	/**
	* This way of creating an instance is not encourage anymore!
	* @deprecated use @see getInstance instead
	* constructor stay public even if we have getInstance for 2 reason
	* 1- backward compatibility with existing scripts
	* 2- permit getInstance to create any derived class without redefining it in each subclass
	*/
	public function __construct(){
		if(self::$autoconnect)
			$this->open();
	}

	public function __destruct(){
		foreach( self::$instances as $k=>$db){
			self::$instances[$k]->close();
			unset($db,self::$instances[$k]);
		}
		$this->close(); #- close current object connection if not obtained by getInstance
	}

	function __call($m,$a){
		if( isset(self::$aliases[$m]) )
			return call_user_func_array(array($this,self::$aliases[$m]),$a);
	}
	###*** REQUIRED METHODS FOR EXTENDED CLASS ***###

	/** open connection to database */
	public function open(){}

	/** close connection to previously opened database */
	public function close(){}
	/**
	* Select the database to work on (it's the same as the use db command or mysql_select_db function)
	* @param string $dbname
	* @return bool
	* /
	function select_db($dbname=null){}*/
	/**
	* take a resource result set and return an array of type 'ASSOC','NUM','BOTH'
	* @see sqlitedb or mysqldb implementation for exemple
	*/
	public function fetch_res($result_set,$result_type='ASSOC'){}

	public function last_insert_id(){}

	/**
	* base method you should replace this one in the extended class, to use the appropriate escape func regarding the database implementation
	* @param string $quotestyle (both/single/double) which type of quote to escape
	* @return str
	*/
	public function escape_string($string,$quotestyle='both'){
		$escapes = array("\x00", "\x0a", "\x0d", "\x1a", "\x09","\\");
		$replace = array('\0',   '\n',    '\r',   '\Z' , '\t',  "\\\\");
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
				$replace[] = "\'";
				break;
			case 'both':
			case 'b':
			case '"\'':
			case '\'"':
				$escapes[] = '"';
				$replace[] = '\"';
				$escapes[] = "'";
				$replace[] = "\'";
				break;
		}
		return str_replace($escapes,$replace,$string);
	}

	/**
	* perform a query on the database
	* @param string $Q_str
	* @return= result id | FALSE
	**/
	public function query($Q_str){}

	/**
	* perform a query on the database like query but return the affected_rows instead of result
	* give a most suitable answer on query such as INSERT OR DELETE
	* @param string $Q_str
	* @return int affected_rows or FALSE on error!
	* @can work without this method but less smart
	function query_affected_rows($Q_str){}
	*/

	/**
	* get the table list from $this->dbname
	* @return array
	*/
	public function list_tables(){}
	/**
	* return the list of field in $table
	* @param string $table name of the sql table to work on
	* @param bool $extended_info if true will return the result of a show field query in a query_to_array fashion
	*                           (indexed by fieldname instead of int if false)
	* @return array
	*/
	public function list_table_fields($table,$extended_info=FALSE){}

	/** Verifier si cette methode peut s'appliquer a SQLite */
	public function show_table_keys($table){}

	/**
	* optimize table statement query
	* @param string $table name of the table to optimize
	* @return bool
	*/
	public function optimize($table){}

	public function error_no(){}
	public function error_str($errno=null){}

	###*** COMMON METHODS ***###

	/**
	* return the result of a query to an array
	* @param string $Q_str SQL query
	* @param string $result_type 'ASSOC', 'NUM' et 'BOTH'
	* @return array | false if no result
	*/
	public function query_to_array($Q_str,$result_type='ASSOC'){
		$this->last_q2a_res = array();
		if(! $this->query($Q_str)){
			return FALSE;
		}
		return $this->last_q2a_res = $this->fetch_res($this->last_qres,$result_type);
	}

	/**
	* send a select query to $table with arr $fields requested (all by default) and with arr $conditions
	* @param string|array $Table
	* @param string|array $fields
	* @param string|array $conditions
	* @param string $res_type 'ASSOC', 'NUM' et 'BOTH'
	* @Return  array | false
	**/
	public function select_rows($tables,$fields = '*', $conds = null,$result_type = 'ASSOC'){
		//we make the table list for the Q_str
		if(! $tb_str = $this->array_to_str($tables))
			return FALSE;
		//we make the fields list for the Q_str
    if(! $fld_str = $this->array_to_str($fields) )
			$fld_str = '*';
		//now the WHERE str
		$conds_str = $this->process_conds($conds);

		$Q_str = "SELECT $fld_str FROM $tb_str $conds_str";
		# echo "SQL : $Q_str\n;";
		return $this->query_to_array($Q_str,$result_type);
	}
	/**
	* Same as select_rows but return only the first row.
	* equal to $res = select_rows followed by $res = $res[0];
	* @see select_rows for details
	* @return array of fields
	*/
	public function select_row($tables,$fields = '*', $conds = null,$result_type = 'ASSOC'){
		if(! $res = $this->select_rows($tables,$fields,$conds,$result_type))
			return FALSE;
		return $res[0];
	}
	/**
	* just a quick way to do a select_rows followed by a associative_array_from_q2a_res
	* see both thoose method for more information about parameters or return values
	*/
	public function select_associative($tables,$fields='*',$conds=null,$index_field='id',$value_fields=null,$keep_index=FALSE){
		if(! $this->select_rows($tables,$fields,$conds))
			return FALSE;
		return $this->associative_array_from_q2a_res($index_field,$value_fields,null,$keep_index);
	}
	/**
	* select a single value in database
	* @param string $table
	* @param string $field the field name where to pick-up value
	* @param mixed conds
	* @return mixed or FALSE
	*/
	public function select_value($table,$field,$conds=null){
		if($res = $this->select_row($table,$field,$conds,'NUM'))
			return $res[0];
		else
			return FALSE;
	}
	/**
	* select a single table field and return all values
	* @param string $table
	* @param string $field name of the single field to retrieve
	* @param mixed  $conds
	* @return array or FALSE
	*/
	public function select_col($table,$field,$conds=null){
		$conds_str = $this->process_conds($conds);
		$Q_str = "SELECT $field FROM $table $conds_str";
		if(! $res = $this->query_to_array($Q_str,'NUM') )
			return FALSE;
		foreach($res as $row){
			$_res[] = $row[0];
		}
		return $_res;
	}

	/**
	* @return array  array((array) results,(str) navigationstring, (int) totalrows)
	*/
	public function select_slice($table,$fields='*',$conds=null,$pageId=1,$pageNbRows=10){
		$conds = $this->process_conds($conds);
		if(! ($tot = $this->select_value($table,'count(*)',$conds) ) )
			return FALSE;
		$limitStart = (int) $pageNbRows * ($pageId-1);
		$res = $this->select_rows($table,$fields,$conds." Limit $limitStart,$pageNbRows");
		# now prepare navigation links
		$attrs = $this->set_slice_attrs();
		extract($attrs);
		$nbpages = ceil($tot/max(1,$pageNbRows));

		# start/prev link
		if($nbpages > 1 && $pageId != 1){
			$first = str_replace('%lnk',str_replace('%page',1,$linkStr),$first);
			$prev = str_replace('%lnk',str_replace('%page',$pageId-1,$linkStr),$prev);
		}else{
			$first = $prev = '';
		}
		# next/end link
		if( $pageId < $nbpages ){
			$last  = str_replace('%lnk',str_replace('%page',$nbpages,$linkStr),$last);
			$next = str_replace('%lnk',str_replace('%page',$pageId+1,$linkStr),$next);
		}else{
			$last = $next = '';
		}

		# pages links
		if(preg_match('!%(\d+)?links!',$formatStr,$m)){
			$nblinks = isset($m[1])?$m[1]:'';
			if(! $nblinks){ # all pages links
				$slideStart = 1;
				$slideEnd   = $nbpages;
			}else{ # range pages link
				$delta      = $nblinks%2?($nblinks-1)/2:$nblinks/2;
				$slideStart = max(1,$pageId - $delta - (($pageId+$delta)<=$nbpages?0: $pageId -($nbpages-$delta)) );
				$slideEnd   = min($nbpages,$pageId + $delta + ($pageId > $delta?0: $delta - $pageId + 1 ) );
			}
			for($i=$slideStart;$i<=$slideEnd;$i++){
				$pageLinks[] = str_replace( array('%lnk','%page'),
																		array(str_replace('%page',$i,$linkStr),$i),
																		($i==$pageId?$curpage:$pages)
																	);
			}

			$links = implode($linkSep,$pageLinks);
		}

		$formatStr = str_replace( array('%first','%prev','%next','%last','%'.$nblinks.'links','%tot','%nbpages','%page'),
															array($first,$prev,$next,$last,$links,$tot,$nbpages,$pageId),
															$formatStr
														);
		return array($res,$formatStr,$tot);
	}

	/**
	* set attributes for slice rendering.
	* take an associative array of format strings to render slice links.
	* - firt:  first page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - prev:  previous page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - next:  next page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - last:  last page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - pages: pages link %lnk and %page will be replaced by the link to the page and the number of the page
	* - curpage: selected page link %lnk and %page will be replaced by the link to the page and the number of the page
	* - linkStr: is used for rendering the url of pages %page will be replaced by the corresponding page number
	* - linkSep: separator between pages links
	* - formatStr: is used to render the full pagination string
	*              %start, %prev, %next, %last will be replaced respectively by corresponding links
	*              %Nlinks will be replaced by the pages links. N is the number of link to display
	*              including the selected page ex: %5links will show 5 pages links
	* you can pass only the keys you want to replace ex: db::set_slice_attrs(array('linkStr'=>"myslice.php?page=%page"))
	* all keys can also contain a %tot and %nbpages which will be replaced respectively by
	* the total amount of result and the total number of pages
	*@param array $attrs
	*@return array
	*/
	public function set_slice_attrs($attrs=null){
		static $sliceAttrs;
		if(! isset($sliceAttrs) ){
			$sliceAttrs = array( 'first' => "<a href=\"%lnk\" class=\"pagelnk\"><<</a>",
														'prev'  => "<a href=\"%lnk\" class=\"pagelnk\"><</a>",
														'next'  => "<a href=\"%lnk\" class=\"pagelnk\">></a>",
														'last'   => "<a href=\"%lnk\" class=\"pagelnk\">>></a>",
														'pages'  => "<a href=\"%lnk\" class=\"pagelnk\">%page</a>",
														#- 'curpage'  => "<b><a href=\"%lnk\" class=\"pagelnk\">%page</a></b>",
														'curpage'  => '<input type="text" value="%page" onfocus="this.value=\'\';" onkeydown="if(event.keyCode==13){ var p=parseInt(this.value)||1;window.location=\'%lnk\'.replace(/page=%page/,\'page=\'+(p>%nbpages?%nbpages:(p<1?1:p)));return false;}" size="3" title="aller &agrave; la page" style="text-align:center;" />',
														'linkStr'  => "?page=%page",
														'linkSep'  => " ",
														'formatStr'=> " %first %prev %5links %next %last"
													);
		}
		if( is_array($attrs) ){
			foreach($sliceAttrs as $k=>$v){
				$sliceAttrs[$k] = isset($attrs[$k])?$attrs[$k]:$v;
			}
		}
		return $sliceAttrs;
	}

	/**
	* Send an insert query to $table
	* @param string $table
	* @param array $values (arr(FLD=>VALUE,)
	* @param bool $return_id the function will return the inserted_id if $return_id is true (the default value), else it'll return only true or false.
	* @return insert id or FALSE
	**/
	public function insert($table,$values,$return_id=TRUE){
		if(!is_array($values))
			return FALSE;
		$fld = $this->protect_field_names(array_keys($values));
		$val = array_map(array($this,'prepare_smart_param'),$values);

		$Q_str = "INSERT INTO $table ($fld) VALUES (".$this->array_to_str($val).")";
		if(! $this->query($Q_str) )
			return FALSE;
		$this->last_id = $this->last_insert_id();
		return $return_id?$this->last_id:TRUE;
	}
	/**
	* Send a delete query to $table
	* @param string $table
	* @param mixed $conds
	* @return int affected_rows
	**/
	public function delete($table,$conds=null){
		$conds_str = $this->process_conds($conds);
		$Q_str = "DELETE FROM $table $conds_str";
		if(method_exists($this,'query_affected_rows')){
			$res = $this->query_affected_rows($Q_str);
			return ($res===FALSE || $res === -1)?FALSE:$res;
		}else{
			$count = (int) $this->get_count($table);
			if(! $this->query($Q_str) )
				return FALSE;
			$count2 = (int) $this->get_count($table);
			return (int) ($count - $count2);
		}
	}
	/**
	* Send an update query to $table
	* @param string $table
	* @param string|array $values ( 'fld=value, fld2=value2' arr(FLD=>VALUE,))
	* @return int affected_rows or bool (depends on the database implementation (have we a query_affected_rows or not?))
	**/
	public function update($table,$values,$conds = null){
		if(is_array($values)){
			$str = array();
			foreach( $values as $k=>$v)
				$str[] = $this->protect_field_names($k)." = ".$this->prepare_smart_param($v).' ';
		}elseif(! is_string($values)){
			return FALSE;
		}
		# now the WHERE str
		$conds_str = $this->process_conds($conds);
		$Q_str = "UPDATE $table SET ".(is_array($values)?$this->array_to_str($str):$values)." $conds_str";
		if(method_exists($this,'query_affected_rows')){
			$res = $this->query_affected_rows($Q_str);
			return ($res===FALSE || $res === -1)?FALSE:$res;
		}else{
			return (bool) $this->query($Q_str);
		}
	}

	/**
	* get the number of row in $table
	* @param string $table table name
	* @param mixed  $conds
	* @return int
	*/
	public function get_count($table,$conds=null){
		return $this->select_value($table,'count(*) as c',$conds);
	}

	/**
	*return an associative array indexed by $index_field with values $value_fields from
	*a mysqldb->select_rows result
	*@param string $index_field default value is id
	*@param mixed $value_fields (string field name or array of fields name default is null so keep all fields
	*@param array $res the mysqldb->select_rows result
	*@param bool $keep_index if set to true then the index field will be keep in the values associated (unused if $value_fields is string)
	*@param bool $sort_keys will automaticly sort the array by key if set to true @deprecated argument
	*@return array
	*/
	public function associative_array_from_q2a_res($index_field='id',$value_fields=null,$res = null,$keep_index=FALSE,$sort_keys=FALSE){
		if($res===null)
			$res = $this->last_q2a_res;
		if(! is_array($res)){
			$this->verbose("associative_array_from_q2a_res with invalid result",__FUNCTION__,1);
			return FALSE;
		}
		# then verify index exists
		if(!isset($res[0][$index_field])){
			$this->verbose("associative_array_from_q2a_res with invalid index field '$index_field'",__FUNCTION__,1);
			return FALSE;
		}
		# then we do the trick
		if(is_string($value_fields)){
			foreach($res as $row)
				$associatives_res[$row[$index_field]] = $row[$value_fields];
		}elseif(is_array($value_fields)||$value_fields===null){
			foreach($res as $row){
				$associatives_res[$row[$index_field]] = $row;
				if(!$keep_index)
					unset($associatives_res[$row[$index_field]][$index_field]);
			}
		}
		if(! count($associatives_res))
			return FALSE;
		if($sort_keys)
			ksort($associatives_res);
		return $this->last_q2a_res = $associatives_res;
	}
	/*########## INTERNAL METHOD ##########*/

	/**
	* used by other methods to parse the conditions param of a QUERY.
	* If $conds is string then nothing more is done.
	* If it's an array, the first value (index 0) will be consider as the full condition string and all '?' will be replaced by other values in the array (sort of sprintf).
	* You can add a number before a ? to replace it by a given index in the array like 2?
	* @param string|array $conds
	* @return string
	*/
	public function process_conds($conds=null){
		if(is_string($conds) )
			return $conds;
		elseif(! is_array($conds) )
			return '';
		$conds_str = array_shift($conds);
		array_unshift($conds,'');
		$i=0;
		return preg_replace('!(\d*)\?!e',"\$this->prepare_smart_param('\\1'!==''?\$conds['\\1']:(isset(\$conds[++\$i])?\$conds[\$i]:null),'single')",$conds_str);
	}

	/**
	* used internally for smart params processing
	* @private
	*/
	protected function prepare_smart_param($val){
		if(is_null($val)){
			return 'NULL';
		}elseif (is_int($val) || is_float($val)) {
			return $val;
		} elseif(is_string($val)) {
			return "'".$this->escape_string($val,'single')."'";
		} elseif(is_array($val)) {
			return implode(',', array_map(array(&$this,'prepare_smart_param'),$val));
		}else{
			return "''";
		}
	}
	/**
	* used internally to prepare fields for queries
	* @param string|array $fields list of fields. it's up to you to protect fieldsname if you put in fields as string
	*/
	public function protect_field_names($fields){
		if(is_array($fields)){
			foreach($fields as $k=>$f)
				$fields[$k] = $this->_protect_fldname.$f.$this->_protect_fldname;
			$fields = implode(',',$fields);
		}elseif($fields){
			if( $this->_protect_fldname && ! substr_count($fields,$this->_protect_fldname) ){ # if already protected we do nothing
				$fields = preg_replace('!\s*,\s*!',$this->_protect_fldname.','.$this->_protect_fldname,$fields);
				$fields = $this->_protect_fldname . trim($fields) . $this->_protect_fldname;
			}
		}
		return $fields?$fields:false;
	}

	protected function array_to_str($var,$sep=','){
		return (is_string($var)?$var:(is_array($var)?implode($sep,$var):''));
	}

	protected function set_error($callingfunc=null){
		static $i=0;
		if(! $this->db ){
			$this->error[$i]['nb']  = null;
			$this->error[$i]['str'] = '[ERROR] No Db Handler';
		}else{
			$this->error[$i]['nb']  = $this->error_no();
			$this->error[$i]['str'] = $this->error_str($this->error[$i]['nb']);
		}
		$this->last_error = $this->error[$i];
		$this->verbose($this->error[$i]['str'],$callingfunc,1);
		$i++;
	}

	/**
	* print a msg on STDOUT if $this->beverbose is set to true
	* @param string $msg         message output string
	* @param string $callingFunc name of the calling function
	* @param int $msgLvl         the level corresponding to the message
	*                            1-> error message
	*                            2-> query or similar informative message
	*                            msgLvl must be an int not a string as it will be test by type
	* @private
	*/
	protected function verbose($msg,$callingFunc=null,$msgLvl=1){
		if(! $this->beverbose)
			return;
		if( ($msgLvl===2 && $this->beverbose >=2) || ($msgLvl === 1 && $this->beverbose !== 2) ){
			$msg = get_class($this).($callingFunc?"::$callingFunc":'').' => '.$msg;
			$useConsoleApp = ( php_sapi_name()=='cli' && class_exists('console_app',false))?true:false;
			$isError = $msgLvl===1?true:false;
			if($isError){
				if($useConsoleApp)
					return console_app::msg_error($msg);
				echo "<b style=\"color:red;\">[ERROR] $msg</b><br />\n";
			}else{
				if($useConsoleApp)
					return console_app::msg_info($msg);
				echo "<b style=\"color:blue;\">$msg</b><br />\n";
			}
		}
	}

	###*** DEPRECATED METHODS ***###

	/**
	* return the list of field in $table
	* @deprecated still here for compatibility with old version
	* @use and @see db::list_table_fields() instead
	* @param string $table name of the sql table to work on
	* @param bool $extended_info will return the result of a show field query in a query_to_array fashion
	*/
	public function get_fields($table,$extended_info=FALSE){
		return $this->list_table_fields($table,$extended_info);
	}

	/**
	* get the fields list of table
	* @deprecated now the $indexed_by_name args won't exists anymore but will considered as TRUE in all case
	* @see db::list_table_fields as a replacement method
	* @param string $table
	* @param bool $indexed_by_name the return array will be indexed by the fields name if set to true (default is FALSE)
	* @return array
	*/
	public function list_fields($table,$indexed_by_name=FALSE){
		return $this->list_table_fields($table,TRUE);
	}

}
