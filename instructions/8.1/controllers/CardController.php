<?php

require_once __DIR__ . '/../bootstrap.php';

class CardController
{
	private $cardModel;
	private $userCardModel;
	public $redirectUrl;

	public function __construct($cardModel, $userCardModel)
	{
		$this->cardModel = $cardModel;
		$this->userCardModel = $userCardModel;
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
		$this->redirectUrl = $location;
		header("Location: " . $location);
		exit();
	}

	public function list()
	{
		$cards = $this->cardModel->listCards();

		// If user is logged in, check which cards are in their list
		if (isset($_SESSION['user_id'])) {
			foreach ($cards as &$card) {
				$card['user_id'] = $this->userCardModel->isCardInUserList($_SESSION['user_id'], $card['id']);
			}
		}

		return $cards;
	}
	public function addToUserList()
	{
		// Ensure the user is logged in
		if (!isset($_SESSION['user_id'])) {
			$this->redirect('/login.php');
			return;
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
		$this->redirect("/list.php");
	}

}

