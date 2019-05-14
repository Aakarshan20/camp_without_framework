<?php 

defined('ACC')||exit('ACC denied');

include('./include/config.ini.php');
define('CFG',$_CFG);

// print_r(CFG);

class Mysql{
	private static $instance;
	private static $db;
	public function get_instance(){
		if(!(self::$instance instanceof  self )){
			self::$instance = new self();
		}
		return self::$instance;
	}
	private function __construct(){
		$dsn = "mysql:host=" . CFG['host'] . ";dbname=" . CFG['db'];
		self::$db = new PDO($dsn, CFG['user'],CFG['passwd'] );
		self::$db->query("set names utf8");
		self::$db->query("insert into test (value)values(1)");
		
	}
	
	public function query($sql){
		return self::$db->query($sql);
	}
	public function fetchAll($sql){
		return self::$db->query($sql)->fetchAll();
	}
<<<<<<< HEAD
=======
	public function prepare($sql){
		return self::$db->prepare($sql);
	}
	public function lastInsertId(){
		return self::$db->lastInsertId();
	}
	
>>>>>>> update
}

?>