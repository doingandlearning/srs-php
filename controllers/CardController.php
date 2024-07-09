<?php
require_once '../models/Card.php';
require_once '../models/UserCard.php';
require_once '../database.php';

session_start();

$cardModel = new Card();
$userCardModel = new UserCard();
$action = $_GET['action'];

switch ($action) {
	case 'add':
		$question = $_POST['question'];
		$answer = $_POST['answer'];
		$cardModel->addCard($question, $answer);
		$cardId = $cardModel->getLastInsertId();
		$userCardModel->addUserCard($_SESSION['user_id'], $cardId);
		header('Location: /views/list_cards.php');
		break;

	case 'review':
		$id = $_GET['id'];
		$response = $_POST['response'];
		$userCard = $userCardModel->getUserCardById($_SESSION['user_id'], $id);
		$quality = ($response === 'correct') ? 5 : 2;

		$nextReviewData = $userCardModel->calculateNextReview($userCard, $quality);
		$userCardModel->updateUserCard(
			$id,
			$nextReviewData['interval'],
			$nextReviewData['next_review'],
			$nextReviewData['repetitions'],
			$nextReviewData['ease_factor'],
			$response
		);

		// Log progress
		$userCardModel->logProgress($_SESSION['user_id'], $userCard['card_id'], $response);

		header('Location: /controllers/CardController.php?action=getNext');
		break;

	case 'list':
		$cards = $cardModel->listCards();
		require '../views/list_cards.php';
		break;

	case 'addToUserList':
		$cardId = $_GET['card_id'];
		if (!$userCardModel->isCardInUserList($_SESSION['user_id'], $cardId)) {
			$userCardModel->addUserCard($_SESSION['user_id'], $cardId);
		}
		header('Location: /controllers/CardController.php?action=list');
		break;

	case 'getNext':
		$userCard = $userCardModel->getNextUserCard($_SESSION['user_id']);
		if (!$userCard) {
			$userCard = $userCardModel->getRandomUserCard($_SESSION['user_id']);
		}

		require '../views/review_cards.php';
		break;
	case 'progress':
		$progress = $userCardModel->getUserProgress($_SESSION['user_id']);
		require '../views/progress.php';
		break;
	default:
		header('Location: /index.php');
		break;
}
