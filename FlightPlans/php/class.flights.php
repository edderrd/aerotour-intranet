<?
/*
	This SQL query will create the table to store your object.

	CREATE TABLE feedback (
	feedbackid INTEGER PRIMARY KEY,
	name TEXT,
	email TEXT,
	comments TEXT);
*/

/**
* Feedback class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev3
* @see http://www.phpobjectgenerator.com/plog/tutorials/38/pdo-sqlite
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=sqlite&objectName=Feedback&attributeList=array+%28%0A++0+%3D%3E+%27name%27%2C%0A++1+%3D%3E+%27email%27%2C%0A++2+%3D%3E+%27comments%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TEXT%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A%29
*/

require_once 'configuration.php';
class Flight

{
	public $flightId;
	public $fstatus;
	public $fdate;
	public $route;
	public $pax;
	public $tail;
	public $pilot;
	public $client;
	public $leaves;
	public $returns;
	public $fuel;
	public $notes;

	
	function Flight($fstatus='', $fdate='', $route='', $pax='', $tail='', $pilot='', $client='', $leaves='', $returns='', $fuel='', $notes='')
	{
		$this->fstatus = $fstatus;
		$this->fdate = $fdate;
		$this->route = $route;
		$this->pax = $pax;
		$this->tail = $tail;
		$this->pilot = $pilot;
		$this->client = $client;
		$this->leaves = $leaves;
		$this->returns = $returns;
		$this->fuel = $fuel;
		$this->notes = $notes;
	}

	

	/**
	* Gets object from database
	* @param integer $feedbackId 
	* @return object $Feedback
	*/
	function Get($flightId)
	{
		try
		{
			/*
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$stmt = $Database->prepare("select * from schedule where id= ? LIMIT 1");
			if ($stmt->execute(array($flightId)))
			{
				while ($row = $stmt->fetch())
				{
					$this->flightId = $row['id'];
					$this->fstatus = $row['status'];
					$this->fdate = $row['date'];
					$this->route = $row['route'];
					$this->pax = $row['pax'];
					$this->tail = $row['tail'];
					$this->pilot = $row['pilot'];
					$this->client = $row['client'];
					$this->leaves = $row['leaves'];
					$this->returns = $row['returns'];
					$this->fuel = $row['fuel'];
					$this->notes = $row['notes'];
				}
			}
			return $this;
			*/
			//$dbname='../data/flights.sqlite';
			//$mytable = 'schedule';
			$dbname = DB_NAME;
			$mytable = DB_TABLE;
			
			$base=new SQLiteDatabase($dbname, 0666, $err);
			if ($err)  exit($err);		
							
			//read data from database
			$query = "select * from schedule where id= %d LIMIT 1";
			$query = sprintf($query, $flightId);
			
			$results = $base->arrayQuery($query, SQLITE_ASSOC);
			$size = count($results);
			
			foreach ($results as $row) {
				$this->flightId = $row['id'];
				$this->fstatus = $row['status'];
				$this->fdate = $row['date'];
				$this->route = $row['route'];
				$this->pax = $row['pax'];
				$this->tail = $row['tail'];
				$this->pilot = $row['pilot'];
				$this->client = $row['client'];
				$this->leaves = $row['leaves'];
				$this->returns = $row['returns'];
				$this->fuel = $row['fuel'];
				$this->notes = $row['notes'];
			}
			
			return $this;
		}
		catch (Exception $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param string $field 
	* @param string $comparator 
	* @param string $fieldValue 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @return array $feedbackList
	*/
	static function GetFlightList($field,$comparator,$fieldValue,$sortBy="",$ascending=true,$optionalConditions="")
	{
		$feedbackList = Array();
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$sql = "select id from schedule where $field $comparator '$fieldValue'";
			foreach ($Database->query($sql) as $row)
			{
				$flight = new Flight();
				$flight->Get($row['id']);
				$flightList[] = $flight;
			}
			
			switch(strtolower($sortBy))
			{
				case strtolower("status"):
					usort($flightList, array("Flight","CompareFlightBystatus"));
				if (!$ascending)
					{
						$flightList = array_reverse($flightList);
					}
				break;
				
				case strtolower("date"):
					usort($flightList, array("Flight","CompareFlightBydate"));
				if (!$ascending)
					{
						$flightList = array_reverse($flightList);
					}
				break;
				
				case strtolower("pilot"):
					usort($flightList, array("Flight","CompareFlightBypilot"));
				if (!$ascending)
					{
						$flightList = array_reverse($flightList);
					}
				break;

				case "":
				default:
				break;

			}
			return $flightList;
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	

	

	/**
	* Saves the object to the database
	* @return integer $flightId
	*/
	function Save()
	{
		try
		{
		
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$count=0;
			$sql = "select count(id) as count from schedule where id = '$this->flightId'";

			foreach ($Database->query($sql) as $row)
			{
				$count=$row['count'];
			}
			if ($count == 1)
			{
				// update object
				$stmt = $Database->prepare("update schedule set status=?,date=?,route=?,pax=?,tail=?,pilot=?,client=?,leaves=?,returns=?,fuel=?,notes=? where id=?");
				$stmt->bindParam(1, $this->fstatus);
				$stmt->bindParam(2, $this->fdate);
				$stmt->bindParam(3, $this->route);
				$stmt->bindParam(4, $this->pax);
				$stmt->bindParam(5, $this->tail);
				$stmt->bindParam(6, $this->pilot);
				$stmt->bindParam(7, $this->client);
				$stmt->bindParam(8, $this->leaves);
				$stmt->bindParam(9, $this->returns);
				$stmt->bindParam(10, $this->fuel);
				$stmt->bindParam(11, $this->notes);
				$stmt->bindParam(12, $this->flightId);
			}
			else
			{
				// insert object
				$stmt = $Database->prepare("insert into schedule (status,date,route,pax,tail,pilot,client,leaves,returns,fuel,notes) values (?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $this->fstatus);
				$stmt->bindParam(2, $this->fdate);
				$stmt->bindParam(3, $this->route);
				$stmt->bindParam(4, $this->pax);
				$stmt->bindParam(5, $this->tail);
				$stmt->bindParam(6, $this->pilot);
				$stmt->bindParam(7, $this->client);
				$stmt->bindParam(8, $this->leaves);
				$stmt->bindParam(9, $this->returns);
				$stmt->bindParam(10, $this->fuel);
				$stmt->bindParam(11, $this->notes);
			}
			$stmt->execute();

			if ($this->flightId == "")
			{
				$this->flightId = $Database->lastInsertId();
			}
			return $this->flightId;
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	

	
	/**
	* Clones the object and saves it to the database
	* @return integer $feedbackId
	*/
	function SaveNew()
	{
		$this->flightId='';
		return $this->Save();
	}

		

	/**
	* Deletes the object from the database
	* @return integer $affectedRows
	*/
	function Delete()
	{
		try
		{
			$Database = new PDO($GLOBALS['configuration']['pdoDriver'].':'.$GLOBALS['configuration']['sqliteDatabase']);
			$affectedRows = $Database->query("delete from schedule where id = '$this->flightId'");
			if ($affectedRows != null)
			{
				return $affectedRows;
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	

	/**
	* private function to sort an array of Feedback by name
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFlightBystatus($flight1, $flight2)
	{
		return strcmp(strtolower($flight1->status), strtolower($flight2->status));
	}

	
	/**
	* private function to sort an array of Feedback by email
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFlightBydate($flight1, $flight2)
	{
		return strcmp(strtolower($flight1->date), strtolower($flight2->date));
	}

	
	/**
	* private function to sort an array of Feedback by comments
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	static function CompareFlightBypilot($flight1, $flight2)
	{
		return strcmp(strtolower($flight1->pilot), strtolower($flight2->pilot));
	}
}

?>