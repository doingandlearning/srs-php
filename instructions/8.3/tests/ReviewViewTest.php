<?php


use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../bootstrap.php';
class ReviewViewTest extends TestCase
{
	private function renderReviewView($userCard)
	{
		ob_start();
		include __DIR__ . '/../views/review.php';
		return ob_get_clean();
	}

	public function testReviewCardDataRendersCorrectly()
	{
		// Arrange
		$userCard = [
			'id' => 1,
			'question' => 'What is PHP?',
			'answer' => 'A programming language',
			'last_reviewed' => '2024-12-18',
			'last_review_result' => 'correct',
		];
		$title = 'Review Card';

		// Act
		$output = $this->renderReviewView($userCard);

		// Assert
		$this->assertStringContainsString('<h1>Review Card</h1>', $output);
		$this->assertStringContainsString('What is PHP?', $output);
		$this->assertStringContainsString('A programming language', $output);
		$this->assertStringContainsString('Last Reviewed:', $output);
		$this->assertStringContainsString('correct', $output);
	}

	public function testNoCardsToReviewDisplaysMessage()
	{
		// Arrange
		$userCard = null;
		$title = 'Review Card';

		// Act
		$output = $this->renderReviewView($userCard);

		// Assert
		$this->assertStringContainsString('<p>No cards to review.</p>', $output);
		$this->assertStringNotContainsString('<form id="review-form"', $output);
	}
}