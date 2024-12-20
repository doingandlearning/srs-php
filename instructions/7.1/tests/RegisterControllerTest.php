<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/User.php';

class RegisterControllerTest extends TestCase
{
	private $userMock;

	protected function setUp(): void
	{
		// Mock the User model
		$this->userMock = $this->createMock(User::class);
	}

	public function testRegistrationRedirectsOnSuccess()
	{
		// Arrange: Simulate a successful registration
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST['username'] = 'testuser';
		$_POST['password'] = 'password123';

		$this->userMock->expects($this->once())
			->method('register')
			->with(
				$this->equalTo('testuser'),
				$this->equalTo('password123')
			)
			->willReturn(['success' => true]);

		// Mock the header function to capture the redirect
		$this->expectOutputRegex('/^$/'); // No output expected

		// Act: Simulate request
		$userModel = $this->userMock;
		include __DIR__ . '/../register.php';

		// Assert: Successful registration leads to a redirect
		$this->assertTrue(headers_sent() === false, "Redirect should send headers");
	}

	public function testRegistrationFailsAndRendersError()
	{
		// Arrange: Simulate validation failure
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST['username'] = 'ab'; // Too short
		$_POST['password'] = 'password123';

		$this->userMock->expects($this->once())
			->method('register')
			->with(
				$this->equalTo('ab'),
				$this->equalTo('password123')
			)
			->willReturn([
				'success' => false,
				'error' => 'Username must be at least 3 characters long.',
			]);

		// Start output buffering
		ob_start();

		// Act: Simulate request
		$userModel = $this->userMock;
		include __DIR__ . '/../register.php';

		// Get the output
		$output = ob_get_clean();

		// Assert: Check if error message is rendered
		$this->assertStringContainsString('Username must be at least 3 characters long.', $output);
	}

	public function testRendersRegistrationFormOnGet()
	{
		// Arrange: Simulate a GET request
		$_SERVER['REQUEST_METHOD'] = 'GET';

		// Start output buffering
		ob_start();

		// Act: Include the script
		include __DIR__ . '/../register.php';

		// Get the output
		$output = ob_get_clean();

		// Assert: Ensure the form is rendered
		$this->assertStringContainsString('<form action="/register.php" method="POST">', $output);
	}
}
