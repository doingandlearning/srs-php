<?php
// database.php

class Database
{
	private static $instance = null;
	private $conn;

	private $host = '127.0.0.1';
	private $port = '5432';
	private $user = 'postgres';
	private $pass = 'password';
	private $name = 'srs';

	private function __construct()
	{
		$this->conn = new PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->name}", $this->user, $this->pass);
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
	
}
