<?php
namespace Root\Basic;

class Model{
	
	protected $connection;
	
	public function __construct(){
		global $config;
		$this->connection = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['db_charset'], 
		$config['db_username'], $config['db_password'], 
		array(
			PDO::MYSQL_ATTR_FOUND_ROWS => true,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		));
	}
	
	
	public function query($sql, $params = false){
		try {
			$dbh = $this->connection;
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$dbh->beginTransaction();
			$q = $dbh->prepare($sql);
			if (!$q) {
				echo "PDO::errorInfo():";
				print_r($dbh->errorInfo());
			}

			if ($params) {
				foreach($params as $key => & $val) {
					if (is_array($val) && count($val) == 3)
						$q->bindParam($key, $val[0], $val[1], $val[2]);
					elseif (is_array($val) && count($val) == 2)
						$q->bindParam($key, $val[0], $val[1]);
					else
						$q->bindParam($key, $val);
				}
			}

			$q->execute();
			return $q;
		}

		catch(PDOException $e) {
			print ("ERROR: " . $e->getMessage());
			return false;
		}
	}
}