<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class ListCardTest extends TestCase
{
	private $pdo;
	private $card;

	protected function setUp(): void
	{
		// Set up an in-memory SQLite database
		$this->pdo = new PDO('sqlite::memory:');
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Create a table for the cards
		$this->pdo->exec("
					CREATE TABLE cards (
							id INTEGER PRIMARY KEY AUTOINCREMENT,
							question TEXT NOT NULL,
							answer TEXT NOT NULL
					)
			");

		// Inject the PDO connection into the Card class
		$this->card = new Card();
		$reflection = new ReflectionClass($this->card);
		$property = $reflection->getProperty('conn');
		$property->setAccessible(true);
		$property->setValue($this->card, $this->pdo);
	}

	public function testListCardsReturnsPopulatedData()
	{
		// Arrange: Insert some test data
		$this->pdo->exec("INSERT INTO cards (question, answer) VALUES ('What is PHP?', 'A programming language')");
		$this->pdo->exec("INSERT INTO cards (question, answer) VALUES ('What is PHPUnit?', 'A testing framework')");

		// Act
		$result = $this->card->listCards();

		// Assert
		$this->assertCount(2, $result);
		$this->assertEquals('What is PHP?', $result[0]['question']);
		$this->assertEquals('A programming language', $result[0]['answer']);
		$this->assertEquals('What is PHPUnit?', $result[1]['question']);
		$this->assertEquals('A testing framework', $result[1]['answer']);
	}
}
