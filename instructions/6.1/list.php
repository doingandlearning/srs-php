<?php
include './partials/header.php';

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/controllers/CardController.php';

$controller = new CardController(new Card());
$cards = $controller->list();

include __DIR__ . '/views/list.php';

include './partials/footer.php';
?>