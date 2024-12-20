<?php

require_once __DIR__ . '/../bootstrap.php';

class CardController
{
	private $cardModel;
	private $userCardModel;

	public function __construct($cardModel)
	{
		$this->cardModel = $cardModel;
		$this->userCardModel = new UserCard();
	}

	public function add()
	{
		if (empty($_POST['question']) || empty($_POST['answer'])) {
			throw new InvalidArgumentException('Question and answer are required');
		}

		$question = trim($_POST['question']);
		$answer = trim($_POST['answer']);

		if ($question === '' || $answer === '') {
			throw new InvalidArgumentException('Question and answer are required');
		}

		$this->cardModel->addCard($question, $answer);

		return $this->redirect('list.php');
	}

	protected function redirect($location)
	{
		header("Location: " . $location);
		exit();
	}

	public function list()
	{
		return $this->cardModel->listCards();
	}
	public function addToUserList()
	{
		// Ensure the user is logged in
		if (!isset($_SESSION['user_id'])) {
			header('Location: /login.php');
			exit();
		}

		// Get the card ID from the query parameters
		$cardId = $_GET['card_id'] ?? null;

		if ($cardId) {
			// Check if the card is already in the user's list
			if (!$this->userCardModel->isCardInUserList($_SESSION['user_id'], $cardId)) {
				// Add the card to the user's list
				$this->userCardModel->addUserCard($_SESSION['user_id'], $cardId);
			}
		}

		// Redirect back to the card list
		header('Location: /list.php');
		exit();
	}
}

