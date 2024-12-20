<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/Card.php';

class CardControllerTest extends TestCase
{
	private $cardMock;
	private $controller;
	private $lastRedirect;

	protected function setUp(): void
	{
		$this->cardMock = $this->createMock(Card::class);

		// Create a test-specific controller class that overrides the redirect method
		$this->controller = new class ($this->cardMock) extends CardController {
			public $lastRedirect;

			protected function redirect($location)
			{
				$this->lastRedirect = $location;
				return true;
			}
		};

		// Reset $_POST before each test
		$_POST = [];
	}

	protected function tearDown(): void
	{
		$_POST = [];
	}

	public function testAddSuccessful()
	{
		// Arrange
		$_POST['question'] = 'What is PHP?';
		$_POST['answer'] = 'A programming language';

		// Assert that addCard is called with the correct parameters
		$this->cardMock->expects($this->once())
			->method('addCard')
			->with(
				$this->equalTo('What is PHP?'),
				$this->equalTo('A programming language')
			);

		// Act
		$this->controller->add();

		// Assert
		$this->assertEquals('list.php', $this->controller->lastRedirect);
	}

	public function testAddWithMissingQuestion()
	{
		// Arrange
		$_POST['answer'] = 'A programming language';

		// Assert
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Question and answer are required');

		// Act
		$this->controller->add();
	}

	public function testAddWithMissingAnswer()
	{
		// Arrange
		$_POST['question'] = 'What is PHP?';

		// Assert
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Question and answer are required');

		// Act
		$this->controller->add();
	}

	public function testAddWithEmptyStrings()
	{
		// Arrange
		$_POST['question'] = '   ';
		$_POST['answer'] = '   ';

		// Assert
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Question and answer are required');

		// Act
		$this->controller->add();
	}

	public function testAddTrimsWhitespace()
	{
		// Arrange
		$_POST['question'] = '  What is PHP?  ';
		$_POST['answer'] = '  A programming language  ';

		// Assert that addCard is called with trimmed strings
		$this->cardMock->expects($this->once())
			->method('addCard')
			->with(
				$this->equalTo('What is PHP?'),
				$this->equalTo('A programming language')
			);

		// Act
		$this->controller->add();
	}
}