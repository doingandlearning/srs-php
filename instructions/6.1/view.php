<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');

	// Validate inputs
	if (empty($username) || strlen($username) < 3) {
		die('Error: Username must be at least 3 characters long.');
	}
	if (empty($password) || strlen($password) < 6) {
		die('Error: Password must be at least 6 characters long.');
	}

	// Hash the password
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

	// Store user in the database
	$userModel = new User();
	$userModel->registerUser($username, $hashedPassword);

	// Redirect to the login page
	header('Location: /login.php');
	exit();
}

// If GET request, show the registration form
include __DIR__ . '/views/register.php';
