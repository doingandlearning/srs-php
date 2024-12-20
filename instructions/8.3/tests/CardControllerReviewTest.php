<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/UserCard.php';
require_once __DIR__ . '/../bootstrap.php';

class CardControllerReviewTest extends TestCase
{
	private $cardMock;
	private $userCardMock;
	private $controller;

	protected function setUp(): void
	{
		// Mock the Card model
		$this->cardMock = $this->createMock(Card::class);

		// Mock the UserCard model
		$this->userCardMock = $this->createMock(UserCard::class);

		// Create a test-friendly CardController with a mock redirect method
		$this->controller = new class ($this->cardMock, $this->userCardMock) extends CardController {
			public $redirectUrl = null;

			protected function redirect($url)
			{
				$this->redirectUrl = $url; // Capture the redirect URL for assertions
			}
			protected function require($file)
			{
				// Stub require to do nothing during tests
				return;
			}
		};

		// Initialize session
		$_SESSION = ['user_id' => 1];
		$_GET = [];
		$_POST = [];
	}

	/**
	 * Test getNext Method
	 */
	public function testGetNextFetchesNextCard()
	{
		// Arrange
		$expectedCard = [
			'id' => 1,
			'question' => 'What is PHP?',
			'answer' => 'A programming language',
		];

		$this->userCardMock->expects($this->once())
			->method('getNextUserCard')
			->with(1)
			->willReturn($expectedCard);

		$this->userCardMock->expects($this->never())
			->method('getRandomUserCard');

		// Act
		ob_start(); // Capture output
		$this->controller->getNext();
		ob_end_clean(); // Ignore output since require is stubbed

		// Assert: Ensure the mock was called and the flow worked
		$this->assertTrue(true); // If no errors, the test passes
	}



	public function testGetNextFetchesRandomCardIfNoCardDue()
	{
		// Arrange
		$expectedCard = [
			'id' => 2,
			'question' => 'What is MVC?',
			'answer' => 'A design pattern',
		];

		$this->userCardMock->expects($this->once())
			->method('getNextUserCard')
			->with(1)
			->willReturn(null);

		$this->userCardMock->expects($this->once())
			->method('getRandomUserCard')
			->with(1)
			->willReturn($expectedCard);

		// Act
		ob_start();
		$this->controller->getNext();
		ob_end_clean();

		// Assert
		$this->assertTrue(true); // If no errors, the test passes
	}

	/**
	 * Test review Method
	 */
	public function testReviewProcessesCorrectAnswer()
	{
		// Arrange
		$_GET['id'] = 1;
		$_POST['response'] = 'correct';

		$userCard = [
			'id' => 1,
			'interval' => 6,
			'ease_factor' => 2.5,
			'repetitions' => 3,
		];
		$nextReviewData = [
			'interval' => 10,
			'next_review' => '2024-12-20',
			'repetitions' => 4,
			'ease_factor' => 2.6,
		];

		$this->userCardMock->expects($this->once())
			->method('getUserCardById')
			->with(1, 1)
			->willReturn($userCard);

		$this->userCardMock->expects($this->once())
			->method('calculateNextReview')
			->with($userCard, 5) // Quality for "correct"
			->willReturn($nextReviewData);

		$this->userCardMock->expects($this->once())
			->method('updateUserCard')
			->with(
				1,
				$nextReviewData['interval'],
				$nextReviewData['next_review'],
				$nextReviewData['repetitions'],
				$nextReviewData['ease_factor'],
				'correct'
			);

		$this->userCardMock->expects($this->once())
			->method('logProgress')
			->with(1, 1, 'correct');

		// Act
		$this->controller->review();

		// Assert
		$this->assertEquals('/review.php', $this->controller->redirectUrl);
	}

	public function testReviewProcessesIncorrectAnswer()
	{
		// Arrange
		$_GET['id'] = 1;
		$_POST['response'] = 'incorrect';

		$userCard = [
			'id' => 1,
			'interval' => 6,
			'ease_factor' => 2.5,
			'repetitions' => 3,
		];
		$nextReviewData = [
			'interval' => 1, // Reset interval for incorrect answer
			'next_review' => '2024-12-18',
			'repetitions' => 0,
			'ease_factor' => 1.3, // Minimum ease factor
		];

		$this->userCardMock->expects($this->once())
			->method('getUserCardById')
			->with(1, 1)
			->willReturn($userCard);

		$this->userCardMock->expects($this->once())
			->method('calculateNextReview')
			->with($userCard, 2) // Quality for "incorrect"
			->willReturn($nextReviewData);

		$this->userCardMock->expects($this->once())
			->method('updateUserCard')
			->with(
				1,
				$nextReviewData['interval'],
				$nextReviewData['next_review'],
				$nextReviewData['repetitions'],
				$nextReviewData['ease_factor'],
				'incorrect'
			);

		$this->userCardMock->expects($this->once())
			->method('logProgress')
			->with(1, 1, 'incorrect');

		// Act
		$this->controller->review();

		// Assert
		$this->assertEquals('/review.php', $this->controller->redirectUrl);
	}
}
