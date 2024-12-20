<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/CardController.php';
require_once __DIR__ . '/../models/Card.php';

class AddCardViewTest extends TestCase
{
	private $cardMock;
	private $controller;
	/** @var array */
	private $originalServer;

	protected function setUp(): void
	{
		$this->cardMock = $this->createMock(Card::class);
		$this->controller = new CardController($this->cardMock);

		$this->originalServer = $_SERVER;
		$_POST = [];
		$_SERVER = [];
	}

	protected function tearDown(): void
	{
		$_SERVER = $this->originalServer;
		$_POST = [];
	}

	public function testViewRendersForm()
	{
		// Capture output buffer

		ob_start();
		$title = 'Add a new card'; // Required by the view
		include __DIR__ . '/../views/add_card.php';
		$output = ob_get_clean();

		// Assert form elements exist
		$this->assertStringContainsString('<form action="/add.php"', $output);
		$this->assertStringContainsString('method="POST"', $output);
		$this->assertStringContainsString('<textarea name="question"', $output);
		$this->assertStringContainsString('<textarea name="answer"', $output);
		$this->assertStringContainsString('<button type="submit"', $output);
	}

	public function testFormSubmissionHandling()
	{
		// Simulate POST request
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST['question'] = 'Test Question';
		$_POST['answer'] = 'Test Answer';

		// Create a controller with a mock redirect
		$controller = new class ($this->cardMock) extends CardController {
			public $redirectCalled = false;
			public $redirectLocation;

			protected function redirect($location)
			{
				$this->redirectCalled = true;
				$this->redirectLocation = $location;
				return true;
			}
		};

		// Expect card to be added
		$this->cardMock->expects($this->once())
			->method('addCard')
			->with('Test Question', 'Test Answer');

		// Process the form
		$controller->add();

		// Assert redirect was called
		$this->assertTrue($controller->redirectCalled);
		$this->assertEquals('list.php', $controller->redirectLocation);
	}

	public function testGetRequestShowsForm()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		// Mock the header/footer includes
		$this->mockHeaderAndFooter();

		ob_start();
		include __DIR__ . '/../add.php';
		$output = ob_get_clean();

		$this->assertStringContainsString('<form', $output);
		$this->assertStringContainsString('Add a New Card', $output);
	}

	private function mockHeaderAndFooter()
	{
		// Create mock header and footer files in a temporary location
		$tempDir = sys_get_temp_dir() . '/partials';
		if (!is_dir($tempDir)) {
			mkdir($tempDir);
		}

		// Create mock header file
		file_put_contents($tempDir . '/header.php', '<?php /* Mock header */ ?>');
		// Create mock footer file
		file_put_contents($tempDir . '/footer.php', '<?php /* Mock footer */ ?>');

		// Set include path to include our temp directory
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname($tempDir));
	}

	public function __destruct()
	{
		// Clean up temporary files if they exist
		$tempDir = sys_get_temp_dir() . '/partials';
		if (is_dir($tempDir)) {
			@unlink($tempDir . '/header.php');
			@unlink($tempDir . '/footer.php');
			@rmdir($tempDir);
		}
	}

	public function testFormValidationAttributes()
	{
		ob_start();
		include __DIR__ . '/../views/add_card.php';
		$output = ob_get_clean();

		// Check for required attributes
		$this->assertStringContainsString('required', $output);

		// Check for proper labels
		$this->assertStringContainsString('<label for="question"', $output);
		$this->assertStringContainsString('<label for="answer"', $output);
	}

	public function testFormStyling()
	{
		ob_start();
		include __DIR__ . '/../views/add_card.php';
		$output = ob_get_clean();

		// Check for CSS styling
		$this->assertStringContainsString('<style>', $output);
		$this->assertStringContainsString('width: 50%', $output);
	}
}