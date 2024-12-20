<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/User.php';

class UserModelTest extends TestCase
{
	private $pdoMock;
	private $stmtMock;
	private $userModel;

	protected function setUp(): void
	{
		// Mock the PDOStatement
		$this->stmtMock = $this->createMock(PDOStatement::class);

		// Mock the PDO connection
		$this->pdoMock = $this->createMock(PDO::class);

		// Mock the Database class to return the mocked PDO connection
		$databaseMock = $this->createMock(Database::class);
		$databaseMock->method('getConnection')->willReturn($this->pdoMock);

		// Create the User model with the mocked database connection
		$this->userModel = new User();
		$reflection = new ReflectionClass($this->userModel);
		$property = $reflection->getProperty('conn');
		$property->setAccessible(true);
		$property->setValue($this->userModel, $this->pdoMock);
	}

	public function testRegisterFailsWithShortUsername()
	{
		// Act
		$response = $this->userModel->register('ab', 'password123');

		// Assert
		$this->assertFalse($response['success']);
		$this->assertEquals('Username must be at least 3 characters long.', $response['error']);
	}

	public function testRegisterFailsWithShortPassword()
	{
		// Act
		$response = $this->userModel->register('validuser', '123');

		// Assert
		$this->assertFalse($response['success']);
		$this->assertEquals('Password must be at least 6 characters long.', $response['error']);
	}

	public function testRegisterUserInsertsDataCorrectly()
	{
		// Arrange
		$username = 'testuser';
		$password = 'password123';

		// Set expectations for prepare
		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->equalTo("INSERT INTO users (username, password) VALUES (:username, :password)"))
			->willReturn($this->stmtMock);

		// Use a callback to validate bindParam calls
		$this->stmtMock->expects($this->exactly(2))
			->method('bindParam')
			->willReturnCallback(function ($param, $value) use ($username, $password) {
				if ($param === ':username') {
					$this->assertEquals('testuser', $value);
				} elseif ($param === ':password') {
					$this->assertTrue(password_verify('password123', $value));
				} else {
					$this->fail("Unexpected parameter: $param");
				}
				return true; // bindParam must return true
			});

		// Expect execute to be called once
		$this->stmtMock->expects($this->once())
			->method('execute');

		// Act
		$response = $this->userModel->register($username, $password);

		// Assert
		$this->assertTrue($response['success']);
		$this->assertArrayNotHasKey('error', $response);
	}
}
