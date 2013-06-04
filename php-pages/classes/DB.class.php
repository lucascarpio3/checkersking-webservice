<?php

class DB {
	private static $instance;
	private $MySQLi;
	
	public static $dbOptions = array(
				'db_host' => '127.0.0.1',
				'db_user' => 'root',
				'db_pass' => 'root',
				'db_name' => 'checkersking'
			);
	
	private function __construct(){
		
		$this->MySQLi = @ new mysqli(	self::$dbOptions['db_host'],
										self::$dbOptions['db_user'],
										self::$dbOptions['db_pass'],
										self::$dbOptions['db_name'] );

		if (mysqli_connect_errno()) {
			throw new Exception('Database error.');
		}

		$this->MySQLi->set_charset("utf8");
	}
	
	public static function init(){

		if(self::$instance instanceof self){
			return false;
		}
		
		self::$instance = new self();
		return mysql_insert_id();
	}
	
	public static function getMySQLiObject(){
		return self::$instance->MySQLi;
	}
	
	public static function query($q){
		
		return self::$instance->MySQLi->query($q);
	}
	
	public static function esc($str){
		return self::$instance->MySQLi->real_escape_string(htmlspecialchars($str));
	}
}

?>