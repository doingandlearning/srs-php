<?php

use PHPUnit\Framework\TestCase;


require_once __DIR__ . '/../bootstrap.php';

class LoginViewTest extends TestCase
{
	public function testLoginFormIsRenderedWithError()
	{
		// Arrange
		$error = 'Invalid username or password.';
		ob_start();

		// Act
		include __DIR__ . '/../views/login.php';
		$output = ob_get_clean();

		// Assert
		$this->assertStringContainsString('<form action="/login.php" method="POST">', $output);
		$this->assertStringContainsString('Invalid username or password.', $output);
		$this->assertStringContainsString('<label for="username">Username:</label>', $output);
		$this->assertStringContainsString('<label for="password">Password:</label>', $output);
	}

	public function testLoginFormIsRenderedWithoutError()
	{
		// Arrange
		$error = '';
		ob_start();

		// Act
		include __DIR__ . '/../views/login.php';
		$output = ob_get_clean();

		// Assert
		$this->assertStringContainsString('<form action="/login.php" method="POST">', $output);
		$this->assertStringNotContainsString('Invalid username or password.', $output);
	}
}
