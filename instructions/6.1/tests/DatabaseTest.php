<?php
use PHPUnit\Framework\TestCase;

require_once 'Database.php'; // Ensure Database class is loaded

class DatabaseTest extends TestCase
{
	public function testGetInstanceReturnsSingleton()
	{
		// Test content
		$dbInstance1 = Database::getInstance();
		$dbInstance2 = Database::getInstance();
		$this->assertSame($dbInstance1, $dbInstance2);
	}

	public function testConnectionIsValid()
	{
		// Test content
		$db = Database::getInstance();
		$conn = $db->getConnection();
		$this->assertInstanceOf(PDO::class, $conn);
	}
}
