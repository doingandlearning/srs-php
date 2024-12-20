<?php
require_once 'bootstrap.php';
require_once 'controllers/CardController.php';

$controller = new CardController(new Card(), new UserCard());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Process review submission
	$controller->review();
} else {
	// Fetch the next card to review
	$userCard = $controller->getNext();

	// Pass the card to the view
	include 'partials/header.php';
	include 'views/review.php';
	include 'partials/footer.php';
}
