<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/User.php';

class UserModelLoginTest extends TestCase
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

	public function testLoginFailsWithInvalidCredentials()
	{
		// Arrange
		$username = 'invaliduser';
		$password = 'invalidpassword';

		// Mock the prepare and execute calls
		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->equalTo("SELECT id, username, password FROM users WHERE username = :username"))
			->willReturn($this->stmtMock);

		$this->stmtMock->expects($this->once())
			->method('execute');

		$this->stmtMock->expects($this->once())
			->method('fetch')
			->willReturn(false); // No user found

		// Act
		$response = $this->userModel->login($username, $password);

		// Assert
		$this->assertFalse($response['success']);
		$this->assertEquals('Invalid username or password.', $response['error']);
	}

	public function testLoginSucceedsWithValidCredentials()
	{
		// Arrange
		$username = 'validuser';
		$password = 'validpassword';
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Mock the prepare and execute calls
		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->equalTo("SELECT id, username, password FROM users WHERE username = :username"))
			->willReturn($this->stmtMock);

		$this->stmtMock->expects($this->once())
			->method('execute');

		$this->stmtMock->expects($this->once())
			->method('fetch')
			->willReturn([
				'id' => 1,
				'username' => $username,
				'password' => $hashedPassword
			]);

		// Act
		$response = $this->userModel->login($username, $password);

		// Assert
		$this->assertTrue($response['success']);
		$this->assertEquals(1, $response['user']['id']);
		$this->assertEquals('validuser', $response['user']['username']);
	}
}
