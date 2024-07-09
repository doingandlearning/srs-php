<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE username = :username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user && password_verify($password, $user['password'])) {
		$_SESSION['user_id'] = $user['id'];
		header('Location: index.php');
		exit();
	} else {
		$error = 'Invalid username or password';
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title>Login</title>
</head>

<body>
	<h1>Login</h1>
	<?php if (isset($error)) : ?>
		<p style="color: red;"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>
	<form action="login.php" method="POST">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username" required>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required>
		<button type="submit">Login</button>
	</form>
	<p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>

</html>