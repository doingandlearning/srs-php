<?php
session_start();


require_once 'bootstrap.php';
require_once 'controllers/CardController.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Handle the form submission (review result)
	$cardId = $_POST['card_id'];
	$response = $_POST['result']; // 'correct' or 'incorrect'

	// Process the review result
	$controller->processReview($cardId, $response);

	// Redirect back to review page to review the next card
	header('Location: /review.php');
	exit();
} else {
	include 'partials/header.php';

	$controller = new CardController(new Card(), new UserCard());
	$controller->getNext();

	include 'partials/footer.php';
}

