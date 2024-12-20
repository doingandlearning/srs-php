<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/Card.php';
require_once __DIR__ . '/../models/UserCard.php';

class UserListCardControllerTest extends TestCase
{
	private $cardMock;
	private $userCardMock;
	private $controller;
	private $lastRedirect;

	protected function setUp(): void
	{
		$this->cardMock = $this->createMock(Card::class);
		$this->userCardMock = $this->createMock(UserCard::class);

		// Create a test-specific controller class that overrides the redirect method
		$this->controller = new class ($this->cardMock, $this->userCardMock) extends CardController {
			public $lastRedirect;

			protected function redirect($location)
			{
				$this->lastRedirect = $location;
				return true;
			}
		};

		$_SESSION = [];
	}

	/**
	 * Step 1: Test Constructor
	 */
	public function testConstructorInitializesWithDependencies()
	{
		// Create a regular controller for testing the constructor
		$regularController = new CardController($this->cardMock, $this->userCardMock);

		$reflection = new ReflectionClass(CardController::class);

		// Ensure the Card model is set correctly
		$cardProperty = $reflection->getProperty('cardModel');
		$cardProperty->setAccessible(true);
		$this->assertSame($this->cardMock, $cardProperty->getValue($regularController));

		// Ensure the UserCard model is set correctly
		$userCardProperty = $reflection->getProperty('userCardModel');
		$userCardProperty->setAccessible(true);
		$this->assertSame($this->userCardMock, $userCardProperty->getValue($regularController));
	}

	/**
	 * Step 2: Test addToUserList
	 */
	public function testAddToUserListAddsCardSuccessfully()
	{
		$_SESSION['user_id'] = 1;
		$_GET['card_id'] = 2;

		$this->userCardMock->expects($this->once())
			->method('isCardInUserList')
			->with(1, 2)
			->willReturn(false);

		$this->userCardMock->expects($this->once())
			->method('addUserCard')
			->with(1, 2);

		$this->controller->addToUserList();

		$this->assertEquals('/list.php', $this->controller->lastRedirect);
	}

	public function testAddToUserListDoesNotAddDuplicateCard()
	{
		$_SESSION['user_id'] = 1;
		$_GET['card_id'] = 2;

		$this->userCardMock->expects($this->once())
			->method('isCardInUserList')
			->with(1, 2)
			->willReturn(true);

		$this->userCardMock->expects($this->never())
			->method('addUserCard');

		$this->controller->addToUserList();

		$this->assertEquals('/list.php', $this->controller->lastRedirect);
	}

	public function testAddToUserListRedirectsWhenNotLoggedIn()
	{
		$_SESSION = [];
		$_GET['card_id'] = 2;

		$this->userCardMock->expects($this->never())
			->method('isCardInUserList');

		$this->userCardMock->expects($this->never())
			->method('addUserCard');

		$this->controller->addToUserList();

		$this->assertEquals('/login.php', $this->controller->lastRedirect);
	}

	/**
	 * Step 3: Test list Method
	 */
	public function testListMethodAugmentsCardsWhenLoggedIn()
	{
		$_SESSION['user_id'] = 1;

		// Mock listCards to return sample cards
		$cards = [
			['id' => 1, 'question' => 'What is PHP?', 'answer' => 'A programming language'],
			['id' => 2, 'question' => 'What is MVC?', 'answer' => 'A design pattern'],
		];
		$this->cardMock->expects($this->once())
			->method('listCards')
			->willReturn($cards);

		// Mock isCardInUserList
		$this->userCardMock->expects($this->exactly(2))
			->method('isCardInUserList')
			->willReturnMap([
				[1, 1, true], // User has card 1
				[1, 2, false], // User does not have card 2
			]);

		// Act
		$result = $this->controller->list();

		// Assert: Cards are augmented correctly
		$this->assertCount(2, $result);
		$this->assertTrue($result[0]['user_id']);
		$this->assertFalse($result[1]['user_id']);
	}

	public function testListMethodDoesNotAugmentCardsWhenNotLoggedIn()
	{
		$_SESSION = [];

		// Mock listCards to return sample cards
		$cards = [
			['id' => 1, 'question' => 'What is PHP?', 'answer' => 'A programming language'],
			['id' => 2, 'question' => 'What is MVC?', 'answer' => 'A design pattern'],
		];
		$this->cardMock->expects($this->once())
			->method('listCards')
			->willReturn($cards);

		// Ensure isCardInUserList is NOT called
		$this->userCardMock->expects($this->never())
			->method('isCardInUserList');

		// Act
		$result = $this->controller->list();

		// Assert: Cards are returned without augmentation
		$this->assertEquals($cards, $result);
	}
}
