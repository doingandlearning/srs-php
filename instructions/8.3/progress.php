<?php
require_once 'bootstrap.php';
require_once 'controllers/CardController.php';

$controller = new CardController(new Card(), new UserCard());

// Fetch progress data
$progress = $controller->progress();

// Render the progress view
include 'partials/header.php';
include 'views/progress.php';
include 'partials/footer.php';
