<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/UserCard.php';
require_once __DIR__ . '/../bootstrap.php';
class UserCardTest extends TestCase
{
	private $userCard;
	private $pdoMock;
	private $stmtMock;

	protected function setUp(): void
	{
		// Mock the PDOStatement
		$this->stmtMock = $this->createMock(PDOStatement::class);

		// Mock the PDO connection
		$this->pdoMock = $this->createMock(PDO::class);

		// Mock the Database class to return the mocked PDO connection
		$databaseMock = $this->createMock(Database::class);
		$databaseMock->method('getConnection')->willReturn($this->pdoMock);

		// Instantiate UserCard and inject the mocked PDO connection
		$this->userCard = new UserCard();
		$reflection = new ReflectionClass($this->userCard);
		$property = $reflection->getProperty('conn');
		$property->setAccessible(true);
		$property->setValue($this->userCard, $this->pdoMock);
	}

	public function testGetNextUserCard()
	{
		// Arrange
		$userId = 1;
		$expectedResult = [
			'id' => 1,
			'question' => 'What is PHP?',
			'answer' => 'A programming language',
			'next_review' => '2024-12-18',
		];

		$this->stmtMock->expects($this->once())
			->method('execute');
		$this->stmtMock->expects($this->once())
			->method('fetch')
			->willReturn($expectedResult);

		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->stringContains("SELECT uc.*, c.question, c.answer"))
			->willReturn($this->stmtMock);

		// Act
		$result = $this->userCard->getNextUserCard($userId);

		// Assert
		$this->assertEquals($expectedResult, $result);
	}

	public function testGetRandomUserCard()
	{
		// Arrange
		$userId = 1;
		$expectedResult = [
			'id' => 2,
			'question' => 'What is MVC?',
			'answer' => 'A design pattern',
		];

		$this->stmtMock->expects($this->once())
			->method('execute');
		$this->stmtMock->expects($this->once())
			->method('fetch')
			->willReturn($expectedResult);

		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->stringContains("SELECT uc.*, c.question, c.answer"))
			->willReturn($this->stmtMock);

		// Act
		$result = $this->userCard->getRandomUserCard($userId);

		// Assert
		$this->assertEquals($expectedResult, $result);
	}

	public function testCalculateNextReview()
	{
		// Arrange
		$userCard = [
			'interval' => 6,
			'ease_factor' => 2.5,
			'repetitions' => 3,
		];
		$quality = 4;

		// Act
		$result = $this->userCard->calculateNextReview($userCard, $quality);

		// Assert
		$this->assertArrayHasKey('interval', $result);
		$this->assertArrayHasKey('ease_factor', $result);
		$this->assertArrayHasKey('repetitions', $result);
		$this->assertArrayHasKey('next_review', $result);
		$this->assertGreaterThanOrEqual(1, $result['interval']);
		$this->assertGreaterThanOrEqual(1.3, $result['ease_factor']);
	}

	public function testUpdateUserCard()
	{
		// Arrange
		$id = 1;
		$interval = 6;
		$next_review = '2024-12-19';
		$repetitions = 3;
		$ease_factor = 2.5;
		$last_review_result = 1;

		$this->stmtMock->expects($this->once())
			->method('execute');

		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->stringContains("UPDATE user_cards"))
			->willReturn($this->stmtMock);

		// Act
		$this->userCard->updateUserCard($id, $interval, $next_review, $repetitions, $ease_factor, $last_review_result);

		// Assert: If no exceptions are thrown, the test passes
		$this->assertTrue(true);
	}
}
