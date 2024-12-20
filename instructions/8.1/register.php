<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/models/User.php';

$userModel = $userModel ?? new User();
$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$response = $userModel->register(
		trim($_POST['username'] ?? ''),
		trim($_POST['password'] ?? '')
	);

	if ($response['success']) {
		header('Location: /login.php');
		return;
	}
}

$error = $response['error'] ?? '';
include __DIR__ . '/views/register.php';