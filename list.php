<?php
require_once 'bootstrap.php';
require_once 'controllers/CardController.php';

include 'partials/header.php';

$controller = new CardController(new Card(), new UserCard());
$controller->list();
include 'partials/footer.php';
?>