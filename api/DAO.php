<?php 

require_once __DIR__ . '/Tools.php';

class DAO {
	private $pdo;
	private $TN;
	private static $SELF_OBJECT;

	public static function new() {
		if (!(self::$SELF_OBJECT instanceof self)) {
			self::$SELF_OBJECT = new self();
		}
		return self::$SELF_OBJECT;
	}

	private function __construct() {
		$config = CONFIG['database'];
		$type = $config['type'] ?? 'mysql';
		$host = $config['host'] ?? 'localhost';
		$port = $config['port'] ?? 3306;
		$user = $config['user'] ?? 'root';
		$password = $config['password'] ?? 'root';
		$db = $config['db'] ?? 'root';
		$this->TN = $config['table_name'] ?? 'dao';
		$this->pdo = new PDO(
			"$type:dbname=$db;host=$host;port=$port", 
			$user, 
			$password, 
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			]
		);
		$this->exec("SET NAMES utf8mb4");
	}

	private function replace_PRE($sql) {
		return str_replace('TN', $this->TN, $sql);
	}

	private static function process_pdo_error($e) {
		Tools::die("PDO ERROR: " . $e->getMessage(), 500);
	}

	public function exec($sql) {
		$sql = $this->replace_PRE($sql);
		try {
			return $this->pdo->exec($sql);
		} catch (PDOException $e) {
			self::process_pdo_error($e);
		}
	}

	public function last_ins_id() {
		return $this->pdo->lastInsertId();
	}

	public function query($sql) {
		$sql = $this->replace_PRE($sql);
		try {
			$PDOStatement = $this->pdo->query($sql);
			return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			self::process_pdo_error($e);
		}
	}

	public function query_once($sql) {
		$sql = $this->replace_PRE($sql);
		try {
			$PDOStatement = $this->pdo->query($sql);
			return $PDOStatement->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			self::process_pdo_error($e);
		}
	}

	public function pp($sql) {
		$sql = $this->replace_PRE($sql);
		return new DAOPrepare($this->pdo->prepare($sql));
	}
}

class DAOPrepare {
	private $stmt;

	public function __construct($stmt) {
		$this->stmt = $stmt;
	}

	public function bv($id, $value) {
		$this->stmt->bindValue($id, $value);
	}

	public function exec() {
		try {
			return $this->stmt->execute();
		} catch (PDOException $e) {
			Tools::die("PDO PREPARE EXEC ERROR: " . $e->getMessage(), 500);
		}
	}

	public function gd() {
		try {
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			Tools::die("PDO PREPARE FETCH ERROR: " . $e->getMessage(), 500);
		}
	}
}