<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Load essential dependencies
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/models/Card.php';
require_once __DIR__ . '/models/UserCard.php';
