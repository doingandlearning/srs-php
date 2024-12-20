<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';
class ListCardControllerTest extends TestCase
{
	private $cardMock;
	private $controller;

	protected function setUp(): void
	{
		// Mock the Card model
		$this->cardMock = $this->createMock(Card::class);

		// Create the controller instance with the mocked Card model
		$this->controller = new CardController($this->cardMock);
	}

	public function testListCallsListCards()
	{
		// Arrange
		$mockData = [
			['id' => 1, 'question' => 'What is PHP?', 'answer' => 'A programming language'],
			['id' => 2, 'question' => 'What is PHPUnit?', 'answer' => 'A testing framework']
		];

		// Expect the model's listCards method to be called once
		$this->cardMock->expects($this->once())
			->method('listCards')
			->willReturn($mockData);

		// Act
		$result = $this->controller->list();

		// Assert
		$this->assertEquals($mockData, $result, "The list method did not return the correct data.");
	}
}
