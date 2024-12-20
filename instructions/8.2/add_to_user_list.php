<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/controllers/CardController.php';

// Get the card ID from the query string
$cardId = $_GET['card_id'] ?? null;

if (!$cardId) {
	die('Invalid card ID');
}

// Instantiate the controller and call the method
$controller = new CardController(new Card(), new UserCard());

try {
	$controller->addToUserList($_SESSION['user_id'], $cardId);
} catch (InvalidArgumentException $e) {
	die($e->getMessage());
}

// Redirect back to the list of cards
header('Location: /list.php');
exit();
