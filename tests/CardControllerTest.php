<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/Card.php';
require_once __DIR__ . '/../models/UserCard.php';
require_once __DIR__ . '/../database.php';


class CardControllerTest extends TestCase
{
	private $cardMock;
	private $userCardMock;
	private $controller;

	protected function setUp(): void
	{
		$this->cardMock = $this->createMock(Card::class);
		$this->userCardMock = $this->createMock(UserCard::class);

		$this->controller = new CardController($this->cardMock, $this->userCardMock);
	}

	public function testAdd()
	{
		// Arrange
		$_POST['question'] = 'What is the capital of France?';
		$_POST['answer'] = 'Paris';
		$_SESSION['user_id'] = 1;

		// Expectations
		$this->cardMock->expects($this->once())->method('addCard')->with('What is the capital of France?', 'Paris');
		$this->cardMock->expects($this->once())->method('getLastInsertId')->willReturn(10);
		$this->userCardMock->expects($this->once())->method('addUserCard')->with(1, 10);

		// Act
		$this->controller->add(true);

		// Assert that the redirect location was set correctly
		$this->assertEquals('/controllers/CardController.php?action=list', $this->controller->redirectLocation);
	}
}