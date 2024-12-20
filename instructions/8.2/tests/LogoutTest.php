<?php

use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
	private $testHeaders;

	protected function setUp(): void
	{
		$this->testHeaders = []; // Initialize the headers array

		// Ensure a session is active
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$_SESSION['user_id'] = 1;
		$_SESSION['username'] = 'testuser';
	}

	protected function tearDown(): void
	{
		// Clean up session after each test
		// Check if a session is active before unsetting and destroying
		if (session_status() === PHP_SESSION_ACTIVE) {
			session_unset();
			session_destroy();
		}
		$_SESSION = [];
	}

	public function testLogoutClearsSessionAndRedirects()
	{
		// Custom header function to capture headers
		$headerFunction = function ($header) {
			$this->testHeaders[] = $header;
		};

		// Act: Include the logout.php file with the custom header function
		include __DIR__ . '/../logout.php';

		// Assert: Session variables are cleared
		$this->assertEmpty($_SESSION, 'Session variables should be empty after logout.');

		// Assert: Check for redirect in test headers
		$this->assertContains('Location: /login.php', $this->testHeaders, 'Logout should redirect to /login.php');
	}
}
