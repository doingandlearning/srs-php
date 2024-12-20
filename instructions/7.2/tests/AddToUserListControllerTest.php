<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/UserCard.php';
require_once __DIR__ . '/../bootstrap.php';
class AddToUserListControllerTest extends TestCase
{
	private $userCardMock;
	private $controller;

	protected function setUp(): void
	{
		// Mock the UserCard model
		$this->userCardMock = $this->createMock(UserCard::class);

		// Create a test-friendly CardController with overridden redirect
		$this->controller = new class ($this->userCardMock) extends CardController {
			public $redirectUrl = null;

			protected function redirect($url)
			{
				$this->redirectUrl = $url; // Capture the redirect URL instead of exiting
			}
		};

		// Reset session and GET variables before each test
		$_SESSION = [];
		$_GET = [];
	}

	public function testAddToUserListAddsCardSuccessfully()
	{
		// Simulate a logged-in user and a valid card ID
		$_SESSION['user_id'] = 1;
		$_GET['card_id'] = 2;

		// Mock isCardInUserList to return false
		$this->userCardMock->expects($this->once())
			->method('isCardInUserList')
			->with(1, 2)
			->willReturn(false);

		// Mock addUserCard to be called
		$this->userCardMock->expects($this->once())
			->method('addUserCard')
			->with(1, 2);

		// Act
		$this->controller->addToUserList();

		// Assert redirect to list.php
		$this->assertEquals('/list.php', $this->controller->redirectUrl);
	}

	public function testAddToUserListDoesNotAddDuplicateCard()
	{
		// Simulate a logged-in user and a valid card ID
		$_SESSION['user_id'] = 1;
		$_GET['card_id'] = 2;

		// Mock isCardInUserList to return true (card already added)
		$this->userCardMock->expects($this->once())
			->method('isCardInUserList')
			->with(1, 2)
			->willReturn(true);

		// Ensure addUserCard is NOT called
		$this->userCardMock->expects($this->never())
			->method('addUserCard');

		// Act
		$this->controller->addToUserList();

		// Assert redirect to list.php
		$this->assertEquals('/list.php', $this->controller->redirectUrl);
	}

	public function testAddToUserListRedirectsWhenNotLoggedIn()
	{
		// Simulate no user session and a valid card ID
		$_SESSION = [];
		$_GET['card_id'] = 2;

		// Ensure isCardInUserList and addUserCard are never called
		$this->userCardMock->expects($this->never())
			->method('isCardInUserList');
		$this->userCardMock->expects($this->never())
			->method('addUserCard');

		// Act
		$this->controller->addToUserList();

		// Assert redirect to login.php
		$this->assertEquals('/login.php', $this->controller->redirectUrl);
	}
}
