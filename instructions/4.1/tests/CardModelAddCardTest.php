<?php
use PHPUnit\Framework\TestCase;

require_once 'models/Card.php'; // Ensure Card class is loaded
require_once 'Database.php'; // Ensure Database class is loaded

class CardModelAddCardTest extends TestCase
{
	public function testAddCard()
	{
		// Create a mock for the PDOStatement class
		$stmtMock = $this->createMock(PDOStatement::class);
		// Define the expected values
		$expectedParams = [
			[':question', 'What is PHP?'],
			[':answer', 'A scripting language.']
		];

		$matcher = $this->exactly(2);
		$stmtMock->expects($matcher)
			->method('bindParam')
			->willReturnCallback(function ($param, $value) use ($matcher, $expectedParams) {
				// Use the numberOfInvocations to check each call
				$currentInvocation = $matcher->numberOfInvocations();
				$expected = $expectedParams[$currentInvocation - 1];
				$this->assertEquals($expected[0], $param, "Parameter mismatch on call $currentInvocation");
				$this->assertEquals($expected[1], $value, "Value mismatch on call $currentInvocation");

				return true;
			});

		// Expect the `execute` method to be called once
		$stmtMock->expects($this->once())
			->method('execute');
		// Create a mock for the PDO class
		$pdoMock = $this->createMock(PDO::class);

		// Expect the `prepare` method to be called with a specific query
		$pdoMock->expects($this->once())
			->method('prepare')
			->with("INSERT INTO cards (question, answer) VALUES (:question, :answer)")
			->willReturn($stmtMock);

		// Create a mock for the Database class
		$databaseMock = $this->createMock(Database::class);
		$databaseMock->method('getConnection')->willReturn($pdoMock);

		// Use dependency injection to replace the database connection in the Card class
		$card = new Card();
		$reflection = new ReflectionClass($card);
		$property = $reflection->getProperty('conn');
		$property->setAccessible(true);
		$property->setValue($card, $pdoMock);

		// Call the method under test
		$card->addCard('What is PHP?', 'A scripting language.');

		// Assertions are handled by the expectations on the mocks
	}
}
