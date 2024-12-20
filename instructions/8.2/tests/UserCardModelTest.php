<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/UserCard.php';
require_once __DIR__ . '/../bootstrap.php';

class UserCardModelTest extends TestCase
{
	private $pdoMock;
	private $stmtMock;
	private $userCard;

	protected function setUp(): void
	{
		// Mock the PDOStatement
		$this->stmtMock = $this->createMock(PDOStatement::class);

		// Mock the PDO connection
		$this->pdoMock = $this->createMock(PDO::class);

		// Mock the Database class to return the mocked PDO connection
		$databaseMock = $this->createMock(Database::class);
		$databaseMock->method('getConnection')->willReturn($this->pdoMock);

		// Inject the mock connection into the UserCard class
		$this->userCard = new UserCard();
		$reflection = new ReflectionClass($this->userCard);
		$property = $reflection->getProperty('conn');
		$property->setAccessible(true);
		$property->setValue($this->userCard, $this->pdoMock);
	}

	public function testAddUserCardSuccess()
	{
		// Arrange
		$userId = 1;
		$cardId = 2;

		// Prepare expectations for the query
		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->equalTo("INSERT INTO user_cards (user_id, card_id) VALUES (:user_id, :card_id)"))
			->willReturn($this->stmtMock);

		// Verify sequential calls to bindParam using invocation count
		$this->stmtMock->expects($this->exactly(2))
			->method('bindParam')
			->willReturnCallback(function ($param, $value) use ($userId, $cardId) {
				static $callCount = 0;
				$callCount++;
				if ($callCount === 1) {
					$this->assertEquals(':user_id', $param);
					$this->assertEquals($userId, $value);
				} elseif ($callCount === 2) {
					$this->assertEquals(':card_id', $param);
					$this->assertEquals($cardId, $value);
				}
				return true; // Simulate successful bindParam
			});

		$this->stmtMock->expects($this->once())
			->method('execute');

		// Act
		$this->userCard->addUserCard($userId, $cardId);

		// Assert: If no exceptions are thrown, the test passes
		$this->assertTrue(true);
	}

	public function testIsCardInUserListReturnsCard()
	{
		// Arrange
		$userId = 1;
		$cardId = 2;
		$expectedResult = ['user_id' => $userId, 'card_id' => $cardId];

		// Prepare expectations for the query
		$this->pdoMock->expects($this->once())
			->method('prepare')
			->with($this->equalTo("SELECT * FROM user_cards WHERE user_id = :user_id AND card_id = :card_id"))
			->willReturn($this->stmtMock);

		$this->stmtMock->expects($this->exactly(2))
			->method('bindParam')
			->willReturnCallback(function ($param, $value) use ($userId, $cardId) {
				static $callCount = 0;
				$callCount++;
				if ($callCount === 1) {
					$this->assertEquals(':user_id', $param);
					$this->assertEquals($userId, $value);
				} elseif ($callCount === 2) {
					$this->assertEquals(':card_id', $param);
					$this->assertEquals($cardId, $value);
				}
				return true;
			});

		$this->stmtMock->expects($this->once())
			->method('execute');

		$this->stmtMock->expects($this->once())
			->method('fetch')
			->with(PDO::FETCH_ASSOC)
			->willReturn($expectedResult);

		// Act
		$result = $this->userCard->isCardInUserList($userId, $cardId);

		// Assert
		$this->assertEquals($expectedResult, $result);
	}
}
