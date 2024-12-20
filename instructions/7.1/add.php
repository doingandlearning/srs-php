<?php
include './partials/header.php';

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/controllers/CardController.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	include 'views/add_card.php';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new CardController(new Card());
	$controller->add();
}

include './partials/footer.php';

?>