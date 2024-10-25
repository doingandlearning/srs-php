<?php

require_once __DIR__ . '/../bootstrap.php';
class CardController
{
	private $cardModel;
	private $userCardModel;

	public $redirectLocation;

	public function __construct($cardModel, $userCardModel)
	{
		$this->cardModel = $cardModel;
		$this->userCardModel = $userCardModel;
		$this->redirectLocation = '/index.php';
	}

	public function getAdd()
	{
		require '../views/add_card.php';
	}

	public function add($testMode = false)
	{
		$question = $_POST['question'];
		$answer = $_POST['answer'];
		$this->cardModel->addCard($question, $answer);
		$cardId = $this->cardModel->getLastInsertId();
		$this->userCardModel->addUserCard($_SESSION['user_id'], $cardId);
		$this->redirectLocation = '/controllers/CardController.php?action=list';
		if (!$testMode) {
			header("Location: {$this->redirectLocation}");
			exit; // Ensure script halts after redirection
		}
	}

	public function review()
	{
		$id = $_GET['id'];
		$response = $_POST['response'];
		$userCard = $this->userCardModel->getUserCardById($_SESSION['user_id'], $id);
		$quality = ($response === 'correct') ? 5 : 2;

		$nextReviewData = $this->userCardModel->calculateNextReview($userCard, $quality);
		$this->userCardModel->updateUserCard(
			$id,
			$nextReviewData['interval'],
			$nextReviewData['next_review'],
			$nextReviewData['repetitions'],
			$nextReviewData['ease_factor'],
			$response
		);

		$this->userCardModel->logProgress($_SESSION['user_id'], $userCard['card_id'], $response);
		header('Location: /controllers/CardController.php?action=getNext');
	}

	public function list()
	{
		$cards = $this->cardModel->listCards();
		$userCardModel = $this->userCardModel;
		# log out the content of $cards

		require '../views/list_cards.php';
	}

	public function addToUserList()
	{
		$cardId = $_GET['card_id'];
		if (!$this->userCardModel->isCardInUserList($_SESSION['user_id'], $cardId)) {
			$this->userCardModel->addUserCard($_SESSION['user_id'], $cardId);
		}
		header('Location: /controllers/CardController.php?action=list');
	}

	public function getNext()
	{
		$userCard = $this->userCardModel->getNextUserCard($_SESSION['user_id']);
		if (!$userCard) {
			$userCard = $this->userCardModel->getRandomUserCard($_SESSION['user_id']);
		}

		require '../views/review_cards.php';
	}

	public function progress()
	{
		$progress = $this->userCardModel->getUserProgress($_SESSION['user_id']);
		require '../views/progress.php';
	}
}


// Routing Logic
$cardModel = new Card();
$userCardModel = new UserCard();
$controller = new CardController($cardModel, $userCardModel);

// Determine which action to take
$action = $_GET['action'] ?? 'list'; // Default to 'list' if no action is specified
if (method_exists($controller, $action)) {
	$controller->$action();
} else {
	header('Location: /index.php');
	exit;
}