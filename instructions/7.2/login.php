<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/models/User.php';

$userModel = $userModel ?? new User();
$response = ['success' => false, 'error' => ''];

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$response = $userModel->login(
		trim($_POST['username'] ?? ''),
		trim($_POST['password'] ?? '')
	);

	if ($response['success']) {
		// Store user data in session
		$_SESSION['user_id'] = $response['user']['id'];
		$_SESSION['username'] = $response['user']['username'];

		// Redirect to dashboard
		header('Location: /list.php');
		return;
	}
}

// Pass the error message (if any) to the view
$error = $response['error'] ?? '';
include __DIR__ . '/views/login.php';