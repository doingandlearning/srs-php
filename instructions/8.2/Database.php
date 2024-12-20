<?php
class Database
{
	private static $instance = null;
	private $conn;

	private $host = '127.0.0.1';
	private $port = '3306'; // MySQL default port
	private $user = 'root';
	private $pass = 'root'; // Empty string for password
	private $name = 'srs'; // Target database name

	// Private constructor to prevent creating a new instance from outside
	private function __construct()
	{
		try {
			$this->conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->name}", $this->user, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}

	// Method to get the instance of the database connection
	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	// Method to return the database connection
	public function getConnection()
	{
		return $this->conn;
	}
}