<?php

class Database
{
	private static $instance = null;
	private $conn;

	private $host = 'docker-mysql.orb.local';
	private $port = '3306'; // MySQL default port
	private $user = 'root';
	private $pass = 'password'; // Empty string for password
	private $name = 'srs'; // Target database name

	private function __construct()
	{
		// Create a new PDO connection
		$this->conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->name}", $this->user, $this->pass);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	public function getConnection()
	{
		return $this->conn;
	}
	public static function setInstance(?Database $instance): void
	{
		self::$instance = $instance;
	}
}
