<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Card.php';
require_once __DIR__ . '/../Database.php';

class CardTest extends TestCase
{
	private $card;
	private $mockPdo;

	protected function setUp(): void
	{
		// Reset the Database singleton instance
		$reflection = new ReflectionClass(Database::class);
		$instance = $reflection->getProperty('instance');
		$instance->setAccessible(true);
		$instance->setValue(null, null);

		// Create a mock PDO that will be injected into our Database mock
		$this->mockPdo = $this->createMock(PDO::class);

		// Create a mock Database that returns our mock PDO
		$mockDatabase = $this->getMockBuilder(Database::class)
			->disableOriginalConstructor()
			->getMock();

		$mockDatabase->method('getConnection')
			->willReturn($this->mockPdo);

		// Set our mock as the singleton instance
		Database::setInstance($mockDatabase);

		// Create the card instance that will use our mocked database
		$this->card = new Card();
	}

	protected function tearDown(): void
	{
		// Clean up the singleton after each test
		$reflection = new ReflectionClass(Database::class);
		$instance = $reflection->getProperty('instance');
		$instance->setAccessible(true);
		$instance->setValue(null, null);
	}

	public function testAddCard()
	{
		// Create a mock PDOStatement
		$stmt = $this->createMock(PDOStatement::class);

		// Set up expectations for prepare method
		$this->mockPdo->expects($this->once())
			->method('prepare')
			->with("INSERT INTO cards (question, answer) VALUES (:question, :answer)")
			->willReturn($stmt);

		// Set up expectations for bindParam calls
		$stmt->expects($this->exactly(2))
			->method('bindParam')
			->willReturnCallback(function ($param, $value) {
				if ($param === ':question') {
					$this->assertEquals('Test question', $value);
				} elseif ($param === ':answer') {
					$this->assertEquals('Test answer', $value);
				}
				return true;
			});

		$stmt->expects($this->once())
			->method('execute');

		// Call the method
		$this->card->addCard('Test question', 'Test answer');
	}

	public function testAddCardWithEmptyQuestion()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->card->addCard('', 'Test answer');
	}

	public function testAddCardWithEmptyAnswer()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->card->addCard('Test question', '');
	}
}