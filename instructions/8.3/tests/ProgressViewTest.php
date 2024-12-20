<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class ProgressViewTest extends TestCase
{
	private function renderProgressView($progress)
	{
		ob_start();
		include __DIR__ . '/../views/progress.php';
		return ob_get_clean();
	}

	public function testProgressDataRendersCorrectly()
	{
		// Arrange
		$progress = [
			[
				'card_id' => 1,
				'question' => 'What is PHP?',
				'answer' => 'A programming language',
				'total_reviews' => 10,
				'total_correct_answers' => 8,
			],
			[
				'card_id' => 2,
				'question' => 'What is MVC?',
				'answer' => 'A design pattern',
				'total_reviews' => 5,
				'total_correct_answers' => 3,
			],
		];
		$title = 'User Progress';

		// Act
		$output = $this->renderProgressView($progress);

		// Assert
		$this->assertStringContainsString('<h1>Your Progress</h1>', $output);
		$this->assertStringContainsString('What is PHP?', $output);
		$this->assertStringContainsString('A programming language', $output);
		$this->assertStringContainsString('10', $output);
		$this->assertStringContainsString('8', $output);
	}

	public function testNoProgressDataDisplaysMessage()
	{
		// Arrange
		$progress = [];
		$title = 'User Progress';

		// Act
		$output = $this->renderProgressView($progress);

		// Assert
		$this->assertStringContainsString('<p>You have not reviewed any cards yet.</p>', $output);
		$this->assertStringNotContainsString('<table>', $output);
	}
}