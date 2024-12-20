<?php

use PHPUnit\Framework\TestCase;

class ListViewTest extends TestCase
{
	public function testRendersFlashcardsCorrectly()
	{
		// Arrange: Mock flashcard data
		$cards = [
			['id' => 1, 'question' => 'What is PHP?', 'answer' => 'A programming language'],
			['id' => 2, 'question' => 'What is PHPUnit?', 'answer' => 'A testing framework']
		];

		// Start output buffering
		ob_start();

		// Include the view file
		include __DIR__ . '/../views/list.php';

		// Get the rendered content
		$output = ob_get_clean();

		// Assert: Check if the output contains the expected table structure
		$this->assertStringContainsString('<table', $output, "The output does not contain a table.");
		$this->assertStringContainsString('<th>ID</th>', $output, "The table does not contain the 'ID' column header.");
		$this->assertStringContainsString('<th>Question</th>', $output, "The table does not contain the 'Question' column header.");
		$this->assertStringContainsString('<th>Answer</th>', $output, "The table does not contain the 'Answer' column header.");

		// Assert: Check if the mock data is rendered correctly
		foreach ($cards as $card) {
			$this->assertStringContainsString(htmlspecialchars($card['id']), $output, "The output does not contain the expected ID {$card['id']}.");
			$this->assertStringContainsString(htmlspecialchars($card['question']), $output, "The output does not contain the expected question '{$card['question']}'.");
			$this->assertStringContainsString(htmlspecialchars($card['answer']), $output, "The output does not contain the expected answer '{$card['answer']}'.");
		}
	}

	public function testRendersNoFlashcardsMessage()
	{
		// Arrange: Empty flashcard data
		$cards = [];

		// Start output buffering
		ob_start();

		// Include the view file
		include __DIR__ . '/../views/list.php';

		// Get the rendered content
		$output = ob_get_clean();

		// Assert: Check if the output contains the "No flashcards available" message
		$this->assertStringContainsString('No flashcards available.', $output, "The output does not contain the 'No flashcards available' message.");
	}
}
