<?php
/**
   * EDB Class -- Easy Database Class
   * @version 0.1.1
   * @author Eduards Marhelis <eduards.marhelis@gmail.com>
   * @link http://code.google.com/p/edb-php-class/
   * @copyright Copyright 2010 Eduards Marhelis
   * @license http://www.opensource.org/licenses/mit-license.php MIT License
   * @package EDB Class
   */
class edb{
	private	$connection		=	false;
	public	$debug			=	false; //debuging all
	public	$res			=	0; //last result data
	public	$line			=	0; //last line data
	public	$one			=	0; //last one data
	public	$queryAll		= 	array();
	public	$queryCount		= 	0; //tatal query count
	public	$queryTime		= 	0; //total query time
	
	/**
	   * @function 			__Construct 
	   * @description 		Connects to database when created new edb(); object.
	   * @param string 		$host 	Database Host.
	   * @param string 		$user 	Database user.
	   * @param string 		$pass 	Database pass.
	   * @param string 		$db 	Database name.
	   * @return 			nothing.
	   */
	public function __construct($host, $user=0, $pass=0, $db=0){
		$data = $host;
		if(is_array($data)){
			$host = $data[0];
			$user = $data[1];
			$pass = $data[2];
			$db   = $data[3];
		}
			$this->connection = mysql_connect($host, $user, $pass) or die(mysql_error());
			mysql_select_db($db) or die(mysql_error());
		
	}
	/**
	   * @function 			q  (shortening for query) 
	   * @description 		runs mysql query and returns php array.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
	public function q($a){
		$start	=	microtime(1);
		$this->res = array();
		$q = mysql_query("$a", $this->connection) or die(mysql_error());
		while($row = mysql_fetch_array($q)){
			$this->res[] = $row;
		}
		$end = microtime(1);
		
		$this->debugData($start,$end,$a);
		return $this->res;
	}
	/**
	   * @function 			line   
	   * @description 		runs mysql query and returns php array with line from db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
	public function line($a){
		$start	=	microtime(1);
		$query = mysql_query("$a", $this->connection);
		$this->line = mysql_fetch_array( $query );
		$end	=	microtime(1);
		
		$this->debugData($start,$end,$a);
		return $this->line;
	}
	/**
	   * @function 			one   
	   * @description 		runs mysql query and returns php string db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function one($a){
		$start	=	microtime(1);
		$query = mysql_query("$a", $this->connection);
		$r = mysql_fetch_array( $query );
		$end	=	microtime(1);
		
		$this->debugData($start,$end,$a);
		$i=0; if(isset($b)) {$i=$b;}
		$this->one = $r[$i];
		return $this->one;
	}
	/**
	   * @function 			s   
	   * @description 		runs mysql query and returns result from mysql query. used for inserts and updates. 
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function s($a){
		$start	=	microtime(1);
		$q = mysql_query("$a", $this->connection) or die(mysql_error());  
		$end	=	microtime(1);
		$this->debugData($start,$end,$a);
		return $q;
	}
	
	private function setCache($file,$result,$q){
		
	}
	   
	private function debugData($start,$end,$a,$b='DB'){
		$this->queryCount++;
		$t = number_format($end - $start, 8);
		$this->queryTime = $this->queryTime + $t;
		$this->queryAll[ $this->queryCount ] = array('query'=>$a,'time'=>$t,'type'=>$b);
	}
	/**
	   * @function 			__destruct   
	   * @description 		closes mysql connection.
	   */
	public function __destruct(){
		mysql_close($this->connection);
	}

}

?>